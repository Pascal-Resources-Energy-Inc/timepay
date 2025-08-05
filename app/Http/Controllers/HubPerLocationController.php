<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HubPerLocation;
use Illuminate\Support\Facades\DB;

class HubPerLocationController extends Controller
{
    public function index(Request $request)
    {
        $territory = $request->get('territory', '');
        $area = $request->get('area', '');
        $hub_status = $request->get('hub_status', '');
        
        // Get unique territories for filter dropdown
        $territories = HubPerLocation::select('territory')
            ->distinct()
            ->whereNotNull('territory')
            ->where('territory', '!=', '')
            ->orderBy('territory')
            ->get();
            
        // Get unique areas for filter dropdown
        $areas = HubPerLocation::select('area')
            ->distinct()
            ->whereNotNull('area')
            ->where('area', '!=', '')
            ->orderBy('area')
            ->get();
            
        // Get unique hub statuses for filter dropdown
        $hub_statuses = HubPerLocation::select('hub_status')
            ->distinct()
            ->whereNotNull('hub_status')
            ->where('hub_status', '!=', '')
            ->orderBy('hub_status')
            ->get();

        return view('attendances.hub_per_location', compact(
            'territories', 
            'areas', 
            'hub_statuses',
            'territory',
            'area',
            'hub_status'
        ));
    }
    
    public function getData(Request $request)
    {
        // Select only essential columns for display
        $query = HubPerLocation::select([
            'id', 'territory', 'area', 'hub_name', 'hub_code', 
            'retail_hub_address', 'hub_status', 'google_map_location_link'
        ]);
        
        // Apply filters only if provided
        if ($request->filled('territory')) {
            $query->where('territory', $request->territory);
        }
        
        if ($request->filled('area')) {
            $query->where('area', $request->area);
        }
        
        if ($request->filled('hub_status')) {
            $query->where('hub_status', $request->hub_status);
        }
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('hub_name', 'like', "%{$search}%")
                  ->orWhere('hub_code', 'like', "%{$search}%")
                  ->orWhere('territory', 'like', "%{$search}%")
                  ->orWhere('area', 'like', "%{$search}%");
            });
        }
        
        // Always get all hubs that match criteria (or all if no filters)
        $hubs = $query->orderBy('territory')->orderBy('area')->orderBy('hub_name')->get();
        
        return response()->json([
            'data' => $hubs->map(function($hub) {
                return [
                    'territory' => $hub->territory,
                    'area' => $hub->area,
                    'hub_name' => $hub->hub_name,
                    'hub_code' => $hub->hub_code,
                    'retail_hub_address' => $hub->retail_hub_address,
                    'hub_status' => $hub->hub_status,
                    'google_map_location_link' => $hub->google_map_location_link,
                ];
            })
        ]);
    }
    
    public function export(Request $request)
    {
        // Select only essential columns for export
        $query = HubPerLocation::select([
            'territory', 'area', 'hub_name', 'hub_code', 
            'retail_hub_address', 'hub_status', 'google_map_location_link'
        ]);
        
        // Apply same filters as getData
        if ($request->filled('territory')) {
            $query->where('territory', $request->territory);
        }
        
        if ($request->filled('area')) {
            $query->where('area', $request->area);
        }
        
        if ($request->filled('hub_status')) {
            $query->where('hub_status', $request->hub_status);
        }
        
        $hubs = $query->orderBy('territory')->orderBy('area')->orderBy('hub_name')->get();
        
        $filename = 'hub_per_location_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];
        
        $callback = function() use ($hubs) {
            $file = fopen('php://output', 'w');
            
            // CSV headers - only essential columns
            fputcsv($file, [
                'Territory', 'Area', 'Hub Name', 'Hub Code', 
                'Address', 'Status', 'Google Map Link'
            ]);
            
            foreach ($hubs as $hub) {
                fputcsv($file, [
                    $hub->territory,
                    $hub->area,
                    $hub->hub_name,
                    $hub->hub_code,
                    $hub->retail_hub_address,
                    $hub->hub_status,
                    $hub->google_map_location_link,
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}