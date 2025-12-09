<?php 

namespace App\Http\Controllers;

use App\Tds;
use App\Region;
use App\SalesTarget;
use App\TdsActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class TDSController extends Controller
{
    public function index(Request $request)
    {
        $query = Tds::with(['user', 'region']);

        if ($request->from && $request->to) {
            $query->whereBetween('date_of_registration', [$request->from, $request->to]);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->package) {
            $query->where('package_type', $request->package);
        }

        $tdsRecords = $query->latest()->get();

        $currentMonth = Carbon::now()->format('Y-m');
        $targetRecord = SalesTarget::where('month', $currentMonth)->first();
        $monthlyTarget = $targetRecord ? $targetRecord->target_amount : 0;

        $actualSales = Tds::whereYear('date_of_registration', Carbon::now()->year)
            ->whereMonth('date_of_registration', Carbon::now()->month)
            ->sum('purchase_amount');

        $stats = [
            'monthly_target' => $monthlyTarget,
            'actual_sales' => $actualSales,
            'for_delivery' => Tds::where('status', 'For Delivery')->count(),
            'gap_to_goal' => max(0, $monthlyTarget - $actualSales),
            'achievement_percentage' => $monthlyTarget > 0 ? round(($actualSales / $monthlyTarget) * 100, 2) : 0,
        ];

        $regions = Region::orderBy('region_name')->get();

        return view('forms.tds.index', compact('tdsRecords', 'stats', 'regions'))
            ->with('header', 'forms');
    }

    public function show($id)
    {
        $tds = Tds::with(['user', 'region'])->findOrFail($id);
        return view('forms.tds.view_details', compact('tds'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date_registered' => 'required|date',
            'employee_name' => 'required|string|max:255',
            'area' => 'required|integer|exists:regions,id',
            'customer_name' => 'required|string|max:255',
            'contact_no' => 'required|string|regex:/^[0-9]{4}-[0-9]{3}-[0-9]{4}$/',
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|string|max:255',
            'awarded_area' => 'nullable|string|max:255',
            'package_type' => 'required|in:EU,D,MD,AD',
            'purchase_amount' => 'required|numeric|min:0',
            'lead_generator' => 'required|string|max:255',
            'supplier_name' => 'required|string|max:255',
            'status' => 'required|in:For Delivery,Delivered',
            'timeline' => 'nullable|date',
            'additional_notes' => 'nullable|string',
        ], [
            'contact_no.regex' => 'Contact number must be in format: 0912-345-6789',
            'package_type.in' => 'Package type must be EU, D, MD, or AD',
            'status.in' => 'Status must be either For Delivery or Delivered',
            'area.exists' => 'Selected area is invalid',
        ]);

        DB::beginTransaction();
        try {
            $tds = Tds::create([
                'date_of_registration' => $request->date_registered,
                'user_id' => auth()->id(),
                'area' => $request->area,
                'customer_name' => $request->customer_name,
                'contact_no' => $request->contact_no,
                'business_name' => $request->business_name,
                'awarded_area' => $request->awarded_area,
                'business_type' => $request->business_type,
                'package_type' => $request->package_type,
                'purchase_amount' => $request->purchase_amount,
                'lead_generator' => $request->lead_generator,
                'supplier_name' => $request->supplier_name,
                'status' => $request->status,
                'timeline' => $request->timeline,
                'additional_notes' => $request->additional_notes,
            ]);

            $tds->logActivity('created', [
                'customer_name' => $request->customer_name,
                'business_name' => $request->business_name,
                'package_type' => $request->package_type,
                'purchase_amount' => $request->purchase_amount,
                'status' => $request->status,
            ]);

            DB::commit();

            return redirect()->route('tds.index')
                ->with('success', 'Dealer registered successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to register dealer: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'date_registered' => 'required|date',
            'area' => 'required|integer|exists:regions,id',
            'customer_name' => 'required|string|max:255',
            'contact_no' => 'required|string|regex:/^[0-9]{4}-[0-9]{3}-[0-9]{4}$/',
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|string|max:255',
            'awarded_area' => 'nullable|string|max:255',
            'package_type' => 'required|in:EU,D,MD,AD',
            'purchase_amount' => 'required|numeric|min:0',
            'lead_generator' => 'required|string|max:255',
            'supplier_name' => 'required|string|max:255',
            'status' => 'required|in:For Delivery,Delivered',
            'timeline' => 'nullable|date',
            'additional_notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $tds = Tds::findOrFail($id);
            $oldData = $tds->getOriginal();

            $tds->update([
                'date_of_registration' => $request->date_registered,
                'area' => $request->area,
                'customer_name' => $request->customer_name,
                'contact_no' => $request->contact_no,
                'business_name' => $request->business_name,
                'awarded_area' => $request->awarded_area,
                'business_type' => $request->business_type,
                'package_type' => $request->package_type,
                'purchase_amount' => $request->purchase_amount,
                'lead_generator' => $request->lead_generator,
                'supplier_name' => $request->supplier_name,
                'status' => $request->status,
                'timeline' => $request->timeline,
                'additional_notes' => $request->additional_notes,
            ]);

            $changes = [];
            if ($oldData['status'] != $request->status) {
                $changes['status'] = ['from' => $oldData['status'], 'to' => $request->status];
            }
            if ($oldData['purchase_amount'] != $request->purchase_amount) {
                $changes['purchase_amount'] = ['from' => $oldData['purchase_amount'], 'to' => $request->purchase_amount];
            }
            if ($oldData['package_type'] != $request->package_type) {
                $changes['package_type'] = ['from' => $oldData['package_type'], 'to' => $request->package_type];
            }

            $tds->logActivity('updated', [
                'customer_name' => $request->customer_name,
                'changes' => $changes
            ]);

            DB::commit();

            return redirect()->route('tds.index')
                ->with('success', 'Dealer information updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update dealer: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $tds = Tds::findOrFail($id);

            $tds->logActivity('deleted', [
                'customer_name' => $tds->customer_name,
                'business_name' => $tds->business_name,
                'purchase_amount' => $tds->purchase_amount,
            ]);

            $tds->delete();

            DB::commit();

            return redirect()->route('tds.index')
                ->with('success', 'Dealer record deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('tds.index')
                ->with('error', 'Failed to delete dealer: ' . $e->getMessage());
        }
    }

    public function updateSalesTarget(Request $request)
    {
        if (auth()->user()->role !== 'Admin' && !auth()->user()->is_admin) {
            return redirect()->back()
                ->with('error', 'Unauthorized access. Only admins can set sales targets.');
        }

        $validated = $request->validate([
            'month' => 'required|date_format:Y-m',
            'target_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $salesTarget = SalesTarget::updateOrCreate(
                ['month' => $request->month],
                [
                    'target_amount' => $request->target_amount,
                    'notes' => $request->notes,
                ]
            );

            $action = $salesTarget->wasRecentlyCreated ? 'created' : 'updated';
            
            $salesTarget->logActivity($action, [
                'month' => $request->month,
                'target_amount' => $request->target_amount,
                'notes' => $request->notes,
            ]);

            DB::commit();

            $message = $action === 'created' 
                ? 'Sales target set successfully!' 
                : 'Sales target updated successfully!';

            return redirect()->route('tds.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to set sales target: ' . $e->getMessage());
        }
    }

    public function getActivityLogs(Request $request)
    {
        $tdsId = $request->input('tds_id');
        $limit = $request->input('limit', 50);

        try {
            $logs = TdsActivityLog::with('user')
                ->where('tds_id', $tdsId)
                ->latest()
                ->limit($limit)
                ->get()
                ->map(function($log) {
                    return [
                        'id' => $log->id,
                        'action' => $log->action,
                        'record_type' => $log->record_type,
                        'record_identifier' => $log->record_identifier,
                        'details' => $log->details,
                        'user_name' => $log->user ? $log->user->name : 'System',
                        'created_at' => $log->created_at,
                    ];
                });

            return response()->json([
                'success' => true,
                'logs' => $logs,
                'count' => $logs->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading activity logs: ' . $e->getMessage()
            ], 500);
        }
    }

    public function export(Request $request)
    {
        $query = Tds::with(['user', 'region']);

        if ($request->from && $request->to) {
            $query->whereBetween('date_of_registration', [$request->from, $request->to]);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->package) {
            $query->where('package_type', $request->package);
        }

        $records = $query->latest()->get();

        $filename = 'sales_performance_' . date('Y-m-d_His') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        fputcsv($output, [
            'Date Registered',
            'Employee Name',
            'Area',
            'Customer Name',
            'Contact No',
            'Business Name',
            'Awarded Area',
            'Business Type',
            'Package Type',
            'Purchase Amount',
            'Lead Generator',
            'Supplier Name',
            'Status',
            'Timeline',
            'Additional Notes'
        ]);

        foreach ($records as $record) {
            fputcsv($output, [
                $record->date_of_registration,
                $record->user ? $record->user->name : 'N/A',
                $record->region ? $record->region->region_name : 'N/A',
                $record->customer_name,
                $record->contact_no ?? 'N/A',
                $record->business_name,
                $record->awarded_area ?? 'N/A',
                $record->business_type,
                $record->package_type,
                $record->purchase_amount,
                $record->lead_generator,
                $record->supplier_name,
                $record->status,
                $record->timeline ?? 'N/A',
                $record->additional_notes ?? 'N/A'
            ]);
        }

        fclose($output);
        exit;
    }
}