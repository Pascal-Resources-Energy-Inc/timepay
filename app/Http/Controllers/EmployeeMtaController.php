<?php

namespace App\Http\Controllers;
use App\EmployeeMta;
use App\Http\Controllers\EmployeeApproverController;
use App\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class EmployeeMtaController extends Controller
{
    public function index(Request $request)
    {
        $today = date('Y-m-d');
        $from = isset($request->from) ? $request->from : date('Y-m-d',(strtotime ( '-1 month' , strtotime ( $today) ) ));
        $to = isset($request->to) ? $request->to : date('Y-m-d');
        $status = isset($request->status) ? $request->status : 'Pending';

        $get_approvers = new EmployeeApproverController;
        $mtas = EmployeeMta::with('user')
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

    public function create()
    {
        //
    }

    
    public function store(Request $request)
    {
        $new_mta = new EmployeeMta;
        $new_mta->user_id = Auth::user()->id;
        $new_mta->mta_date = $request->mta_date;
        $new_mta->work_location = $request->work_location;
        $new_mta->liters_loaded = $request->liters_loaded;
        $new_mta->petron_price = $request->petron_price;
        $new_mta->mta_amount = $request->mta_amount;
        $new_mta->sales_invoice_number = $request->sales_invoice_number;
        $new_mta->notes = $request->notes;
        
        if($request->file('attachment')){
            $logo = $request->file('attachment');
            $original_name = $logo->getClientOriginalName();
            $name = time() . '_' . $logo->getClientOriginalName();
            $logo->move(public_path() . '/images/', $name);
            $file_name = '/images/' . $name;
            $new_mta->attachment = $file_name;
        }
        
        $new_mta->status = 'Pending';
        $new_mta->level = 0;
        $new_mta->created_by = Auth::user()->id;
        $new_mta->save();
    
        Alert::success('Successfully Stored')->persistent('Dismiss');
        return back();
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
        $edit_mta->user_id = Auth::user()->id;
        $edit_mta->mta_date = $request->mta_date;
        $edit_mta->work_location = $request->work_location;
        $edit_mta->liters_loaded = $request->liters_loaded;
        $edit_mta->petron_price = $request->petron_price;
        $edit_mta->mta_amount = $request->mta_amount;
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
}
