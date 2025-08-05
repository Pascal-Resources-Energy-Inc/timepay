<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ApprovalByAmount;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ApprovalAmountController extends Controller
{
    /**
     * Display the approval amount dashboard
     */
    public function index()
    {
        // Get Travel Order amounts
        $travel_approval_amounts = DB::table('approval_by_amount')
                                    ->select('higher_than', 'less_than')
                                    ->where('type_of_form', 'Travel Order')
                                    ->get();
        
        $travel_higher_amounts = $travel_approval_amounts->pluck('higher_than')->unique()->filter()->sort()->values();
        $travel_less_amounts = $travel_approval_amounts->pluck('less_than')->unique()->filter()->sort()->values();
        
        // Get Authority to Deduct amounts
        $authority_approval_amounts = DB::table('approval_by_amount')
                                      ->select('higher_than', 'less_than')
                                      ->where('type_of_form', 'Authority to Deduct')
                                      ->get();
        
        $authority_higher_amounts = $authority_approval_amounts->pluck('higher_than')->unique()->filter()->sort()->values();
        $authority_less_amounts = $authority_approval_amounts->pluck('less_than')->unique()->filter()->sort()->values();
        
        return view('approval_amount.amountApproval', array(
            'header' => 'approval_amount',
            'travel_higher_amounts' => $travel_higher_amounts,
            'travel_less_amounts' => $travel_less_amounts,
            'authority_higher_amounts' => $authority_higher_amounts,
            'authority_less_amounts' => $authority_less_amounts,
        ));
    }

    public function updateApprovalAmount(Request $request)
    {
        $request->validate([
            'higher' => 'required|numeric|min:0',
            'less' => 'required|numeric|min:0',
            'admin_email' => 'required|email',
            'admin_password' => 'required|string',
            'form_type' => 'required|in:travel_order,authority_to_deduct',
        ]);

        if ($request->less > $request->higher) {
            return back()->with('error', 'Immediate Supervisor amount must be less than or equal to Head Supervisor amount.');
        }

        // Verify admin credentials
        $adminUser = User::where('email', $request->admin_email)
                        ->where('role', 'admin')
                        ->first();

        if (!$adminUser) {
            return back()->with('error', 'Invalid admin credentials or insufficient permissions.');
        }

        if (!Hash::check($request->admin_password, $adminUser->password)) {
            return back()->with('error', 'Invalid admin credentials or insufficient permissions.');
        }

        try {
            $targetUserId = auth()->user()->id;
            $editorUserId = $adminUser->id; // Use admin user ID as editor

            // Determine the form type
            $formType = $request->form_type === 'travel_order' ? 'Travel Order' : 'Authority to Deduct';

            // Delete existing records for this form type
            ApprovalByAmount::where('created_by', $targetUserId)
                            ->where('type_of_form', $formType)
                            ->delete();

            // Create new record
            $approval_by_amount = new ApprovalByAmount();
            $approval_by_amount->type_of_form = $formType;
            $approval_by_amount->higher_than = $request->higher;
            $approval_by_amount->less_than = $request->less;
            $approval_by_amount->created_by = $targetUserId;
            $approval_by_amount->updated_by = $editorUserId;
            $approval_by_amount->save();

            return back()->with('success', $formType . ' approval amounts updated successfully by admin: ' . $adminUser->name);

        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while updating approval amounts: ' . $e->getMessage());
        }
    }
}