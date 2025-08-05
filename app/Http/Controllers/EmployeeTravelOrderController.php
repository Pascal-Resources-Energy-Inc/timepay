<?php

namespace App\Http\Controllers;

use App\Http\Controllers\EmployeeApproverController;
use App\Employee;
use App\EmployeeTo;
use App\ApprovalByAmount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Crypt;

class EmployeeTravelOrderController extends Controller
{
   public function to(Request $request)
    {
        $today = date('Y-m-d');
        $from = $request->from ?? date('Y-m-d', strtotime('-1 month', strtotime($today)));
        $to = $request->to ?? date('Y-m-d');
        $status = $request->status ?? '';
        $limit = $request->limit ?? 10;

        $get_approvers = new EmployeeApproverController;

        $filter_status = $request->status ?? 'Select Status';

        $approvalThreshold = ApprovalByAmount::orderBy('higher_than', 'desc')->first();
        $tos = collect(); // default to empty

        if ($filter_status !== 'Select Status' && $filter_status !== '') {
            $tos = EmployeeTo::with([
                'user', 
                'approver.approver_info',
                'approvedBy', 
                'approvedByHeadDivision',  
                'last_approver'
            ])
                ->where('user_id', auth()->user()->id)
                ->whereDate('date_from', '>=', $from)
                ->whereDate('date_to', '<=', $to);

            if ($filter_status !== 'All') {
                $tos->where('status', $filter_status);
            }

            $tos = $tos->orderBy('created_at', 'DESC')->paginate($limit);

            $tos->getCollection()->transform(function ($to) use ($approvalThreshold) {
                $totalAmount = $to->totalamount_total;
                $to->show_final_approver = false;
                $to->final_approver = null;

                $approvers = $to->approver ?? collect();
                $first = $approvers->first();
                $finalApprover = $approvers->filter(fn($a) => ($a->as_final ?? '') === 'on')->first();

                if ($approvalThreshold && $totalAmount > $approvalThreshold->higher_than) {
                    $to->approver = collect([$first, $finalApprover])->filter()->unique('id');

                    if ($finalApprover && $finalApprover->approver_info) {
                        $to->final_approver = $finalApprover->approver_info;
                        $to->show_final_approver = true;
                    }
                } else {
                    $to->approver = collect([$first])->filter()->unique('id');
                }

                return $to;
            });
        }


        $tos_all = EmployeeTo::with('user')
            ->where('user_id', auth()->user()->id)
            ->get();

        $all_approvers = $get_approvers->get_approvers(auth()->user()->id);
        $approver = $all_approvers->filter(fn($a) => $a->approver_info !== null)->last();

        $latest_to = EmployeeTo::latest('id')->first();
        $next_id = $latest_to ? $latest_to->id + 1 : 1;
        $toNumber = 'TO-' . str_pad($next_id, 5, '0', STR_PAD_LEFT);

        $approvalThresholdValue = $approvalThreshold ? $approvalThreshold->higher_than : 0;
        $approversForJs = $all_approvers->map(function ($approver) {
            return [
                'id' => $approver->id,
                'as_final' => $approver->as_final ?? '',
                'position' => $approver->position ?? '',
                'approver_info' => $approver->approver_info ? [
                'name' => $approver->approver_info->name ?? '',
                'full_name' => $approver->approver_info->full_name ?? '',
                'position' => $approver->approver_info->position ?? ''
                ] : null
            ];
        });

        return view('forms.travelorder.travelorder', [
            'header' => 'forms',
            'all_approvers' => $all_approvers,
            'approver' => $approver,
            'tos' => $tos,
            'tos_all' => $tos_all,
            'from' => $from,
            'to' => $to,
            'status' => $status,
            'limit' => $limit,
            'toNumber' => $toNumber,
            'approvalThreshold' => $approvalThresholdValue,
            'approversForJs' => $approversForJs
        ]);
    }

   public function new(Request $request)
    {
            $new_to = new EmployeeTo;
            $new_to->user_id = Auth::user()->id;
            $emp = Employee::where('user_id', auth()->user()->id)->first();
            $new_to->schedule_id = $emp->schedule_id;
            $new_to->to_number = $request->to_number;
            $new_to->applied_date = $request->applied_date;
            $new_to->remarks = $request->remarks;

            $new_to->destination = $request->destination ?? $request->destination1 ?? null;

            $new_to->date_from = 
                ($request->date_from && $request->departure_time) 
                    ? $request->date_from . ' ' . $request->departure_time . ':00' 
                    : (
                        ($request->date_from1 && $request->departure_time1) 
                            ? $request->date_from1 . ' ' . $request->departure_time1 . ':00' 
                            : null
                    );

            $new_to->date_to = 
                ($request->date_to && $request->arrival_time) 
                    ? $request->date_to . ' ' . $request->arrival_time . ':00' 
                    : (
                        ($request->date_to1 && $request->arrival_time1) 
                            ? $request->date_to1 . ' ' . $request->arrival_time1 . ':00' 
                            : null
                    );

            $new_to->destination_2 = $request->destination_2 ?? $request->destination2 ?? null;

            $new_to->date_from_2 = 
                ($request->date_from_2 && $request->departure_time_2) 
                    ? $request->date_from_2 . ' ' . $request->departure_time_2 . ':00' 
                    : (
                        ($request->date_from2 && $request->departure_time2) 
                            ? $request->date_from2 . ' ' . $request->departure_time2 . ':00' 
                            : null
                    );

            $new_to->date_to_2 = 
                ($request->date_to_2 && $request->arrival_time_2) 
                    ? $request->date_to_2 . ' ' . $request->arrival_time_2 . ':00' 
                    : (
                        ($request->date_to2 && $request->arrival_time2) 
                            ? $request->date_to2 . ' ' . $request->arrival_time2 . ':00' 
                            : null
                    );

            $new_to->destination_3 = $request->destination_3 ?? null;
            $new_to->date_from_3 = ($request->date_from_3 && $request->departure_time_3) ? $request->date_from_3 . ' ' . $request->departure_time_3 . ':00' : null;
            $new_to->date_to_3 = ($request->date_to_3 && $request->arrival_time_3) ? $request->date_to_3 . ' ' . $request->arrival_time_3 . ':00' : null;

            $new_to->destination_4 = $request->destination_4 ?? null;
            $new_to->date_from_4 = ($request->date_from_4 && $request->departure_time_4) ? $request->date_from_4 . ' ' . $request->departure_time_4 . ':00' : null;
            $new_to->date_to_4 = ($request->date_to_4 && $request->arrival_time_4) ? $request->date_to_4 . ' ' . $request->arrival_time_4 . ':00' : null;

            $new_to->destination_5 = $request->destination_5 ?? null;
            $new_to->date_from_5 = ($request->date_from_5 && $request->departure_time_5) ? $request->date_from_5 . ' ' . $request->departure_time_5 . ':00' : null;
            $new_to->date_to_5 = ($request->date_to_5 && $request->arrival_time_5) ? $request->date_to_5 . ' ' . $request->arrival_time_5 . ':00' : null;

            $new_to->purpose = $request->purpose;
            $new_to->rc_code = $request->rc_code;

            $new_to->perdiem_amount = $request->perdiem_amount;
            $new_to->perdiem_numofday = $request->perdiem_numofday;
            $new_to->perdiem_total = $request->perdiem_total;

            $new_to->hotellodging_amount = $request->hotellodging_amount;
            $new_to->hotellodging_numofday = $request->hotellodging_numofday;
            $new_to->hotellodging_total = $request->hotellodging_total;

            $new_to->transpo_amount = $request->transpo_amount;
            $new_to->transpo_numofday = $request->transpo_numofday;
            $new_to->transpo_total = $request->transpo_total;

            $new_to->totalfees_amount = $request->totalfees_amount;
            $new_to->totalfees_numofday = $request->totalfees_numofday;
            $new_to->totalfees_total = $request->totalfees_total;

            $new_to->totalamount_amount = $request->totalamount_amount;
            $new_to->totalamount_numofday = $request->totalamount_numofday;
            $new_to->totalamount_total = $request->totalamount_total;

            $new_to->payment_type = $request->payment_type;
            $new_to->mode_payment = $request->mode_payment;
            $new_to->other_instruct = $request->other_instruct;
            $new_to->liquidation_date = $request->liquidation_date;

            if ($request->filled('sig_image_data')) 
            {
                $sigData = $request->sig_image_data;
                $image_parts = explode(";base64,", $sigData);
                $image_base64 = $image_parts[1];
                $encryptedSignature = Crypt::encryptString($image_base64);
                $new_to->sig_image = $encryptedSignature;
            }

            // Handle regular file attachment
            if ($request->file('attachment')) {
                $logo = $request->file('attachment');
                $original_name = $logo->getClientOriginalName();
                $name = time() . '_' . $original_name;
                $logo->move(public_path() . '/images/', $name);
                $file_name = '/images/' . $name;
                $new_to->attachment = $file_name;
            }

            // Handle signature image - store the file path from the hidden input
            if ($request->filled('sig_image')) {
                $new_to->sig_image = $request->sig_image;
            }

            $new_to->status = 'Pending';
            $new_to->level = 0;
            $new_to->created_by = Auth::user()->id;
            $new_to->save();

            Alert::success('Successfully Stored')->persistent('Dismiss');
            return back();
        }

    public function edit_to(Request $request, $id)
        {
            // Optional: Add validation
            $request->validate([
                'to_number' => 'required|string|max:255',
                'applied_date' => 'required|date',
                'destination' => 'required|string|max:255',
                'date_from' => 'required|date',
                'date_to' => 'required|date|after_or_equal:date_from',
                'purpose' => 'required|string',
                'payment_type' => 'required|in:cash advance,reimbursement',
                'mode_payment' => 'required_if:payment_type,cash advance|in:cash,check,payroll',
                'totalamount_total' => 'required|numeric|min:0',
                'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            ]);

            try {
                // Find the existing travel order
                $to = EmployeeTo::findOrFail($id);
                
                // Check if the user owns this travel order or has permission to edit
                if ($to->user_id !== auth()->user()->id && !auth()->user()->hasRole('hr')) {
                    Alert::error('Unauthorized', 'You do not have permission to edit this travel order.')->persistent('Dismiss');
                    return back();
                }

        $to->destination = $request->destination1;
        $to->date_from = $request->date_from1 && $request->departure_time1
            ? $request->date_from1 . ' ' . $request->departure_time1 . ':00'
            : null;
        $to->date_to = $request->date_to1 && $request->arrival_time1
            ? $request->date_to1 . ' ' . $request->arrival_time1 . ':00'
            : null;

        $to->destination_2 = $request->destination2;
        $to->date_from_2 = $request->date_from2 && $request->departure_time2
            ? $request->date_from2 . ' ' . $request->departure_time2 . ':00'
            : null;
        $to->date_to_2 = $request->date_to2 && $request->arrival_time2
            ? $request->date_to2 . ' ' . $request->arrival_time2 . ':00'
            : null;

        $to->destination_3 = $request->destination3;
        $to->date_from_3 = $request->date_from3 && $request->departure_time3
            ? $request->date_from3 . ' ' . $request->departure_time3 . ':00'
            : null;
        $to->date_to_3 = $request->date_to3 && $request->arrival_time3
            ? $request->date_to3 . ' ' . $request->arrival_time3 . ':00'
            : null;

        $to->destination_4 = $request->destination4;
        $to->date_from_4 = $request->date_from4 && $request->departure_time4
            ? $request->date_from4 . ' ' . $request->departure_time4 . ':00'
            : null;
        $to->date_to_4 = $request->date_to4 && $request->arrival_time4
            ? $request->date_to4 . ' ' . $request->arrival_time4 . ':00'
            : null;

        $to->destination_5 = $request->destination5;
        $to->date_from_5 = $request->date_from5 && $request->departure_time5
            ? $request->date_from5 . ' ' . $request->departure_time5 . ':00'
            : null;
        $to->date_to_5 = $request->date_to5 && $request->arrival_time5
            ? $request->date_to5 . ' ' . $request->arrival_time5 . ':00'
            : null;

                $to->purpose = $request->purpose;
                $to->rc_code = $request->cost_center ?? $request->rc_code;
                $to->approval_remarks = $request->approval_remarks;

                $to->perdiem_amount = $request->perdiem_amount;
                $to->perdiem_numofday = $request->perdiem_numofday;
                $to->perdiem_total = $request->perdiem_total;

                $to->hotellodging_amount = $request->hotellodging_amount;
                $to->hotellodging_numofday = $request->hotellodging_numofday;
                $to->hotellodging_total = $request->hotellodging_total;

                $to->transpo_amount = $request->transpo_amount;
                $to->transpo_numofday = $request->transpo_numofday;
                $to->transpo_total = $request->transpo_total;

                $to->totalfees_amount = $request->totalfees_amount;
                $to->totalfees_numofday = $request->totalfees_numofday;
                $to->totalfees_total = $request->totalfees_total;

                $to->totalamount_amount = $request->totalamount_amount;
                $to->totalamount_numofday = $request->totalamount_numofday;
                $to->totalamount_total = $request->totalamount_total;

                $to->payment_type = $request->payment_type;
                $to->mode_payment = $request->mode_payment;
                $to->other_instruct = $request->other_instruct;

                if ($request->hasFile('attachment')) {
                    if ($to->attachment && file_exists(public_path($to->attachment))) {
                        unlink(public_path($to->attachment));
                    }
                    
                    $file = $request->file('attachment');
                    $original_name = $file->getClientOriginalName();
                    $name = time() . '_' . $original_name;
                    $file->move(public_path('images'), $name);
                    $to->attachment = '/images/' . $name;
                }

                $to->created_by = auth()->user()->id;
                $to->updated_at = now();

                $significantFields = [
                    'destination', 'date_from', 'date_to', 'purpose', 
                    'totalamount_total', 'payment_type'
                ];
                
                $hasSignificantChanges = false;
                foreach ($significantFields as $field) {
                    if ($to->isDirty($field)) {
                        $hasSignificantChanges = true;
                        break;
                    }
                }

                if ($hasSignificantChanges && in_array($to->status, ['Approved', 'Partially Approved'])) {
                    $to->status = 'Pending';
                    $to->level = 0;
                }

                $to->save();

                Alert::success('Travel Order Updated Successfully')->persistent('Dismiss');
                return back();

            } catch (\Exception $e) {
                Alert::error('Error', 'Failed to update travel order: ' . $e->getMessage())->persistent('Dismiss');
                return back();
            }
        }


    public function hr_edit_to(Request $request, $id)
    {
        $new_to = EmployeeTo::findOrFail($id);
        $new_to->applied_date = $request->applied_date;
        $new_to->date_from = $request->date_from;
        $new_to->date_to = $request->date_to;
        $new_to->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    public function upload_toFile(Request $request, $id)
    {
        $new_to = EmployeeTo::findOrFail($id);

        $request->validate([
            'tofile' => 'required|mimes:pdf,xlsx,csv|max:2048',
        ]);

        $originalFileName = $request->file('tofile')->getClientOriginalName();
        $fileName = pathinfo($originalFileName, PATHINFO_FILENAME) . '_' . time() . '.' . $request->file('tofile')->extension();

        $filePath = $request->file('tofile')->storeAs('public/to_files', $fileName);
        $fileUrl = '/storage/to_files/' . $fileName;

        $new_to->to_file = $fileUrl;
        $new_to->save();

        session()->flash('success', 'Successfully Updated');
        return back();
    }

    public function disable_to($id)
    {
        EmployeeTo::Where('id', $id)->update(['status' => 'Cancelled']);
        Alert::success('Travel Order has been cancelled.')->persistent('Dismiss');
        return back();
    }

    public function apply_form()
    {
        // Get the latest ID from the EmployeeTo table
        $latest_to = EmployeeTo::latest('id')->first();

        // If none exists yet, start from 1
        $next_id = $latest_to ? $latest_to->id + 1 : 1;

        // Format the TO number
        $toNumber = 'TO-' . str_pad($next_id, 5, '0', STR_PAD_LEFT);

        return view('forms.travelorder.apply_to', [
            'toNumber' => $toNumber,
        ]);
    }


}


