<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ApproverSetting;
use App\User;
use RealRashid\SweetAlert\Facades\Alert;

class ApproverSettingController extends Controller
{
    public function index()
    {
        $form_approvers = ApproverSetting::with('user')
                                       ->active()
                                       ->get()
                                       ->groupBy(function($item) {
                                           return $item->user_id . '_' . $item->type_of_form;
                                       })
                                       ->map(function($group) {
                                           $first_item = $group->first();
                                           
                                           return (object) [
                                               'id' => $first_item->id,
                                               'user' => $first_item->user,
                                               'form_type_name' => $first_item->form_type_name,
                                               'type_of_form' => $first_item->type_of_form,
                                               'group_ids' => $group->pluck('id')->toArray()
                                           ];
                                       })
                                       ->values();
        
        $users = User::whereHas('employee', function($q) {
                        $q->where('status', 'Active');
                    })
                    ->get();
        
        $form_types = ApproverSetting::getFormTypes();
        
        return view('approver_settings.view_approver_settings', array(
            'header' => 'form_approvers',
            'form_approvers' => $form_approvers,
            'users' => $users,
            'form_types' => $form_types
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'type_of_forms' => 'required|array|min:1',
            'type_of_forms.*' => 'in:leave,ot,dtr,pd,ad,ne,coe'
        ]);

        $saved_count = 0;

        foreach ($request->type_of_forms as $form_type) {
            $existing_approver = ApproverSetting::where('user_id', $request->user_id)
                                                ->where('type_of_form', $form_type)
                                                ->first();

            if (!$existing_approver) {
                ApproverSetting::create([
                    'user_id' => $request->user_id,
                    'type_of_form' => $form_type,
                    'status' => 'Active'
                ]);
                $saved_count++;
            }
        }

        if ($saved_count > 0) {
            Alert::success("Successfully added {$saved_count} approver setting(s)")->persistent('Dismiss');
        } else {
            Alert::warning('No new approver settings were added (all combinations already exist)')->persistent('Dismiss');
        }

        return back();
    }

    public function removeApprover($id)
    {
        try {
            $approver = ApproverSetting::findOrFail($id);
            $approver->delete();
            
            return response()->json(['success' => true, 'message' => 'Form Approver removed successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error removing approver']);
        }
    }
}