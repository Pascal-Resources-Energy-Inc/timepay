<?php

namespace App\Http\Controllers;
use App\EmployeeMta;
use App\Http\Controllers\EmployeeApproverController;
use App\Employee;
use App\ApproverSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Mail\MtaNotificationMail;
use Illuminate\Support\Facades\Mail;
use App\User;
class EmployeeMtaController extends Controller
{
    public function index(Request $request)
    {
        $today = date('Y-m-d');
        $from = isset($request->from) ? $request->from : date('Y-m-d',(strtotime ( '-1 month' , strtotime ( $today) ) ));
        $to = isset($request->to) ? $request->to : date('Y-m-d');
        $status = isset($request->status) ? $request->status : 'Pending';

        $get_approvers = new EmployeeApproverController;
        $mtas = EmployeeMta::with(['user', 'approverMta'])
                            ->where('user_id',auth()->user()->id)
                            ->where('status',$status)
                            ->whereDate('created_at','>=',$from)
                            ->whereDate('created_at','<=',$to)
                            ->orderBy('created_at','DESC')
                            ->get();
        
        $mtas_all = EmployeeMta::with('user')
                            ->where('user_id',auth()->user()->id)
                            ->get();
        
        $all_approvers = $get_approvers->get_approvers(auth()->user()->id);
        return view('forms.mta.index',
        array(
            'header' => 'forms',
            'all_approvers' => $all_approvers,
            'mtas' => $mtas,
            'mtas_all' => $mtas_all,
            'from' => $from,
            'to' => $to,
            'status' => $status,
        ));
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'mta_date' => 'required|date',
    //         'work_location' => 'required',
    //         'liters_loaded' => 'required',
    //         'petron_price' => 'required|numeric',
    //         'sales_invoice_number' => 'required|string',
    //         'notes' => 'required|string',
    //     ]);

    //     $duplicate = EmployeeMta::where('user_id', Auth::id())
    //         ->where('mta_date', $request->mta_date)
    //         ->exists();

    //     if ($duplicate) {
    //         Alert::error('Duplicate Date', 'You already have an MTA with the same transaction date.')->persistent('Dismiss');
    //         return back()->withInput();
    //     }

    //     $liters = floatval(preg_replace('/[^0-9.]/', '', $request->liters_loaded));
    //     $amount = $liters * floatval($request->petron_price);

    //     $new_mta = new EmployeeMta;
    //     $new_mta->user_id = Auth::user()->id;
    //     $new_mta->mta_date = $request->mta_date;
    //     $new_mta->work_location = $request->work_location;
    //     $new_mta->liters_loaded = $request->liters_loaded;
    //     $new_mta->petron_price = $request->petron_price;
    //     $new_mta->mta_amount = $amount;
    //     $new_mta->sales_invoice_number = $request->sales_invoice_number;
    //     $new_mta->notes = $request->notes;
        
    //     // if($request->file('attachment')){
    //     //     $logo = $request->file('attachment');
    //     //     $original_name = $logo->getClientOriginalName();
    //     //     $name = time() . '_' . $logo->getClientOriginalName();
    //     //     $logo->move(public_path() . '/images/', $name);
    //     //     $file_name = '/images/' . $name;
    //     //     $new_mta->attachment = $file_name;
    //     // }

    //     // ✅ FILE UPLOAD (IMPROVED)
    //     if ($request->hasFile('attachment')) {
    //         $file = $request->file('attachment');
    //         $name = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
    //         $file->move(public_path('images'), $name);
    //         $new_mta->attachment = '/images/' . $name;
    //     }
        
    //     $new_mta->status = 'Pending';
    //     $new_mta->level = 0;
    //     $new_mta->created_by = Auth::user()->id;
    //     $new_mta->save();

    //     $approvers = ApproverSetting::where('type_of_form', 'mta')
    //     ->where(function ($q) use ($request) {
    //         $q->where('work_location', $request->work_location); 
    //     })
    //     ->where('status', 'Active')
    //     ->pluck('user_id');

    //     if ($approvers->count() > 0) {

    //         $users = User::whereIn('id', $approvers)->get();

    //         foreach ($users as $user) {
    //             if (!empty($user->email)) {
    //                 Mail::to($user->email)->send(new MtaNotificationMail($new_mta, $user));
    //             }
    //         }
    //     }
    
    //     Alert::success('Successfully Stored')->persistent('Dismiss');
    //     return back();
    // }

    public function store(Request $request)
    {
        $this->validate($request, [
            'mta_date' => 'required|date',
            'work_location' => 'required|string',
            'liters_loaded' => 'required|numeric',
            'petron_price' => 'required|numeric',
            'sales_invoice_number' => 'required|string',
            'notes' => 'required|string',
        ]);

        // ✅ DUPLICATE CHECK
        // $duplicate = EmployeeMta::where('user_id', Auth::id())
        //     ->where('status', '!=', 'Cancelled') 
        //     ->where('status', '!=', 'Disapproved')
        //     ->where('payment_status', '!=', 'Disapproved')
        //     ->whereDate('mta_date', $request->mta_date)
        //     ->exists();
        $duplicate = EmployeeMta::where('user_id', Auth::id())
                ->whereNotIn('status', ['Cancelled', 'Disapproved'])
                ->where('payment_status', '!=', 'Disapproved')
                ->whereDate('mta_date', $request->mta_date)
                ->exists();

        if ($duplicate) {
            Alert::error('Duplicate Date', 'You already have an MTA with the same transaction date.')
                ->persistent('Dismiss');

            return redirect()->back()->withInput();
        }

        $latestMta = EmployeeMta::orderBy('id', 'desc')->first();

        if ($latestMta && $latestMta->mta_reference) {
            $number = intval(substr($latestMta->mta_reference, 4)) + 1;
        } else {
            $number = 1;
        }

        $mta_reference = 'MTA-' . str_pad($number, 5, '0', STR_PAD_LEFT);

        // ✅ SAFE COMPUTE
        $liters = (float) $request->liters_loaded;
        $price  = (float) $request->petron_price;
        $amount = $liters * $price;

        $new_mta = new EmployeeMta();
        $new_mta->mta_reference = $mta_reference;
        $new_mta->user_id = Auth::id();
        $new_mta->mta_date = $request->mta_date;
        $new_mta->work_location = $request->work_location;
        $new_mta->liters_loaded = $liters;
        $new_mta->petron_price = $price;
        $new_mta->mta_amount = $amount;
        $new_mta->sales_invoice_number = $request->sales_invoice_number;
        $new_mta->notes = $request->notes;

        // if ($request->hasFile('attachment')) {
        //     $file = $request->file('attachment');
        //     $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());

        //     $file->move(public_path('images'), $filename);

        //     $new_mta->attachment = '/images/' . $filename;
        // }

        if ($request->hasFile('attachment')) {

            $file = $request->file('attachment');

            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());

            // ✅ Ensure folder exists
            $destination = public_path('images');

            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }

            $file->move($destination, $filename);

            $new_mta->attachment = 'images/' . $filename; // ❗ REMOVE leading slash
        }

        $new_mta->status = 'Pending';
        $new_mta->level = 0;
        $new_mta->created_by = Auth::id();
        $new_mta->save();

        // ✅ GET APPROVERS (IMPROVED LOGIC)
        $approvers = ApproverSetting::where('type_of_form', 'mta')
            ->where('status', 'Active')
            ->where(function ($q) use ($request) {
                $q->where('work_location', $request->work_location); 
            })
            ->pluck('user_id');

        if (!$approvers->isEmpty()) {

            $users = User::whereIn('id', $approvers)->get();

            // foreach ($users as $user) {
            //     if (!empty($user->email)) {
            //         try {
            //             Mail::to($user->email)
            //                 ->send(new MtaNotificationMail($new_mta, $user));
            //         } catch (\Exception $e) {
            //             \Log::error('Mail Error: ' . $e->getMessage());
            //         }
            //     }
            // }
            foreach ($users as $user) {
                if (!empty($user->email)) {
                    try {
                        Mail::to($user->email)
                            ->send(new MtaNotificationMail($new_mta, $user));
                    } catch (\Exception $e) {
                        \Log::error('Mail Error for ' . $user->email . ': ' . $e->getMessage());
                    }
                }
            }
        }

        Alert::success('Successfully Stored')->persistent('Dismiss');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EmployeeMta  $employeeMta
     * @return \Illuminate\Http\Response
     */
    public function cancel($id)
    {
        $mta = EmployeeMta::findOrFail($id);

        if ($mta->status !== 'Pending') {
            return back()->with('error', 'Only pending requests can be cancelled.');
        }

        $mta->status = 'Cancelled';
        $mta->save();

        Alert::success('Request cancelled successfully.')->persistent('Dismiss');
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EmployeeMta  $employeeMta
     * @return \Illuminate\Http\Response
     */
    public function edit(EmployeeMta $employeeMta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EmployeeMta  $employeeMta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $edit_mta = EmployeeMta::findOrFail($id);
        // $edit_mta->user_id = Auth::user()->id;
        $edit_mta->mta_date = $request->mta_date;
        $edit_mta->work_location = $request->work_location;
        $edit_mta->liters_loaded = $request->liters_loaded;
        $edit_mta->petron_price = $request->petron_price;
        $edit_mta->mta_amount = floatval(preg_replace('/[^0-9.]/', '', $request->liters_loaded)) * floatval($request->petron_price);
        $edit_mta->sales_invoice_number = $request->sales_invoice_number;
        $edit_mta->notes = $request->notes;

        if($request->file('attachment')){
            $logo = $request->file('attachment');
            $original_name = $logo->getClientOriginalName();
            $name = time() . '_' . $logo->getClientOriginalName();
            $logo->move(public_path() . '/images/', $name);
            $file_name = '/images/' . $name;
            $edit_mta->attachment = $file_name;
        }
        
        $edit_mta->level = 0;
        $edit_mta->updated_by = Auth::user()->id;
        $edit_mta->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EmployeeMta  $employeeMta
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmployeeMta $employeeMta)
    {
        //
    }

    public function mtaProcess(Request $request)
    {
        $today = now();

        $from_date = $request->from ?? $today->copy()->subMonths(3)->format('Y-m-d');
        $to_date   = $request->to ?? $today->format('Y-m-d');
        $filter_payment_status = $request->payment_status ?? 'Approved';
        // $approver_id = auth()->id();

        $baseQuery = EmployeeMta::with(['approver.approver_info', 'user', 'approverMta'])
            ->whereBetween('mta_date', [$from_date, $to_date])
            ->where('status', 'Approved');
        
        // $mtas = (clone $baseQuery)
        //     ->where('status', 'Approved')   
        //     ->where('payment_status', $filter_payment_status)   
        //     ->orderBy('mta_date', 'DESC')
        //     ->get();
        $mtas = (clone $baseQuery)
            ->when($filter_payment_status, function ($q) use ($filter_payment_status) {
                $q->where('payment_status', $filter_payment_status);
            })
            ->orderBy('mta_date', 'DESC')
            ->get();
        // dd($mtas);
            
        $for_processing =   (clone $baseQuery)->where('payment_status', 'For Processing')->count();
        $processed      =   (clone $baseQuery)->where('payment_status', 'Processed')->count();
        $disapproved    =   (clone $baseQuery)->where('payment_status', 'Disapproved')->count();

        session(['pending_mta_count' => $for_processing]);

        return view('for-approval.mta-process', [
            'header'            => 'for-process',
            'mtas'              => $mtas,
            'for_processing'    => $for_processing,
            'processed'         => $processed,
            'disapproved'       => $disapproved,
            // 'approver_id'       => $approver_id,
            'from'              => $from_date,
            'to'                => $to_date,
            // 'status'            => $filter_status,
            'payment_status'    => $filter_payment_status,
            // 'all_approvers' => $all_approvers,
        ]);
    }

    public function processMtaAll(Request $request)
    {
        $current_user = auth()->user();

        $ids = json_decode($request->ids, true);
        $count = 0;
        $approver_id = $current_user->id;

        if (!empty($ids)) {
            foreach ($ids as $id) {
                $employee_mta = EmployeeMta::find($id);

                if ($employee_mta) {
                    $employee_mta->update([
                        'processing_date' => now(),
                        'payment_status' => 'For Processing',
                        'payment_remarks' => $request->payment_remarks ?? 'Bulk Process',
                        'processing_by' => $approver_id
                    ]);
                    $count++;
                }
            }

            return $count;
        }

        return 'error';
    }

    public function disapprovedProcessedMtaAll(Request $request)
    {
        $current_user = auth()->user();

        $ids = json_decode($request->ids, true);
        $count = 0;
        $approver_id = $current_user->id;

        if (!empty($ids)) {
            foreach ($ids as $id) {
                $employee_mta = EmployeeMta::find($id);

                if ($employee_mta) {
                    $employee_mta->update([
                        'processing_date' => now(),
                        'payment_status' => 'Disapproved',
                        'payment_remarks' => $request->approval_remarks ?? 'Bulk Disapproved',
                        'processing_by' => $approver_id
                    ]);
                    $count++;
                }
            }

            return $count;
        }

        return 'error';
    }  
    
    public function processMta(Request $request, $id)
    {
        $employee_mta = EmployeeMta::find($id);

        if (!$employee_mta) {
            Alert::error('Monetized Transportation Allowance not found.')->persistent('Dismiss');
            return back();
        }

        $current_user = auth()->user();

        $employee_mta->processing_date = now();
        $employee_mta->payment_status = 'For Processing';
        $employee_mta->payment_remarks = $request->payment_remarks;
        $employee_mta->processing_by = $current_user->id;
        $employee_mta->save();

        Alert::success('Monetized Transportation Allowance has been processing.')->persistent('Dismiss');
        return back();
    }

    public function disapprovedMta(Request $request, $id)
    {
        $employee_mta = EmployeeMta::with('user')->find($id);

        if (!$employee_mta) {
            Alert::error('Monetized Transportation Allowance not found.')->persistent('Dismiss');
            return back();
        }

        $current_user = auth()->user();

        $employee_mta->processing_date = now();
        $employee_mta->payment_status = 'Disapproved';
        $employee_mta->payment_remarks = $request->approval_remarks;
        $employee_mta->processing_by = $current_user->id;
        $employee_mta->save();

        // try {
        //     if ($employee_mta->user && $employee_mta->user->email) {
        //         Mail::to($employee_mta->user->email)
        //             ->send(new MtaDeclinedNotification($employee_mta, $current_user));
        //     }
        // } catch (\Exception $e) {
        //         \Log::error('Failed to send MTA decline email: ' . $e->getMessage());
        // }

        Alert::success('Monetized Transportation Allowance Request has been disapproved.')->persistent('Dismiss');
        return back();
    }

    public function processedMtaAll(Request $request)
    {
        $current_user = auth()->user();

        $ids = json_decode($request->ids, true);
        $count = 0;
        $approver_id = $current_user->id;

        if (!empty($ids)) {
            foreach ($ids as $id) {
                $employee_mta = EmployeeMta::find($id);

                if ($employee_mta) {
                    $employee_mta->update([
                        'processing_date' => now(),
                        'payment_status' => 'Processed',
                        'payment_remarks' => $request->payment_remarks ?? 'Bulk Processed',
                        'processing_by' => $approver_id
                    ]);
                    $count++;
                }
            }

            return $count;
        }

        return 'error';
    }

    public function processedMta(Request $request, $id)
    {
        $employee_mta = EmployeeMta::find($id);

        if (!$employee_mta) {
            Alert::error('Monetized Transportation Allowance not found.')->persistent('Dismiss');
            return back();
        }

        $current_user = auth()->user();

        $employee_mta->processing_date = now();
        $employee_mta->payment_status = 'Processed';
        $employee_mta->payment_remarks = $request->payment_remarks;
        $employee_mta->processing_by = $current_user->id;
        $employee_mta->save();

        Alert::success('Monetized Transportation Allowance has been processed.')->persistent('Dismiss');
        return back();
    }

    public function mtaReport(Request $request)
    {
        // $company = isset($request->company) ? $request->company : [];
        $from = isset($request->from) ? $request->from : date('Y-m-d');
        $to = isset($request->to) ? $request->to : date('Y-m-d');
        $status =  isset($request->status) ? $request->status : "Approved";
        $employee_mtas = [];
        // if(isset($request->from) && isset($request->to)){
        //     $employee_mtas = EmployeeMta::with('user','employee')
        //                                 ->whereDate('applied_date','>=',$from)
        //                                 ->whereDate('applied_date','<=',$to)
        //                                 ->whereHas('employee',function($q) use($company){
        //                                     $q->whereIn('company_id',$company);
        //                                 })
        //                                 ->get();

        //     if($status != "ALL")
        //     {
        //         $employee_mtas = $employee_mtas->where('status',$status);
        //     }
        // }
         $employee_mtas = EmployeeMta::with('user','employee')
                                    ->whereDate('mta_date','>=',$from)
                                    ->whereDate('mta_date','<=',$to)
                                    ->get();

        if($status != "ALL")
        {
            $employee_mtas = $employee_mtas->where('status',$status);
        }

        // dd($employee_mtas);
        return view('reports.mta_report', array(
            'header' => 'reports',
            // 'company'=>$company,
            'from'=>$from,
            'to'=>$to,
            'status'=>$status,
            'employee_mtas' => $employee_mtas,
        ));
    }

    public function printSelected(Request $request)
    {
        $ids = explode(',', $request->ids);

        $mtas = EmployeeMta::with(['user.employee.department'])
            ->whereIn('id', $ids)
            ->where('payment_status', 'Processed')
            ->get();

        return view('forms.mta.print_selected', compact('mtas'));
    }

    public function emailSelected(Request $request)
    {
        if (!$request->ids) {
            return response()->json(['message' => 'No IDs received'], 422);
        }

        // ✅ Convert string → array
        $ids = explode(',', $request->ids);

        $mtas = EmployeeMta::with('user')
            ->whereIn('id', $ids)
            ->where('payment_status', 'Processed')
            ->get();

        if ($mtas->isEmpty()) {
            return response()->json(['message' => 'No records found'], 404);
        }

        try {
            Mail::to('it@pascalresources.com.ph')
                ->send(new \App\Mail\MtaSelectedMail($mtas));

            return response()->json([
                'message' => 'Email sent successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
