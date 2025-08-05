<?php

namespace App\Http\Controllers;

use App\Http\Controllers\EmployeeApproverController;
use App\Employee;
use App\User;
use App\PayInstruction;
use App\ApprovalByAmount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Crypt;

class EmployeeAuthorityDeductionController extends Controller
{
    private const VALID_DEDUCTION_TYPES = [
        'Smart Phone Charges',
        'Card Replacement Charge',
        'Company Phone Repair Charge',
        'BPFC Charges',
        'Inventory or Cash Shortage',
        'SSS Loan',
        'HDMF Loan',
        'Others'
    ];

    private function generateAdNumber()
    {
        $latestAd = PayInstruction::whereNotNull('ad_number')
            ->orderBy('id', 'desc')
            ->first();

        if (!$latestAd || !$latestAd->ad_number) {
            return 'AD-00001';
        }

        $latestNumber = preg_replace('/[^0-9]/', '', $latestAd->ad_number);
        $nextNumber = intval($latestNumber) + 1;

        return 'AD-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    public function getNextAdNumber()
    {
        return response()->json([
            'ad_number' => $this->generateAdNumber()
        ]);
    }

    public function ad(Request $request)
    {
        $today = date('Y-m-d');
        $from = $request->from ?? date('Y-m-d', strtotime('-1 month', strtotime($today)));
        $to = $request->to ?? date('Y-m-d');
        $status = $request->status ?? '';

        $get_approvers = new EmployeeApproverController;

        $ads = PayInstruction::with('user', 'approver.approver_info')
            ->where('user_id', auth()->user()->id)
            ->where('status', $status)
            ->whereDate('applied_date', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->orderBy('created_at', 'DESC')
            ->get();

        $approvalThreshold = ApprovalByAmount::orderBy('higher_than', 'desc')->first();

        $ads_all = PayInstruction::with('user')
            ->where('user_id', auth()->user()->id)
            ->get();
        
        $all_approvers = $get_approvers->get_approvers(auth()->user()->id);
        $nextAdNumber = $this->generateAdNumber();

        $ad_approvers = \App\ApproverSetting::with('user.employee')
            ->where('type_of_form', 'ad')
            ->where('status', 'Active')
            ->get();

        $getApproverForEmployee = function($employee, $deductionAmount = 0) use ($ad_approvers, $approvalThreshold) {
            $employee_company = $employee->company_code ?? $employee->company_id ?? null;
            
            if ($employee_company) {
                $companyApprovers = $ad_approvers->filter(function($approver) use ($employee_company) {
                    $approver_company = $approver->user->employee->company_code ?? $approver->user->employee->company_id ?? null;
                    return $approver_company == $employee_company;
                });
                
                if ($companyApprovers->count() > 0) {
                    $availableApprovers = $companyApprovers;
                } else {
                    $availableApprovers = collect([$ad_approvers->first()]);
                }
            } else {
                $availableApprovers = collect([$ad_approvers->first()]);
            }
            
            $sortedApprovers = $availableApprovers->sortBy(function($approver) {
                return $approver->user->employee->level ?? 999;
            });
            
            $first = $sortedApprovers->first();
            $finalApprover = $sortedApprovers->last();
            
            if ($approvalThreshold && $deductionAmount > $approvalThreshold->higher_than) {
                return collect([$first, $finalApprover])->filter()->unique('id');
            } else {
                return collect([$first])->filter()->unique('id');
            }
        };

        return view('forms.authoritydeduct.authoritydeduct', [
            'header' => 'forms',
            'all_approvers' => $all_approvers,
            'ads' => $ads,
            'ads_all' => $ads_all,
            'from' => $from,
            'to' => $to,
            'status' => $status,
            'adNumber' => $nextAdNumber,
            'ad_approvers' => $ad_approvers,
            'getApproverForEmployee' => $getApproverForEmployee,
            'approvalThreshold' => $approvalThreshold,
        ]);
    }

    public function new(Request $request)
    {
        $request->validate([
            'employee_number' => 'nullable|string|exists:employees,employee_number',
            'date_prepared' => 'required|date',
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'type_of_deduction' => 'required|string|in:' . implode(',', self::VALID_DEDUCTION_TYPES),
            'particular' => 'required|string',
            'total_amount' => 'required|numeric|min:0',
            'Amount_Equal' => 'required|in:yes,no',
            'no_of_deductions' => 'required|integer|min:1',
            'amount_per_cutoff' => 'required|numeric|min:0',
            'start_of_deduction' => 'required|date',
            'employee_signature' => 'required|string',
            'personal_email' => 'required|email',
            'date_issued' => 'nullable|date',
        ]);

        $authority = new PayInstruction;
        
        if ($request->filled('employee_number')) {
            $selectedEmployee = Employee::where('employee_number', $request->employee_number)->first();
            if ($selectedEmployee) {
                $authority->user_id = $selectedEmployee->user_id;
                $emp = $selectedEmployee;
            } else {
                $authority->user_id = Auth::id();
                $emp = Employee::where('user_id', auth()->user()->id)->first();
            }
        } else {
            $authority->user_id = Auth::id();
            $emp = Employee::where('user_id', auth()->user()->id)->first();
        }
        
        $authority->ad_number = $request->ad_number;
        $authority->applied_date = $request->date_prepared;
        $authority->name = $request->name;
        $authority->designation = $request->designation;
        $authority->department = $request->department;
        $authority->location = $request->location;
        $authority->type_of_deduction = $request->type_of_deduction;
        $authority->particular = $request->particular;
        $authority->amount = $request->total_amount;
        $authority->amount_equal = $request->Amount_Equal;
        $authority->frequency = $request->no_of_deductions;
        $authority->deductible = $request->amount_per_cutoff;
        $authority->start_date = $request->start_of_deduction;
        $authority->date_issued = $request->date_issued;
        $authority->requestor_email = $request->personal_email;

        if ($request->filled('employee_signature')) {
            $sigData = $request->employee_signature;
            $image_parts = explode(";base64,", $sigData);
            if (count($image_parts) === 2) {
                $image_base64 = $image_parts[1];
                $encryptedSignature = Crypt::encryptString($image_base64);
                $authority->employee_signature = $encryptedSignature;
            }
        }

        $authority->status = 'Pending';
        $authority->level = 0;
        $authority->created_by = Auth::id();
        $authority->save();

        Alert::success('Authority to Deduct submitted successfully')->persistent('Dismiss');
        return back();
    }

    public function newperEmployee(Request $request)
        {
            $request->validate([
                'ad_number' => 'required|string',
                'employee_number' => 'required|string|exists:employees,employee_number',
                'date_prepared' => 'required|date',
                'name' => 'required|string|max:255',
                'designation' => 'required|string|max:255',
                'department' => 'required|string|max:255',
                'location' => 'required|string|max:255',
                'type_of_deduction' => 'required|string|in:SSS Loan,HDMF Loan',
                'particular' => 'required|string',
                'total_amount' => 'required|numeric|min:0',
                'Amount_Equal' => 'required|in:yes,no',
                'no_of_deductions' => 'required|integer|min:1|max:120',
                'amount_per_cutoff' => 'required|numeric|min:0',
                'start_of_deduction' => 'required|date',
                'personal_email' => 'required|email',
                'date_issued' => 'nullable|date',
            ]);

            try {
                $selectedEmployee = Employee::where('employee_number', $request->employee_number)->first();
                
                if (!$selectedEmployee) {
                    Alert::error('Selected employee not found')->persistent('Dismiss');
                    return back()->withInput();
                }

                $authority = new PayInstruction();
                $authority->user_id = $selectedEmployee->user_id ?? $selectedEmployee->id;
                
                $authority->ad_number = $request->ad_number;
                $authority->applied_date = $request->date_prepared;
                $authority->name = $request->name;
                $authority->designation = $request->designation;
                $authority->department = $request->department;
                $authority->location = $request->location;
                $authority->type_of_deduction = $request->type_of_deduction;
                $authority->particular = $request->particular;
                $authority->amount = $request->total_amount;
                $authority->amount_equal = $request->Amount_Equal;
                $authority->frequency = $request->no_of_deductions;
                $authority->deductible = $request->amount_per_cutoff;
                $authority->start_date = $request->start_of_deduction;
                $authority->date_issued = $request->date_issued ?? now()->format('Y-m-d');
                $authority->requestor_email = $request->personal_email;

                $authority->status = 'Approved';
                $authority->level = 1;
                $authority->created_by = Auth::id();
                
                $authority->save();

                Alert::success('Authority to Deduct submitted successfully')->persistent('Dismiss');
                return back();
                
            } catch (\Exception $e) {
                \Log::error('Error creating Authority to Deduct: ' . $e->getMessage());
                Alert::error('An error occurred while submitting the form. Please try again.')->persistent('Dismiss');
                return back()->withInput();
            }
        }

    public function edit_ad(Request $request, $id)
    {
        try {
            $authority = PayInstruction::where('id', $id)
                ->where('user_id', auth()->user()->id)
                ->firstOrFail();

            if ($authority->status !== 'Pending') {
                Alert::error('Cannot edit this record. Only pending records can be modified.')->persistent('Dismiss');
                return back();
            }

            $validatedData = $request->validate([
                'date_prepared' => 'required|date',
                'name' => 'required|string|max:255',
                'designation' => 'required|string|max:255',
                'department' => 'required|string|max:255',
                'work_location' => 'required|string|max:255',
                'type_of_deduction' => 'required|string|in:' . implode(',', self::VALID_DEDUCTION_TYPES),
                'particular' => 'required|string',
                'total_amount' => 'required|numeric|min:0',
                'Amount_Equal' => 'required|in:yes,no',
                'no_of_deductions' => 'required|integer|min:1',
                'amount_per_cutoff' => 'required|numeric|min:0',
                'start_of_deduction' => 'required|date',
                'personal_email' => 'required|email',
                'date_issued' => 'nullable|date',
                'employee_signature' => 'nullable|string',
                'keep_existing_signature' => 'nullable|boolean',
            ]);

            $calculatedAmountPerCutoff = $validatedData['total_amount'] / $validatedData['no_of_deductions'];
            
            $authority->applied_date = $validatedData['date_prepared'];
            $authority->name = $validatedData['name'];
            $authority->designation = $validatedData['designation'];
            $authority->department = $validatedData['department'];
            $authority->location = $validatedData['location'];
            $authority->type_of_deduction = $validatedData['type_of_deduction'];
            $authority->particular = $validatedData['particular'];
            $authority->amount = $validatedData['total_amount'];
            $authority->amount_equal = $validatedData['Amount_Equal'];
            $authority->frequency = $validatedData['no_of_deductions'];
            $authority->deductible = round($calculatedAmountPerCutoff, 2);
            $authority->start_date = $validatedData['start_of_deduction'];
            $authority->date_issued = $validatedData['date_issued'];
            $authority->requestor_email = $validatedData['personal_email'];

            if ($request->filled('employee_signature') && !$request->has('keep_existing_signature')) {
                $sigData = $request->employee_signature;
                $image_parts = explode(";base64,", $sigData);
                if (count($image_parts) === 2) {
                    $image_base64 = $image_parts[1];
                    $encryptedSignature = Crypt::encryptString($image_base64);
                    $authority->employee_signature = $encryptedSignature;
                }
            }

            $authority->updated_at = now();
            $authority->save();

            Alert::success('Authority to Deduct updated successfully')->persistent('Dismiss');
            return redirect()->back()->with('success', 'Authority to Deduct updated successfully');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Alert::error('Record not found or you do not have permission to edit this record.')->persistent('Dismiss');
            return back()->withErrors(['error' => 'Record not found or access denied']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log validation errors for debugging
            Log::error('Validation failed for Authority Deduction edit:', [
                'user_id' => auth()->id(),
                'authority_id' => $id,
                'validation_errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            
            Alert::error('Validation failed. Please check your input and try again.')->persistent('Dismiss');
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating Authority Deduction: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'authority_id' => $id,
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            Alert::error('An error occurred while updating the record. Please try again.')->persistent('Dismiss');
            return back()->withErrors(['error' => 'Update failed'])->withInput();
        }
    }

    public function disable_ad($id)
    {
        PayInstruction::Where('id', $id)->update(['status' => 'Cancelled']);
        Alert::success('Deduction has been cancelled.')->persistent('Dismiss');
        return back();
    }

    public static function getValidDeductionTypes()
    {
        return self::VALID_DEDUCTION_TYPES;
    }
}