<?php 

namespace App\Http\Controllers;

use App\Tds;
use App\Region;
use App\SalesTarget;
use App\TdsActivityLog;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class TDSController extends Controller
{
    private function getRegionDisplayName($region)
    {
        if (!$region) return 'N/A';
        
        $display = $region->region . ' - ' . $region->province;
        if ($region->district) {
            $display .= ' - ' . $region->district;
        }
        return $display;
    }

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

        if ($request->program) {
            $query->where('program_type', $request->program);
        }

        $tdsRecords = $query->latest()->get();

        $currentMonth = Carbon::now()->format('Y-m');
        
        $activeTdsUserIds = Tds::whereYear('date_of_registration', Carbon::now()->year)
            ->whereMonth('date_of_registration', Carbon::now()->month)
            ->pluck('user_id')
            ->unique();
        
        $totalMonthlyTarget = SalesTarget::where('month', $currentMonth)
            ->whereIn('user_id', $activeTdsUserIds)
            ->sum('target_amount');

        // CHANGED: Only count "Delivered" status in actual sales
        $actualSales = Tds::whereYear('date_of_registration', Carbon::now()->year)
            ->whereMonth('date_of_registration', Carbon::now()->month)
            ->where('status', 'Delivered')
            ->sum('purchase_amount');

        $stats = [
            'monthly_target' => $totalMonthlyTarget,
            'actual_sales' => $actualSales,
            'for_delivery' => Tds::where('status', 'For Delivery')->count(),
            'gap_to_goal' => max(0, $totalMonthlyTarget - $actualSales),
            'achievement_percentage' => $totalMonthlyTarget > 0 ? round(($actualSales / $totalMonthlyTarget) * 100, 2) : 0,
        ];

        $regions = Region::orderBy('region')
            ->orderBy('province')
            ->get();

        return view('forms.tds.index', compact('tdsRecords', 'stats', 'regions'))
            ->with('header', 'tds');
    }

    public function show($id)
    {
        $tds = Tds::with(['user', 'region'])->findOrFail($id);
        return view('forms.tds.view_details', compact('tds'));
    }

    public function dashboard(Request $request)
    {
        $selectedYear = $request->input('year', date('Y'));
        $selectedRegion = $request->input('region', null);
        $selectedEmployee = $request->input('employee', null);
        
        $regions = Region::orderBy('region')
            ->orderBy('province')
            ->get();
        
        $query = Tds::with(['user', 'region'])
            ->whereYear('date_of_registration', $selectedYear);
        
        if ($selectedRegion) {
            $query->where('area', $selectedRegion);
        }
        
        if ($selectedEmployee) {
            $query->where('user_id', $selectedEmployee);
        }
        
        $records = $query->get();
        
        $userIds = $records->pluck('user_id')->unique();
        $totalTarget = 0;
        
        foreach ($userIds as $userId) {
            $employeeYearlyTarget = SalesTarget::where('user_id', $userId)
                ->whereYear('month', $selectedYear)
                ->sum('target_amount');
            
            $totalTarget += $employeeYearlyTarget;
        }
        
        $totalActual = $records->sum('purchase_amount');
        
        $achievementRate = $totalTarget > 0 ? ($totalActual / $totalTarget) * 100 : 0;
        $activeTds = $records->groupBy('user_id')->count();
        
        $overview = [
            'total_target' => $totalTarget,
            'total_actual' => $totalActual,
            'achievement_rate' => $achievementRate,
            'active_tds' => $activeTds,
        ];
        
        $regionData = [];
        $regionsToProcess = $selectedRegion 
            ? Region::where('id', $selectedRegion)->get() 
            : $regions;
        
        foreach ($regionsToProcess as $region) {
            $regionRecords = $records->where('area', $region->id);
            
            if ($selectedEmployee && $regionRecords->isEmpty()) {
                continue;
            }
            
            $regionKey = $this->getRegionDisplayName($region);
            $regionData[$regionKey] = $this->prepareRegionKPI($region, $regionRecords, $selectedYear, $selectedEmployee);
        }
        
        $chartData = $this->prepareChartData($records, $selectedYear, $userIds);

        return view(
            'forms.tds.dashboard',
            array(
                'header' => 'tdsDashboard',
                'overview' => $overview,
                'regionData' => $regionData,
                'regions' => $regions,
                'selectedYear' => $selectedYear,
                'selectedRegion' => $selectedRegion,
                'selectedEmployee' => $selectedEmployee,
                'chartData' => $chartData
            )
        );
    }

    public function getEmployees(Request $request)
    {
        $search = $request->input('search', '');
        $selectedYear = $request->input('year', date('Y'));
        $selectedRegion = $request->input('region', null);
        
        $query = DB::table('users')
            ->join('tds', 'users.id', '=', 'tds.user_id')
            ->leftJoin('employees', 'users.id', '=', 'employees.user_id')
            ->whereYear('tds.date_of_registration', $selectedYear)
            ->select('users.id', 'users.name', 'employees.employee_number')
            ->distinct();
        
        if ($selectedRegion) {
            $query->where('tds.area', $selectedRegion);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('users.name', 'LIKE', "%{$search}%")
                ->orWhere('employees.employee_number', 'LIKE', "%{$search}%");
            });
        }
        
        $employees = $query->orderBy('users.name')
            ->limit(20)
            ->get();
        
        $results = $employees->map(function($employee) {
            $displayText = $employee->name;
            if ($employee->employee_number) {
                $displayText = $employee->employee_number . ' - ' . $employee->name;
            }
            return [
                'id' => $employee->id,
                'text' => $displayText
            ];
        });
        
        return response()->json([
            'results' => $results
        ]);
    }

    public function getAllUsers(Request $request)
    {
        try {
            $search = $request->input('search', '');
            $page = $request->input('page', 1);
            $perPage = 20;
            
            $query = DB::table('users')
                ->leftJoin('employees', 'users.id', '=', 'employees.user_id')
                ->select('users.id', 'users.name', 'employees.employee_number')
                ->orderBy('users.name');
            
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('users.name', 'LIKE', "%{$search}%")
                    ->orWhere('employees.employee_number', 'LIKE', "%{$search}%");
                });
            }
            
            $total = $query->count();
            
            $users = $query->skip(($page - 1) * $perPage)
                        ->take($perPage)
                        ->get();
            
            $results = $users->map(function($user) {
                $displayText = $user->name;
                if ($user->employee_number) {
                    $displayText = $user->employee_number . ' - ' . $user->name;
                }
                return [
                    'id' => $user->id,
                    'text' => $displayText
                ];
            });
            
            return response()->json([
                'results' => $results,
                'total' => $total,
                'pagination' => [
                    'more' => ($page * $perPage) < $total
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('getAllUsers error: ' . $e->getMessage());
            
            return response()->json([
                'results' => [],
                'total' => 0,
                'error' => 'Failed to load users',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function prepareRegionKPI($region, $records, $year, $selectedEmployee = null)
    {
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $monthNumbers = [
            'Jan' => 1, 'Feb' => 2, 'Mar' => 3, 'Apr' => 4, 'May' => 5, 'Jun' => 6,
            'Jul' => 7, 'Aug' => 8, 'Sep' => 9, 'Oct' => 10, 'Nov' => 11, 'Dec' => 12
        ];
        
        $summary = [
            'monthly' => array_fill_keys($months, 0),
            'actual' => 0,
            'target' => 0,
            'achievement' => 0,
            'ad_monthly' => array_fill_keys($months, 0),
            'ad_actual' => 0,
            'ad_target' => 0,
            'dealer_monthly' => array_fill_keys($months, 0),
            'dealer_actual' => 0,
            'dealer_target' => 0,
        ];
        
        $employees = [];
        $userIds = $records->pluck('user_id')->unique();
        
        foreach ($userIds as $userId) {
            $user = \App\User::find($userId);
            if (!$user) continue;
            
            $userRecords = $records->where('user_id', $userId);
            
            $employeeYearlyTarget = SalesTarget::where('user_id', $userId)
                ->whereYear('month', $year)
                ->sum('target_amount');
            
            $employeeYearlyTarget = $employeeYearlyTarget ?: 0;
            
            $employeeData = [
                'name' => $user->name,
                'monthly' => array_fill_keys($months, 0),
                'actual' => 0,
                'target' => $employeeYearlyTarget,
                'achievement' => 0,
                'ad_monthly' => array_fill_keys($months, 0),
                'ad_actual' => 0,
                'ad_target' => 1,
                'dealer_monthly' => array_fill_keys($months, 0),
                'dealer_actual' => 0,
                'dealer_target' => 40,
            ];
            
            foreach ($months as $month) {
                $monthNum = $monthNumbers[$month];
                $monthRecords = $userRecords->filter(function($record) use ($year, $monthNum) {
                    $date = \Carbon\Carbon::parse($record->date_of_registration);
                    return $date->year == $year && $date->month == $monthNum;
                });
                
                $monthAmount = $monthRecords->sum('purchase_amount');
                $employeeData['monthly'][$month] = $monthAmount;
                $employeeData['actual'] += $monthAmount;
                
                $adCount = $monthRecords->whereIn('package_type', ['AD', 'MD'])->count();
                $dealerCount = $monthRecords->whereIn('package_type', ['D', 'EU'])->count();
                
                $employeeData['ad_monthly'][$month] = $adCount;
                $employeeData['ad_actual'] += $adCount;
                
                $employeeData['dealer_monthly'][$month] = $dealerCount;
                $employeeData['dealer_actual'] += $dealerCount;
                
                $summary['monthly'][$month] += $monthAmount;
                $summary['ad_monthly'][$month] += $adCount;
                $summary['dealer_monthly'][$month] += $dealerCount;
            }
            
            $employeeData['achievement'] = $employeeData['target'] > 0 
                ? ($employeeData['actual'] / $employeeData['target']) * 100 
                : 0;
            
            $employees[] = $employeeData;
            
            $summary['actual'] += $employeeData['actual'];
            $summary['target'] += $employeeData['target'];
            $summary['ad_actual'] += $employeeData['ad_actual'];
            $summary['ad_target'] += $employeeData['ad_target'];
            $summary['dealer_actual'] += $employeeData['dealer_actual'];
            $summary['dealer_target'] += $employeeData['dealer_target'];
        }
        
        $summary['achievement'] = $summary['target'] > 0 
            ? ($summary['actual'] / $summary['target']) * 100 
            : 0;
        
        return [
            'summary' => $summary,
            'employees' => $employees,
            'has_vacant' => false,
            'vacant_target' => 0,
        ];
    }

    public function getEmployeeTarget(Request $request)
    {
        $userId = $request->input('user_id');
        $month = $request->input('month');
        
        $target = SalesTarget::where('user_id', $userId)
            ->where('month', $month)
            ->first();
        
        return response()->json([
            'target_amount' => $target ? $target->target_amount : 200000,
            'notes' => $target ? $target->notes : '',
        ]);
    }

    private function prepareChartData($records, $year, $userIds = null)
    {
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $monthNumbers = [
            'Jan' => 1, 'Feb' => 2, 'Mar' => 3, 'Apr' => 4, 'May' => 5, 'Jun' => 6,
            'Jul' => 7, 'Aug' => 8, 'Sep' => 9, 'Oct' => 10, 'Nov' => 11, 'Dec' => 12
        ];
        
        $monthly = array_fill_keys($months, 0);
        $monthlyTargets = array_fill_keys($months, 0);
        
        foreach ($months as $month) {
            $monthNum = $monthNumbers[$month];
            $monthRecords = $records->filter(function($record) use ($year, $monthNum) {
                $date = \Carbon\Carbon::parse($record->date_of_registration);
                return $date->year == $year && $date->month == $monthNum;
            });
            
            $monthly[$month] = $monthRecords->sum('purchase_amount');
            
            $monthString = $year . '-' . str_pad($monthNum, 2, '0', STR_PAD_LEFT);
            
            if ($userIds) {
                $monthlyTargets[$month] = SalesTarget::where('month', $monthString)
                    ->whereIn('user_id', $userIds)
                    ->sum('target_amount');
            } else {
                $monthlyTargets[$month] = SalesTarget::where('month', $monthString)
                    ->sum('target_amount');
            }
        }
        
        $packages = [
            'EU - End User' => $records->where('package_type', 'EU')->count(),
            'D - Dealer' => $records->where('package_type', 'D')->count(),
            'MD - Mega Dealer' => $records->where('package_type', 'MD')->count(),
            'AD - Area Distributor' => $records->where('package_type', 'AD')->count(),
        ];
        
        return [
            'monthly' => $monthly,
            'monthly_targets' => $monthlyTargets,
            'packages' => $packages,
        ];
    }

    public function dashboardExport(Request $request)
    {
        $selectedYear = $request->input('year', date('Y'));
        $selectedRegion = $request->input('region', null);
        $selectedEmployee = $request->input('employee', null); 
        
        $query = Tds::with(['user', 'region'])
            ->whereYear('date_of_registration', $selectedYear);
        
        if ($selectedRegion) {
            $query->where('area', $selectedRegion);
        }
        
        if ($selectedEmployee) {
            $query->where('user_id', $selectedEmployee);
        }
        
        $records = $query->get();
        $regions = $selectedRegion 
            ? Region::where('id', $selectedRegion)->get() 
            : Region::orderBy('region')->orderBy('province')->get();
        
        $filename = 'tds_kpi_dashboard_' . $selectedYear;
        if ($selectedEmployee) {
            $user = \App\User::find($selectedEmployee);
            $filename .= '_' . str_replace(' ', '_', $user->name ?? 'employee');
        }
        $filename .= '_' . date('Y-m-d_His') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        foreach ($regions as $region) {
            $regionRecords = $records->where('area', $region->id);
            
            fputcsv($output, [$selectedYear . ' KPI for Trade Development Specialists']);
            
            $regionTitle = $this->getRegionDisplayName($region);
            fputcsv($output, [$regionTitle]);
            fputcsv($output, []);
            
            fputcsv($output, [
                'Employee Name', 'Metric',
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',
                'Actual', 'Target', 'A/R'
            ]);
            
            $userIds = $regionRecords->pluck('user_id')->unique();
            
            foreach ($userIds as $userId) {
                $user = \App\User::find($userId);
                if (!$user) continue;
                
                $userRecords = $regionRecords->where('user_id', $userId);
                
                $employeeYearlyTarget = SalesTarget::where('user_id', $userId)
                    ->whereYear('month', $selectedYear)
                    ->sum('target_amount');
                
                $employeeYearlyTarget = $employeeYearlyTarget ?: 0;
                
                $phpRow = [$user->name, 'Php'];
                $total = 0;
                
                for ($m = 1; $m <= 12; $m++) {
                    $monthRecords = $userRecords->filter(function($record) use ($selectedYear, $m) {
                        $date = \Carbon\Carbon::parse($record->date_of_registration);
                        return $date->year == $selectedYear && $date->month == $m;
                    });
                    $amount = $monthRecords->sum('purchase_amount');
                    $phpRow[] = number_format($amount, 2);
                    $total += $amount;
                }
                
                $phpRow[] = number_format($total, 2);
                $phpRow[] = number_format($employeeYearlyTarget, 2);
                
                $achievement = $employeeYearlyTarget > 0 
                    ? ($total / $employeeYearlyTarget) * 100 
                    : 0;
                $phpRow[] = number_format($achievement, 2) . '%';
                
                fputcsv($output, $phpRow);
            }
            
            fputcsv($output, []);
            fputcsv($output, []);
        }
        
        fclose($output);
        exit;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date_registered' => 'required|date',
            'employee_name' => 'required|string|max:255',
            'area' => 'required|integer|exists:regions,id',
            'customer_name' => 'required|string|max:255',
            'contact_no' => 'required|string|max:255',
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|string|max:255',
            'awarded_area' => 'nullable|string|max:255',
            'package_type' => 'required|in:EU,D,MD,AD',
            'purchase_amount' => 'required|numeric|min:0',
            'program_type' => 'nullable|in:Roadshow,Mini-Roadshow,Non-Roadshow',
            'program_area' => 'required_if:program_type,Roadshow,Mini-Roadshow|nullable|string|max:255',
            'lead_generator' => 'required|string|max:255',
            'supplier_name' => 'required|string|max:255',
            'status' => 'required|in:Decline,Interested,For Delivery,Delivered',
            'timeline' => 'nullable|date',
            'delivery_date' => 'nullable|date',
            'document_attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'additional_notes' => 'nullable|string',
        ], [
            'package_type.in' => 'Package type must be EU, D, MD, or AD',
            'status.in' => 'Status must be Decline, Interested, For Delivery, or Delivered',
            'area.exists' => 'Selected area is invalid',
            'program_area.required_if' => 'Program area is required for Roadshow and Mini-Roadshow',
            'document_attachment.mimes' => 'Document must be a PDF, DOC, DOCX, JPG, JPEG, or PNG file',
            'document_attachment.max' => 'Document size must not exceed 5MB',
        ]);

        DB::beginTransaction();
        try {
            $documentFileName = null;
            
            if ($request->hasFile('document_attachment')) {
                $file = $request->file('document_attachment');
                $documentFileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/tds_documents', $documentFileName);
            }

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
                'program_type' => $request->program_type,
                'program_area' => $request->program_area,
                'lead_generator' => $request->lead_generator,
                'supplier_name' => $request->supplier_name,
                'status' => $request->status,
                'timeline' => $request->timeline,
                'delivery_date' => $request->delivery_date,
                'document_attachment' => $documentFileName,
                'additional_notes' => $request->additional_notes,
            ]);

            $tds->logActivity('created', [
                'customer_name' => $request->customer_name,
                'business_name' => $request->business_name,
                'package_type' => $request->package_type,
                'purchase_amount' => $request->purchase_amount,
                'program_type' => $request->program_type,
                'status' => $request->status,
            ]);

            DB::commit();

            return redirect()->route('tds.index')
                ->with('success', 'Dealer registered successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if (isset($documentFileName) && $documentFileName) {
                \Storage::delete('public/tds_documents/' . $documentFileName);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to register dealer: ' . $e->getMessage());
        }
    }

    // NEW METHOD: Update only status
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Decline,Interested,For Delivery,Delivered',
        ]);

        DB::beginTransaction();
        try {
            $tds = Tds::findOrFail($id);
            $oldStatus = $tds->status;

            $tds->update([
                'status' => $request->status,
            ]);

            $tds->logActivity('status_updated', [
                'customer_name' => $tds->customer_name,
                'old_status' => $oldStatus,
                'new_status' => $request->status,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully!',
                'new_status' => $request->status
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
    //     $validated = $request->validate([
    //         'date_registered' => 'required|date',
    //         'area' => 'required|integer|exists:regions,id',
    //         'customer_name' => 'required|string|max:255',
    //         'contact_no' => 'required|string|regex:/^[0-9]{11}$/',
    //         'business_name' => 'required|string|max:255',
    //         'business_type' => 'required|string|max:255',
    //         'awarded_area' => 'nullable|string|max:255',
    //         'package_type' => 'required|in:EU,D,MD,AD',
    //         'purchase_amount' => 'required|numeric|min:0',
    //         'lead_generator' => 'required|string|max:255',
    //         'supplier_name' => 'required|string|max:255',
    //         'status' => 'required|in:For Delivery,Delivered',
    //         'timeline' => 'nullable|date',
    //         'additional_notes' => 'nullable|string',
    //     ]);

    //     DB::beginTransaction();
    //     try {
    //         $tds = Tds::findOrFail($id);
    //         $oldData = $tds->getOriginal();

    //         $tds->update([
    //             'date_of_registration' => $request->date_registered,
    //             'area' => $request->area,
    //             'customer_name' => $request->customer_name,
    //             'contact_no' => $request->contact_no,
    //             'business_name' => $request->business_name,
    //             'awarded_area' => $request->awarded_area,
    //             'business_type' => $request->business_type,
    //             'package_type' => $request->package_type,
    //             'purchase_amount' => $request->purchase_amount,
    //             'lead_generator' => $request->lead_generator,
    //             'supplier_name' => $request->supplier_name,
    //             'status' => $request->status,
    //             'timeline' => $request->timeline,
    //             'additional_notes' => $request->additional_notes,
    //         ]);

    //         $changes = [];
    //         if ($oldData['status'] != $request->status) {
    //             $changes['status'] = ['from' => $oldData['status'], 'to' => $request->status];
    //         }
    //         if ($oldData['purchase_amount'] != $request->purchase_amount) {
    //             $changes['purchase_amount'] = ['from' => $oldData['purchase_amount'], 'to' => $request->purchase_amount];
    //         }
    //         if ($oldData['package_type'] != $request->package_type) {
    //             $changes['package_type'] = ['from' => $oldData['package_type'], 'to' => $request->package_type];
    //         }

    //         $tds->logActivity('updated', [
    //             'customer_name' => $request->customer_name,
    //             'changes' => $changes
    //         ]);

    //         DB::commit();

    //         return redirect()->route('tds.index')
    //             ->with('success', 'Dealer information updated successfully!');

    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return redirect()->back()
    //             ->withInput()
    //             ->with('error', 'Failed to update dealer: ' . $e->getMessage());
    //     }
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
            'user_id' => 'required|exists:users,id',
            'month' => 'required|date_format:Y-m',
            'target_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $salesTarget = SalesTarget::updateOrCreate(
                [
                    'user_id' => $request->user_id,
                    'month' => $request->month
                ],
                [
                    'target_amount' => $request->target_amount,
                    'notes' => $request->notes,
                ]
            );

            $action = $salesTarget->wasRecentlyCreated ? 'created' : 'updated';
            
            $user = User::find($request->user_id);
            $salesTarget->logActivity($action, [
                'employee' => $user->name,
                'month' => $request->month,
                'target_amount' => $request->target_amount,
                'notes' => $request->notes,
            ]);

            DB::commit();

            $message = $action === 'created' 
                ? "Sales target set successfully for {$user->name}!" 
                : "Sales target updated successfully for {$user->name}!";

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
                $this->getRegionDisplayName($record->region),
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