<?php

namespace App\Http\Controllers;

use App\Http\Controllers\EmployeeApproverController;
use App\Employee;
use App\EmployeeNe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class EmployeeNumberEnrollmentController extends Controller
{
    public function ne(Request $request)
    {
        $today = date('Y-m-d');
        $from = $request->from ?? date('Y-m-d', strtotime('-1 month', strtotime($today)));
        $to = $request->ad ?? date('Y-m-d');
        $status = $request->status ?? '';

        

        $nes = EmployeeNe::with('user')
            ->where('user_id', auth()->user()->id)
            ->where('status', $status)
            ->whereDate('applied_date', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->orderBy('created_at', 'DESC')
            ->get();

        $nes_all = EmployeeNe::with('user')
            ->where('user_id', auth()->user()->id)
            ->get();
            
        $ne_approvers = \App\ApproverSetting::with('user.employee')
                ->where('type_of_form', 'ne')
                ->where('status', 'Active')
                ->get();

        $getApproverForEmployee = function($employee) use ($ne_approvers) {
            return $ne_approvers->first();
        };

        return view('forms.numberenrollment.numberenrollment', [
            'header' => 'forms',
            'ne_approvers' => $ne_approvers,
            'getApproverForEmployee' => $getApproverForEmployee,
            'nes' => $nes,
            'nes_all' => $nes_all,
            'from' => $from,
            'to' => $to,
            'status' => $status,
        ]);
    }

    private function generateNeNumber()
    {
        $lastNe = EmployeeNe::whereNotNull('ne_no')
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastNe || empty($lastNe->ne_no)) {
            return 'CENF-001';
        }

        $lastNumber = (int) substr($lastNe->ne_no, 5);
        $nextNumber = $lastNumber + 1;

        return 'CENF-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    public function new(Request $request)
        {
            $request->validate([
                'enrollment_type' => 'required|string|in:new_employee,lost_sim,allowance_based,other',
                'other' => 'nullable|string|max:255',
                'comment' => 'required|string|max:1000',
                'employee_number' => 'required|string|max:255',
                'position_designation' => 'required|string|max:255',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'cellphone_number' => 'required|string|regex:/^09[0-9]{9}$/|size:11',
                'network_provider' => 'required|string|in:smart_tnt,globe_tm,dito,sun,other',
                'employee_email' => 'required|email|max:255',
            ]);

            $user = Auth::user();
            $employee = $user->employee;

            if (!$employee) {
                Alert::error('Employee profile not found')->persistent('Dismiss');
                return back();
            }

            // Check for existing enrollment
            $existingEnrollment = EmployeeNe::where('cellphone_number', $request->cellphone_number)
                ->where('status', '!=', 'Rejected')
                ->first();
            
            if ($existingEnrollment) {
                Alert::error('This cellphone number is already enrolled or pending approval')->persistent('Dismiss');
                return back();
            }

            $neNumber = $this->generateNeNumber();

            $ne = new EmployeeNe;
            $ne->user_id = $user->id;
            $ne->ne_no = $neNumber;
            $ne->location = $user->employee->location;
            $ne->enrollment_type = $request->enrollment_type;
            $ne->other = $request->other;
            $ne->comment = $request->comment;
            $ne->employee_number = $request->employee_number;
            $ne->position_designation = $request->position_designation;
            $ne->first_name = $request->first_name;
            $ne->last_name = $request->last_name;
            $ne->cellphone_number = $request->cellphone_number;
            $ne->network_provider = $request->network_provider;
            $ne->employee_email = $request->employee_email;
            $ne->applied_date = now();
            $ne->status = 'Pending';
            $ne->created_by = $user->id;
            
            try {
                $ne->save();
                Alert::success("Number enrollment request submitted successfully. Reference Number: {$neNumber}")->persistent('Dismiss');
                return redirect()->back();
            } catch (\Exception $e) {
                \Log::error('Number enrollment save failed: ' . $e->getMessage());
                Alert::error('Failed to submit number enrollment request. Please try again.')->persistent('Dismiss');
                return back()->withInput();
            }
        }

    public function edit_ne(Request $request, $id)
        {
            $request->validate([
                'enrollment_type' => 'required|string|in:new_employee,lost_sim,allowance_based,other',
                'other' => 'nullable|string|max:255',
                'comment' => 'required|string|max:1000',
                'cellphone_number' => 'required|string|regex:/^09[0-9]{9}$/|size:11',
                'network_provider' => 'required|string|in:smart_tnt,globe_tm,dito,sun,other',
            ]);

            try {
                $ne = EmployeeNe::findOrFail($id);
                
                if ($ne->user_id !== auth()->user()->id) {
                    Alert::error('You are not authorized to edit this enrollment')->persistent('Dismiss');
                    return back();
                }

                if (!in_array($ne->status, ['Pending', 'Rejected'])) {
                    Alert::error('This enrollment cannot be edited as it has already been processed')->persistent('Dismiss');
                    return back();
                }

                $existingEnrollment = EmployeeNe::where('cellphone_number', $request->cellphone_number)
                    ->where('status', '!=', 'Rejected')
                    ->where('id', '!=', $id)
                    ->first();
                
                if ($existingEnrollment) {
                    Alert::error('This cellphone number is already enrolled or pending approval')->persistent('Dismiss');
                    return back();
                }

                $ne->enrollment_type = $request->enrollment_type;
                $ne->other = $request->other;
                $ne->comment = $request->comment;
                $ne->cellphone_number = $request->cellphone_number;
                $ne->network_provider = $request->network_provider;
                
                if ($ne->status === 'Rejected') {
                    $ne->status = 'Pending';
                }
                
                $ne->applied_date = now();
                
                $ne->save();

                Alert::success("Number enrollment request updated successfully. Reference Number: {$ne->ne_no}")->persistent('Dismiss');
                return redirect()->back();

            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                Alert::error('Enrollment record not found')->persistent('Dismiss');
                return back();
            } catch (\Exception $e) {
                \Log::error('Number enrollment update failed: ' . $e->getMessage());
                Alert::error('Failed to update number enrollment request. Please try again.')->persistent('Dismiss');
                return back()->withInput();
            }
        }

    public function disable_ne($id)
        {
            EmployeeNe::Where('id', $id)->update(['status' => 'Cancelled']);
            Alert::success('Number Enrollment has been cancelled.')->persistent('Dismiss');
            return back();
        }

}