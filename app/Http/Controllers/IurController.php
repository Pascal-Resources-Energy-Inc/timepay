<?php

namespace App\Http\Controllers;

use App\IUR;
use App\Employee;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class IurController extends Controller
{
    public function index()
    { 
        $today = date('Y-m-d');
        $from = isset($request->from) ? $request->from : date('Y-m-d',(strtotime ( '-1 month' , strtotime ( $today) ) ));
        $to = isset($request->to) ? $request->to : date('Y-m-d');
        $status = isset($request->status) ? $request->status : 'Pending';

        // Remove the permission check - allow everyone to access
        // $get_approvers = new EmployeeApproverController;

        $iurs = IUR::with('user')
                        ->where('user_id',auth()->user()->id)
                        ->where('status',$status)
                        ->whereDate('created_at', '>=', $from)
                        ->whereDate('created_at', '<=', $to)
                        ->orderBy('created_at','DESC')
                        ->get();
        // dd($iurs);
        
        // $iur_all = IUR::where('user_id',auth()->user()->id)->get();
        // dd($iur_all);
        // $all_approvers = $get_approvers->get_approvers(auth()->user()->id);

        return view('forms.iur.index', [
            'header' => 'forms',
            'from' => $from,
            'to' => $to,
            'status' => $status,
            'iurs' => $iurs
        ]);
    }

    public function create()
    {
        $getEmployees = Employee::where('id',auth()->user()->id)->get();
        
        return view(
            'forms.iur.create',
            array(
                'header'  => 'forms',
                'getEmployees' => $getEmployees
            )
        );
    }

    public function store(Request $request)
    {
        // Base validation
        $request->validate([
            'type' => 'required',
            'work_location' => 'required',
            'request_for' => 'required',
            'details' => 'required|string',
        ]);

        // =========================
        // UNIFORM VALIDATION
        // =========================
        if (in_array($request->request_for, ['Uniform', 'Both'])) {

            $request->validate([
                'issued' => 'required',
                'size' => 'required',
            ]);

            if ($request->issued === 'Yes') {
                $request->validate([
                    'issued_remarks' => 'required|string',
                    'issued_reasons' => 'required|string',
                ]);
            }

            if ($request->issued === 'No') {
                $request->validate([
                    'issued_reasons' => 'required|string',
                ]);
            }

            if ($request->size === 'other') {
                $request->validate([
                    'other_size' => 'required|string',
                ]);
            }
        }

        // =========================
        // ID VALIDATION
        // =========================
        if (in_array($request->request_for, ['ID', 'Both'])) {

            $request->validate([
                'id_request' => 'required|string',
                'id_picture' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            ]);
        }

        try {

            // =========================
            // FILE UPLOAD
            // =========================
            $imagePath = null;

            if ($request->hasFile('id_picture')) {

                $uploadPath = public_path('uploads/id_pictures');

                if (!\File::exists($uploadPath)) {
                    \File::makeDirectory($uploadPath, 0755, true);
                }

                $file = $request->file('id_picture');
                $filename = time().'_id_'.uniqid().'.'.$file->getClientOriginalExtension();

                $file->move($uploadPath, $filename);

                $imagePath = 'uploads/id_pictures/'.$filename;
            }

            $finalSize = null;

            if (in_array($request->request_for, ['Uniform', 'Both'])) {
                $finalSize = $request->size === 'other'
                    ? $request->other_size
                    : $request->size;
            }

            // Generate IUR Reference
            $latestIur = IUR::orderBy('id', 'desc')->first();

            if ($latestIur && $latestIur->iur_reference) {
                $number = intval(substr($latestIur->iur_reference, 3)) + 1;
            } else {
                $number = 1;
            }

            $iur_reference = 'UIR-' . str_pad($number, 5, '0', STR_PAD_LEFT);
            
            $new_iur = new IUR();
            $new_iur->iur_reference = $iur_reference;
            $new_iur->user_id = auth()->id();
            $new_iur->type = $request->type;
            $new_iur->work_location = $request->work_location;
            $new_iur->request_for = $request->request_for;
            $new_iur->details = $request->details;
            $new_iur->status = 'Pending';

            // Uniform
            $new_iur->issued = $request->issued ?? null;
            $new_iur->issued_remarks = $request->issued_remarks ?? null;
            $new_iur->issued_reasons = $request->issued_reasons ?? null;
            $new_iur->size = $finalSize;
            $new_iur->notes = $request->notes ?? null;

            // ID
            $new_iur->id_request = $request->id_request ?? null;
            $new_iur->id_picture = $imagePath;

            $new_iur->created_by = auth()->id();

            $new_iur->save();

            Alert::success('Successfully Stored')->persistent('Dismiss');
            return back();

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $iur = IUR::findOrFail($id);
        return view('forms.iur.view', [
            'header' => 'forms',
            'iur' => $iur
        ]);
    }

    public function edit($id)
    {
        $iur = IUR::findOrFail($id);

        return view('forms.iur.edit', [
            'header' => 'forms',
            'iur' => $iur
        ]);
    }

    public function update(Request $request, $id)
    {
        $iur = IUR::findOrFail($id);

        // Same validation as store
        $request->validate([
            'type' => 'required',
            'work_location' => 'required',
            'request_for' => 'required',
            'details' => 'required',
        ]);

        // Handle image
        if ($request->hasFile('id_picture')) {
            $uploadPath = public_path('uploads/id_pictures');

            if (!\File::exists($uploadPath)) {
                \File::makeDirectory($uploadPath, 0755, true);
            }

            // delete old
            if ($iur->id_picture && file_exists(public_path($iur->id_picture))) {
                unlink(public_path($iur->id_picture));
            }

            $file = $request->file('id_picture');
            $filename = time().'_id_'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);

            $iur->id_picture = 'uploads/id_pictures/'.$filename;
        }

        // Size logic
        $finalSize = $request->size === 'other'
            ? $request->other_size
            : $request->size;

        $iur->update([
            'type' => $request->type,
            'work_location' => $request->work_location,
            'request_for' => $request->request_for,
            'details' => $request->details,
            'issued' => $request->issued,
            'issued_remarks' => $request->issued_remarks,
            'issued_reasons' => $request->issued_reasons,
            'size' => $finalSize,
            'notes' => $request->notes,
            'id_request' => $request->id_request,
        ]);

        Alert::success('Updated successfully')->persistent('Dismiss');
        return back();
    }

    public function cancel($id)
    {
        $iur = IUR::findOrFail($id);

        if ($iur->status !== 'Pending') {
            return back()->with('error', 'Only pending requests can be cancelled.');
        }

        $iur->status = 'Cancelled';
        $iur->save();

        Alert::success('Request cancelled successfully.')->persistent('Dismiss');
        return back();
    }
}
