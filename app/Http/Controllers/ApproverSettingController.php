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
                                           return $item->user_id . '_' . $item->type_of_form . '_' . $item->work_location; // Group by user_id, type_of_form, and work_location
                                       })
                                       ->map(function($group) {
                                           $first_item = $group->first();
                                           
                                           return (object) [
                                               'id' => $first_item->id,
                                               'user' => $first_item->user,
                                               'work_location' => $first_item->work_location,
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
    
    public function getUserForms($user_id)
    {
        $forms = ApproverSetting::where('user_id', $user_id)
                    ->pluck('type_of_form')
                    ->toArray();

        return response()->json($forms);
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'user_id' => 'required|exists:users,id',
    //         'type_of_forms' => 'required|array|min:1',
    //         'type_of_forms.*' => 'in:leave,ot,dtr,pd,ad,ne,coe,uir,mta',
    //         'work_location' => 'required_if:type_of_forms.*,mta'
    //     ]);

    //     $saved_count = 0;

    //     foreach ($request->type_of_forms ?? [] as $form_type) {

    //         $exists = ApproverSetting::where('user_id', $request->user_id)
    //                     ->where('type_of_form', $form_type)
    //                     ->exists();

    //         if (!$exists) {
    //             ApproverSetting::create([
    //                 'user_id' => $request->user_id,
    //                 'type_of_form' => $form_type,
    //                 'work_location' => $request->work_location ?? null,
    //                 'status' => 'Active'
    //             ]);
    //             $saved_count++;
    //         }
    //     }

    //     return back()->with([
    //         'success' => $saved_count > 0 
    //             ? "Successfully added {$saved_count} approver setting(s)"
    //             : "No new approver settings were added"
    //     ]);
    // }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'type_of_forms' => 'required|array|min:1',
            'type_of_forms.*' => 'in:leave,ot,dtr,pd,ad,ne,coe,uir,mta',
            'work_location' => 'nullable|array'
        ]);

        $saved_count = 0;

        foreach ($request->type_of_forms as $form_type) {

            // ✅ CHECK DUPLICATE
            $exists = ApproverSetting::where('user_id', $request->user_id)
                        ->where('type_of_form', $form_type)
                        ->exists();

            if ($exists) {
                continue;
            }

            // ✅ HANDLE MTA MULTIPLE LOCATION
            if ($form_type === 'mta') {

                if (empty($request->work_location)) {
                    return back()->withErrors([
                        'work_location' => 'Work location is required for MTA'
                    ])->withInput();
                }

                foreach ($request->work_location as $location) {

                    $duplicateLocation = ApproverSetting::where('user_id', $request->user_id)
                        ->where('type_of_form', 'mta')
                        ->where('work_location', $location)
                        ->exists();

                    if (!$duplicateLocation) {
                        ApproverSetting::create([
                            'user_id' => $request->user_id,
                            'type_of_form' => 'mta',
                            'work_location' => $location,
                            'status' => 'Active'
                        ]);
                        $saved_count++;
                    }
                }

            } else {

                // ✅ NON-MTA
                ApproverSetting::create([
                    'user_id' => $request->user_id,
                    'type_of_form' => $form_type,
                    'work_location' => null,
                    'status' => 'Active'
                ]);

                $saved_count++;
            }
        }

        return back()->with([
            'success' => $saved_count > 0
                ? "Successfully added {$saved_count} approver setting(s)"
                : "No new approver settings were added"
        ]);
    }

    // public function removeApprover($id)
    // {
    //     try {
    //         $approver = ApproverSetting::findOrFail($id);
    //         $approver->delete(); 

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Form Approver removed successfully (soft deleted)'
    //         ]);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error removing approver'
    //         ]);
    //     }
    // }

    public function removeApprover($id)
    {
        try {
            $approver = ApproverSetting::findOrFail($id);
            $approver->delete(); 

            return response()->json([
                'success' => true,
                'message' => 'Form Approver removed successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error removing approver'
            ], 500);
        }
    }
}