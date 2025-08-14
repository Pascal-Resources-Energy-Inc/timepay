<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\HubPerLocation;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Log;

class HubPerLocationController extends Controller
{
    public function index(Request $request)
    {
        $region = $request->get('region', '');
        $territory = $request->get('territory', '');
        $area = $request->get('area', '');
        $hub_status = $request->get('hub_status', '');
        
        $header = 'Hub Per Location';
        
        $regions = HubPerLocation::select('region')
            ->distinct()
            ->whereNotNull('region')
            ->where('region', '!=', '')
            ->orderBy('region')
            ->get();
            
        $territories = collect();
        if ($region) {
            $territories = HubPerLocation::select('territory')
                ->distinct()
                ->where('region', $region)
                ->whereNotNull('territory')
                ->where('territory', '!=', '')
                ->orderBy('territory')
                ->get();
        }
            
        $areas = collect();
        if ($territory) {
            $areas = HubPerLocation::select('area')
                ->distinct()
                ->where('territory', $territory)
                ->whereNotNull('area')
                ->where('area', '!=', '')
                ->orderBy('area')
                ->get();
        }
            
        $hub_statuses = HubPerLocation::select('hub_status')
            ->distinct()
            ->whereNotNull('hub_status')
            ->where('hub_status', '!=', '')
            ->orderBy('hub_status')
            ->get();

        $query = HubPerLocation::select([
            'id', 'region', 'territory', 'area', 'hub_name', 'hub_code', 
            'retail_hub_address', 'hub_status', 'google_map_location_link',
            'lat', 'long'
        ]);
        
        if ($region) {
            $query->where('region', $region);
        }
        
        if ($territory) {
            $query->where('territory', $territory);
        }
        
        if ($area) {
            $query->where('area', $area);
        }
        
        if ($hub_status) {
            $query->where('hub_status', $hub_status);
        }
        
        $hubs = $query->orderBy('region')->orderBy('territory')->orderBy('area')->orderBy('hub_name')->get();

        $users = User::where('login', 1)->with('employee')->get();

        foreach ($hubs as $hub) {
            $hub->hubAssignments = $this->getHubAssignmentsByHubId($hub->id);
        }

        return view('hubs.hub_per_location', compact(
            'header',
            'regions',
            'territories', 
            'areas', 
            'hub_statuses',
            'region',
            'territory',
            'area',
            'hub_status',
            'hubs',
            'users'
        ));
    }

    private function getHubAssignmentsByHubId($hubId)
    {
        return DB::table('hub_per_location_id as hpl')
            ->join('hub_per_location as h', 'hpl.hub_per_location_id', '=', 'h.id')
            ->join('users as u', 'hpl.user_id', '=', 'u.id')
            ->join('employees as e', 'u.id', '=', 'e.user_id')
            ->select(
                'h.hub_name',
                'e.employee_number',
                'u.name as employee_name',
                'hpl.user_id',
                'hpl.hub_per_location_id'
            )
            ->where('hpl.hub_per_location_id', $hubId)
            ->get();
    }

    public function getHubAssignments()
    {
        $hubAssignments = DB::table('hub_per_location_id as hpl')
            ->join('hub_per_location as hub', 'hpl.hub_per_location_id', '=', 'hub.id')
            ->join('users as u', 'hpl.user_id', '=', 'u.id')
            ->join('employees as emp', 'hpl.user_id', '=', 'emp.user_id')
            ->select(
                'hub.hub_name',
                'emp.employee_number',
                'u.name as employee_name',
                'hpl.created_at as assigned_date',
                'u.id as user_id'
            )
            ->orderBy('hub.hub_name')
            ->orderBy('emp.employee_number')
            ->get();

        return $hubAssignments;
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'region' => 'required|string|max:255',
            'territory' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'hub_name' => 'required|string|max:255',
            'hub_code' => 'required|string|max:50',
            'retail_hub_address' => 'required|string',
            'hub_status' => 'required|string|max:50',
            'google_map_location_link' => 'nullable|url',
            'lat' => 'nullable|numeric|between:-90,90',
            'long' => 'nullable|numeric|between:-180,180',
        ]);

        $id = $request->input('id');

        if ($id) {
            $request->validate([
                'hub_code' => 'required|string|max:50|unique:hub_per_location,hub_code,' . $id,
            ]);

            $hub = HubPerLocation::findOrFail($id);
            $action = 'updated';
        } else {
            $request->validate([
                'hub_code' => 'required|string|max:50|unique:hub_per_location,hub_code',
            ]);

            $hub = new HubPerLocation;
            $action = 'created';
        }

        $hub->region = $request->region;
        $hub->territory = $request->territory;
        $hub->area = $request->area;
        $hub->hub_name = $request->hub_name;
        $hub->hub_code = $request->hub_code;
        $hub->retail_hub_address = $request->retail_hub_address;
        $hub->hub_status = $request->hub_status;
        $hub->google_map_location_link = $request->google_map_location_link;
        $hub->lat = $request->lat;
        $hub->long = $request->long;
        
        if (!$id) {
            $hub->created_at = now();
        }
        $hub->updated_at = now();

        $hub->save();

        $message = $action === 'created' ? 'Hub created successfully!' : 'Hub updated successfully!';
        Alert::success($message)->persistent('Dismiss');
        return back();
    }
    
    
    public function createUserForHub(Request $request)
    {
        try {
            $request->validate([
                'hub_id' => 'required|exists:hub_per_location,id',
                'employee' => 'required|array|min:1',
                'employee.*' => 'exists:users,id' 
            ]);

            $hubId = $request->hub_id;
            $employeeIds = $request->employee; // This is now an array

            $successCount = 0;
            $errors = [];

            foreach ($employeeIds as $userId) {
                $user = User::with('employee')->find($userId);
                
                if (!$user || !$user->employee || !$user->employee->employee_number) {
                    $errors[] = "Employee with ID {$userId} does not have a valid employee number";
                    continue;
                }

                // Check if this user is already assigned to this hub
                $existingAssignment = DB::table('hub_per_location_id')
                    ->where('hub_per_location_id', $hubId)
                    ->where('user_id', $userId)
                    ->exists();

                if ($existingAssignment) {
                    $errors[] = "Employee {$user->name} is already assigned to this hub";
                    continue;
                }

                // Check if this user is already assigned to ANY hub
                $existingGlobalAssignment = DB::table('hub_per_location_id')
                    ->where('user_id', $userId)
                    ->exists();

                if ($existingGlobalAssignment) {
                    $errors[] = "Employee {$user->name} is already assigned to another hub";
                    continue;
                }

                // Get next ID and insert
                $nextId = (DB::table('hub_per_location_id')->max('id') ?? 0) + 1;

                DB::table('hub_per_location_id')->insert([
                    'id' => $nextId,
                    'hub_per_location_id' => $hubId,
                    'user_id' => $userId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $successCount++;
            }

            // Show appropriate messages
            if ($successCount > 0 && empty($errors)) {
                Alert::success("Successfully assigned {$successCount} employee(s) to hub!")->persistent('Dismiss');
            } elseif ($successCount > 0 && !empty($errors)) {
                $errorMessage = implode('. ', $errors);
                Alert::warning("Assigned {$successCount} employee(s) successfully, but encountered issues: {$errorMessage}")->persistent('Dismiss');
            } else {
                $errorMessage = implode('. ', $errors);
                Alert::error("No employees were assigned. Issues: {$errorMessage}")->persistent('Dismiss');
            }

            return back();

        } catch (\Illuminate\Validation\ValidationException $e) {
            Alert::error('Validation failed: ' . implode(', ', $e->validator->errors()->all()))->persistent('Dismiss');
            return back();
        } catch (\Exception $e) {
            Log::error('Error creating user for hub', [
                'hub_id' => $request->hub_id ?? null,
                'employee' => $request->employee ?? null,
                'error' => $e->getMessage()
            ]);

            Alert::error('An error occurred while assigning user to hub. Please try again.')->persistent('Dismiss');
            return back();
        }
    }

    public function removeUserFromHubById(Request $request)
{
    try {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'hub_id' => 'required|integer|exists:hub_per_location,id'
        ]);

        $userId = $request->user_id;
        $hubId = $request->hub_id;

        // Get user and hub info for the success message
        $userInfo = DB::table('users as u')
            ->join('employees as e', 'u.id', '=', 'e.user_id')
            ->select('u.name as employee_name', 'e.employee_number')
            ->where('u.id', $userId)
            ->first();

        $hubInfo = DB::table('hub_per_location')
            ->select('hub_name')
            ->where('id', $hubId)
            ->first();

        if (!$userInfo || !$hubInfo) {
            Alert::error('User or Hub not found!')->persistent('Dismiss');
            return back();
        }

        // Check if assignment exists
        $assignmentExists = DB::table('hub_per_location_id')
            ->where('user_id', $userId)
            ->where('hub_per_location_id', $hubId)
            ->exists();

        if (!$assignmentExists) {
            Alert::error('Assignment does not exist!')->persistent('Dismiss');
            return back();
        }

        // Delete the assignment
        $deleted = DB::table('hub_per_location_id')
            ->where('user_id', $userId)
            ->where('hub_per_location_id', $hubId)
            ->delete();

        if ($deleted) {
            Alert::success("Employee {$userInfo->employee_name} ({$userInfo->employee_number}) has been successfully removed from {$hubInfo->hub_name}!")->persistent('Dismiss');
        } else {
            Alert::error('Failed to remove assignment. Please try again.')->persistent('Dismiss');
        }

        return back();

    } catch (\Illuminate\Validation\ValidationException $e) {
        Alert::error('Validation failed: ' . implode(', ', $e->validator->errors()->all()))->persistent('Dismiss');
        return back();
    } catch (\Exception $e) {
        Log::error('Error removing user from hub by ID', [
            'user_id' => $request->user_id ?? null,
            'hub_id' => $request->hub_id ?? null,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        Alert::error('An error occurred while removing the assignment. Please try again.')->persistent('Dismiss');
        return back();
    }
}
    
    public function getTerritoriesByRegion(Request $request)
    {
        $region = $request->get('region');
        
        $territories = HubPerLocation::select('territory')
            ->distinct()
            ->where('region', $region)
            ->whereNotNull('territory')
            ->where('territory', '!=', '')
            ->orderBy('territory')
            ->get();
            
        return response()->json($territories);
    }
    
    public function getAreasByTerritory(Request $request)
    {
        $territory = $request->get('territory');
        
        $areas = HubPerLocation::select('area')
            ->distinct()
            ->where('territory', $territory)
            ->whereNotNull('area')
            ->where('area', '!=', '')
            ->orderBy('area')
            ->get();
            
        return response()->json($areas);
    }

    public function edit(Request $request, $id)
{
    $request->validate([
        'region' => 'required|string|max:255',
        'territory' => 'required|string|max:255',
        'area' => 'required|string|max:255',
        'hub_name' => 'required|string|max:255',
        'hub_code' => 'required|string|max:50|unique:hub_per_location,hub_code,' . $id,
        'retail_hub_address' => 'required|string',
        'hub_status' => 'required|string|max:50',
        'google_map_location_link' => 'nullable|url',
        'lat' => 'nullable|numeric|between:-90,90',
        'long' => 'nullable|numeric|between:-180,180',
    ]);

    try {
        $hub = HubPerLocation::findOrFail($id);

        $hub->region = $request->region;
        $hub->territory = $request->territory;
        $hub->area = $request->area;
        $hub->hub_name = $request->hub_name;
        $hub->hub_code = $request->hub_code;
        $hub->retail_hub_address = $request->retail_hub_address;
        $hub->hub_status = $request->hub_status;
        $hub->google_map_location_link = $request->google_map_location_link;
        $hub->lat = $request->lat;
        $hub->long = $request->long;
        $hub->updated_at = now();

        $hub->save();

        Alert::success('Hub updated successfully!')->persistent('Dismiss');
        return back();
    } catch (\Exception $e) {
        Log::error('Error updating hub', [
            'hub_id' => $id,
            'error' => $e->getMessage()
        ]);

        Alert::error('An error occurred while updating the hub. Please try again.')->persistent('Dismiss');
        return back();
    }
}

}