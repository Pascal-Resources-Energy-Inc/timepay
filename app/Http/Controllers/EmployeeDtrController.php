<?php

namespace App\Http\Controllers;
use App\Http\Controllers\EmployeeApproverController;
use App\Employee;
use App\EmployeeDtr;
use App\EmployeeDtrAttachment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;

class EmployeeDtrController extends Controller
{
    public function dtr(Request $request)
    { 
        $today = date('Y-m-d');
        $from = isset($request->from) ? $request->from : date('Y-m-d',(strtotime ( '-1 month' , strtotime ( $today) ) ));
        $to = isset($request->to) ? $request->to : date('Y-m-d');
        $status = isset($request->status) ? $request->status : 'Pending';

        $get_approvers = new EmployeeApproverController;
        $dtrs = EmployeeDtr::with('user', 'attachmentFiles')
            ->where('user_id',auth()->user()->id)
            ->where('status',$status)
            ->whereDate('created_at','>=',$from)
            ->whereDate('created_at','<=',$to)
            ->orderBy('created_at','DESC')
            ->get();

        $dtrs_all = EmployeeDtr::with('user')
            ->where('user_id',auth()->user()->id)
            ->get();

        $all_approvers = $get_approvers->get_approvers(auth()->user()->id);
        return view('forms.dtr.dtr',
            array(
                'header' => 'forms',
                'all_approvers' => $all_approvers,
                'dtrs' => $dtrs,
                'dtrs_all' => $dtrs_all,
                'from' => $from,
                'to' => $to,
                'status' => $status,
            ));
    }

    public function new(Request $request)
    {
        $uploadedAttachments = $this->validateDtrAttachments(
            $request,
            3
        );
        $check_dtr = EmployeeDtr::where('user_id',auth()->user()->id)
            ->where('dtr_date',$request->dtr_date)
            ->where('status','Pending')
            ->first();

        if($check_dtr){
            Alert::warning('DTR Date has been exist!')->persistent('Dismiss');
            return back();
        }else{
            $new_dtr = new EmployeeDtr;
            $new_dtr->user_id = Auth::user()->id;
            $new_dtr->dtr_date = $request->dtr_date;
            $emp = Employee::where('user_id',auth()->user()->id)->first();
            $new_dtr->schedule_id = $emp->schedule_id;
            $new_dtr->correction = $request->correction;
            $new_dtr->adjustment_type = $request->adjustment_type;

            // if($request->correction == 'Both'){
            //     $stime = $request->time_in;
            //     $etime = $request->time_out;   
            //     $new_dtr->time_in = $request->dtr_date.' '.$request->time_in;
            //     $new_dtr->time_out = $request->dtr_date.' '.$request->time_out;     
            //     if($stime > $etime ){
            //         $new_dtr->time_out = date('Y-m-d', strtotime($request->dtr_date. ' + 1 day')).' '.$request->time_out;
            //     }             
            // }else if($request->correction == 'Time-in'){
            //     $new_dtr->time_in = $request->dtr_date.' '.$request->time_in;
            //     $new_dtr->time_out = null;
            // }else{
            //     $new_dtr->time_in = null;
            //     $new_dtr->time_out = $request->dtr_date.' '.$request->time_out;
            // }      

            $adjusted_times = $this->getAdjustedDtrTimes($request);

            if($request->correction == 'Both'){
                $new_dtr->time_in = $adjusted_times['time_in'];
                $new_dtr->time_out = $adjusted_times['time_out'];                
            }else if($request->correction == 'Time-in'){
                $new_dtr->time_in = $adjusted_times['time_in'];
                $new_dtr->time_out = null;
            }else{
                $new_dtr->time_in = null;
                $new_dtr->time_out = $adjusted_times['time_out'];
            }      

            $new_dtr->remarks = $request->remarks;

            $new_dtr->status = 'Pending';
            $new_dtr->level = 0;
            $new_dtr->created_by = Auth::user()->id;
            // dd($new_dtr);
            $new_dtr->save();

            $this->storeDtrAttachments(
                $new_dtr,
                $uploadedAttachments
            );

            Alert::success('Successfully Stored')->persistent('Dismiss');
            return back();
        }

    } 

    public function edit_dtr(Request $request, $id)
    {
        $new_dtr = EmployeeDtr::findOrFail($id);

        $currentAttachmentCount = $this->currentDtrAttachmentCount($new_dtr);
        $availableSlots = max(0, 3 - $currentAttachmentCount);
        $replacementAttachments = $this->validateDtrAttachmentReplacements(
            $request,
            $new_dtr
        );
        $uploadedAttachments = $this->validateDtrAttachments(
            $request,
            $availableSlots
        );

        $new_dtr->user_id = Auth::user()->id;
        $new_dtr->dtr_date = $request->dtr_date;
        $new_dtr->correction = $request->correction;
        $new_dtr->adjustment_type = $request->adjustment_type;
        // if($request->correction == 'Both'){
        //     $stime = $request->time_in;
        //     $etime = $request->time_out;   
        //     $new_dtr->time_in = $request->dtr_date.' '.$request->time_in;
        //     $new_dtr->time_out = $request->dtr_date.' '.$request->time_out;     
        //     if($stime > $etime ){
        //         $new_dtr->time_out = date('Y-m-d', strtotime($request->dtr_date. ' + 1 day')).' '.$request->time_out;
        //     }             
        // }else if($request->correction == 'Time-in'){
        //     $new_dtr->time_in = $request->dtr_date.' '.$request->time_in;
        //     $new_dtr->time_out = null;
        // }else{
        //     $new_dtr->time_in = null;
        //     $new_dtr->time_out = $request->dtr_date.' '.$request->time_out;
        // }     

        $adjusted_times = $this->getAdjustedDtrTimes($request);

        if($request->correction == 'Both'){
            $new_dtr->time_in = $adjusted_times['time_in'];
            $new_dtr->time_out = $adjusted_times['time_out'];                
        }else if($request->correction == 'Time-in'){
            $new_dtr->time_in = $adjusted_times['time_in'];
            $new_dtr->time_out = null;
        }else{
            $new_dtr->time_in = null;
            $new_dtr->time_out = $adjusted_times['time_out'];
        }  

        $new_dtr->remarks = $request->remarks;

        $new_dtr->status = 'Pending';
        $new_dtr->level = 0;
        $new_dtr->created_by = Auth::user()->id;
        // dd($new_dtr);
        $new_dtr->save();

        $this->replaceDtrAttachments(
            $new_dtr,
            $replacementAttachments
        );

        $this->storeDtrAttachments(
            $new_dtr,
            $uploadedAttachments
        );

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }    

    public function disable_dtr($id)
    {
        EmployeeDtr::Where('id', $id)->update(['status' => 'Cancelled']);
        Alert::success('DTR Correction has been cancelled.')->persistent('Dismiss');
        return back();
    }

    private function getAdjustedDtrTimes(Request $request)
    {
        $has_penalty = EmployeeDtr::isPenaltyAdjustmentType($request->adjustment_type);

        return [
            'time_in' => $this->adjustDtrTime($request->time_in, $has_penalty ? 15 : 0),
            'time_out' => $this->adjustDtrTime($request->time_out, $has_penalty ? -15 : 0),
        ];
    }

    private function adjustDtrTime($time, $minutes)
    {
        if(!$time){
            return null;
        }

        return Carbon::parse($time)->addMinutes($minutes)->format('Y-m-d H:i:s');
    }    

    private function validateDtrAttachments(
        Request $request,
        $availableSlots
    ) {
        /*
         * "attachment" supports the old single-file frontend during rollout.
         * "attachments" supports the new multiple-file frontend.
         */
        $request->validate([
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png|max:5120',
        ]);

        $files = collect($request->file('attachments', []))
            ->filter();

        if ($request->hasFile('attachment')) {
            $files->push($request->file('attachment'));
        }

        if ($files->count() > $availableSlots) {
            $message = $availableSlots > 0
                ? 'You may upload only '.$availableSlots.' more attachment(s).'
                : 'This DTR correction already has the maximum of 3 attachments.';

            throw ValidationException::withMessages([
                'attachments' => $message,
            ]);
        }

        return $files;
    }

    private function validateDtrAttachmentReplacements(
        Request $request,
        EmployeeDtr $dtr
    ) {
        $request->validate([
            'replacement_attachments' => 'nullable|array',
            'replacement_attachments.*' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
        ]);

        $files = collect(
            $request->file('replacement_attachments', [])
        )->filter();

        if ($files->isEmpty()) {
            return $files;
        }

        $validAttachmentIds = $dtr->attachmentFiles()
            ->pluck('id')
            ->map(function ($id) {
                return (string) $id;
            });

        $hasLegacyAttachment = $dtr->attachment
            && !$dtr->attachmentFiles()
                ->where('file_path', $dtr->attachment)
                ->exists();

        foreach ($files->keys() as $attachmentKey) {
            $attachmentKey = (string) $attachmentKey;
            $isValidLegacyKey = $attachmentKey === 'legacy'
                && $hasLegacyAttachment;
            $isValidAttachmentId = $validAttachmentIds
                ->contains($attachmentKey);

            if (!$isValidLegacyKey && !$isValidAttachmentId) {
                throw ValidationException::withMessages([
                    'replacement_attachments' => 'One of the selected attachments is no longer available.',
                ]);
            }
        }

        return $files;
    }

    private function currentDtrAttachmentCount(EmployeeDtr $dtr)
    {
        $storedPaths = $dtr->attachmentFiles()
                           ->pluck('file_path');

        $count = $storedPaths->count();

        /*
         * Count the legacy attachment only when it is not already
         * present in the new attachment table.
         */
        if (
            $dtr->attachment &&
            !$storedPaths->contains($dtr->attachment)
        ) {
            $count++;
        }

        return $count;
    }

    private function storeDtrAttachments(
        EmployeeDtr $dtr,
        $files
    ) {
        if ($files->isEmpty()) {
            return;
        }

        foreach ($files as $file) {
            $filePath = $this->storeDtrAttachmentFile($file);

            $dtr->attachmentFiles()->create([
                'file_path' => $filePath,
            ]);

            /*
             * Dual-write the first attachment to the existing column.
             * Older application code can still display at least one file.
             */
            if (empty($dtr->attachment)) {
                $dtr->attachment = $filePath;
            }
        }

        if ($dtr->isDirty('attachment')) {
            $dtr->save();
        }
    }

    private function replaceDtrAttachments(
        EmployeeDtr $dtr,
        $replacementFiles
    ) {
        if ($replacementFiles->isEmpty()) {
            return;
        }

        $replacedPaths = collect();

        foreach ($replacementFiles as $attachmentKey => $file) {
            $filePath = $this->storeDtrAttachmentFile($file);

            if ((string) $attachmentKey === 'legacy') {
                $replacedPaths->push($dtr->attachment);
                $dtr->attachment = $filePath;
                continue;
            }

            $attachment = $dtr->attachmentFiles()
                ->whereKey($attachmentKey)
                ->firstOrFail();
            $oldPath = $attachment->file_path;
            $replacedPaths->push($oldPath);

            $attachment->update([
                'file_path' => $filePath,
            ]);

            if ($dtr->attachment === $oldPath) {
                $dtr->attachment = $filePath;
            }
        }

        if ($dtr->isDirty('attachment')) {
            $dtr->save();
        }

        $replacedPaths
            ->filter()
            ->unique()
            ->each(function ($oldPath) {
                $this->deleteDtrAttachmentFileIfUnused($oldPath);
            });
    }

    private function storeDtrAttachmentFile($file)
    {
        $directory = public_path('images/dtr-attachments');

        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $extension = strtolower(
            $file->getClientOriginalExtension()
        );
        $storedName = (string) Str::uuid().'.'.$extension;

        $file->move($directory, $storedName);

        return '/images/dtr-attachments/'.$storedName;
    }

    private function deleteDtrAttachmentFileIfUnused($filePath)
    {
        $isStillUsed = EmployeeDtr::where('attachment', $filePath)->exists()
            || EmployeeDtrAttachment::where('file_path', $filePath)->exists();

        if ($isStillUsed) {
            return;
        }

        $publicRoot = realpath(public_path());
        $relativePath = ltrim(
            str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $filePath),
            DIRECTORY_SEPARATOR
        );
        $resolvedPath = realpath(public_path($relativePath));

        if (!$publicRoot || !$resolvedPath || !File::isFile($resolvedPath)) {
            return;
        }

        $publicRootPrefix = rtrim(
            $publicRoot,
            DIRECTORY_SEPARATOR
        ).DIRECTORY_SEPARATOR;

        if (strpos($resolvedPath, $publicRootPrefix) !== 0) {
            return;
        }

        File::delete($resolvedPath);
    }
}


