<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HubPerLocation;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class HubPerLocationController extends Controller
{
    public function index(Request $request)
    {
        $region = $request->get('region', '');
        $territory = $request->get('territory', '');
        $area = $request->get('area', '');
        $hub_status = $request->get('hub_status', '');
        
        // Header variable for the layout
        $header = 'Hub Per Location';
        
        // Get unique regions for filter dropdown
        $regions = HubPerLocation::select('region')
            ->distinct()
            ->whereNotNull('region')
            ->where('region', '!=', '')
            ->orderBy('region')
            ->get();
            
        // Get territories based on selected region
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
            
        // Get areas based on selected territory
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
            
        // Get unique hub statuses for filter dropdown
        $hub_statuses = HubPerLocation::select('hub_status')
            ->distinct()
            ->whereNotNull('hub_status')
            ->where('hub_status', '!=', '')
            ->orderBy('hub_status')
            ->get();

        // Build query for hubs based on filters
        $query = HubPerLocation::select([
            'id', 'region', 'territory', 'area', 'hub_name', 'hub_code', 
            'retail_hub_address', 'hub_status', 'google_map_location_link'
        ]);
        
        // Apply filters
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
        
        // Get the hubs
        $hubs = $query->orderBy('region')->orderBy('territory')->orderBy('area')->orderBy('hub_name')->get();

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
            'hubs'
        ));
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
        ]);

        $hub = HubPerLocation::findOrFail($id);

        $hub->region = $request->region;
        $hub->territory = $request->territory;
        $hub->area = $request->area;
        $hub->hub_name = $request->hub_name;
        $hub->hub_code = $request->hub_code;
        $hub->retail_hub_address = $request->retail_hub_address;
        $hub->hub_status = $request->hub_status;
        $hub->google_map_location_link = $request->google_map_location_link;
        $hub->updated_at = now();

        $hub->save();

        Alert::success('Hub updated successfully')->persistent('Dismiss');
        return back();
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
        ]);

        $id = $request->input('id');

        if ($id) {
            $request->validate([
                'hub_code' => 'required|string|max:50|unique:hub_per_location,hub_code,' . $id,
            ]);

            $hub = HubPerLocation::findOrFail($id);

            $hub->region = $request->region;
            $hub->territory = $request->territory;
            $hub->area = $request->area;
            $hub->hub_name = $request->hub_name;
            $hub->hub_code = $request->hub_code;
            $hub->retail_hub_address = $request->retail_hub_address;
            $hub->hub_status = $request->hub_status;
            $hub->google_map_location_link = $request->google_map_location_link;

            $hub->save();

            $message = 'Hub updated successfully!';
        } else {
            $request->validate([
                'hub_code' => 'required|string|max:50|unique:hub_per_location,hub_code',
            ]);

            $hub = new HubPerLocation;

            $hub->region = $request->region;
            $hub->territory = $request->territory;
            $hub->area = $request->area;
            $hub->hub_name = $request->hub_name;
            $hub->hub_code = $request->hub_code;
            $hub->retail_hub_address = $request->retail_hub_address;
            $hub->hub_status = $request->hub_status;
            $hub->google_map_location_link = $request->google_map_location_link;

            $hub->save();

            $message = 'Hub created successfully!';
        }

        Alert::success($message)->persistent('Dismiss');
        return back();
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
    

}