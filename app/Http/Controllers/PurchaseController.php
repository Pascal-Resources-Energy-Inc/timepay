<?php

namespace App\Http\Controllers;

use App\Purchase;
use App\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\PurchaseExport;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $header = 'purchase';

        $this->updateExpiredPurchases();
        
        $from = $request->input('from', date('Y-m-d'));
        $to = $request->input('to', date('Y-m-d'));
        $status = $request->input('status');
        
        $userId = Auth::id();
        
        $currentEmployee = Employee::where('user_id', $userId)->first();
        
        $query = Purchase::with(['employee', 'items'])
            ->where('user_id', $userId)
            ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->orderBy('created_at', 'desc');
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $purchases = $query->get();
        
        foreach ($purchases as $purchase) {
            if ($purchase->employee) {
                $purchase->purchaser_name = trim(
                    ($purchase->employee->first_name ?? '') . ' ' . 
                    ($purchase->employee->middle_name ?? '') . ' ' . 
                    ($purchase->employee->last_name ?? '')
                );
                $purchase->purchaser_name = preg_replace('/\s+/', ' ', $purchase->purchaser_name);
                $purchase->employee_work_place = $purchase->employee->location ?? 'N/A';
            } else {
                $purchase->purchaser_name = 'N/A';
                $purchase->employee_work_place = 'N/A';
            }
        }
        
        $totalMainProductsAllTime = DB::table('purchase_items')
            ->join('purchases', 'purchase_items.purchase_id', '=', 'purchases.id')
            ->join('products', 'purchase_items.product_id', '=', 'products.id')
            ->where('products.main', 'on')
            ->where('purchases.user_id', $userId)
            ->sum('purchase_items.quantity') ?? 0;
        
        $totalOrdersThisMonth = DB::table('purchases')
            ->where('user_id', $userId)
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->count();
        
        $remaining = DB::table('purchases')
            ->where('user_id', $userId)
            ->where('status', 'Processing')
            ->count();
        
        $totalMainProductsThisMonth = DB::table('purchase_items')
            ->join('purchases', 'purchase_items.purchase_id', '=', 'purchases.id')
            ->join('products', 'purchase_items.product_id', '=', 'products.id')
            ->where('products.main', 'on')
            ->where('purchases.user_id', $userId)
            ->whereYear('purchases.created_at', date('Y'))
            ->whereMonth('purchases.created_at', date('m'))
            ->sum('purchase_items.quantity') ?? 0;
        
        $stats = [
            'total_purchase' => $totalMainProductsAllTime,
            'total_this_month' => $totalOrdersThisMonth,
            'remaining' => $remaining,
            'total_items_sum' => $totalMainProductsThisMonth
        ];
        
        $products = DB::table('products')
            ->select('id', 'product_name', 'price', 'product_image', 'main')
            ->orderBy('id', 'asc')
            ->get();
        
        return view('forms.purchase.purchase', compact('header', 'purchases', 'stats', 'from', 'to', 'status', 'products'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'products' => 'required|array|min:1',
                'products.*.product_id' => 'required|integer|exists:products,id',
                'products.*.quantity' => 'required|integer|min:1',
                'total_items' => 'required|integer|min:1',
                'subtotal' => 'required|numeric|min:0',
                'discount' => 'nullable|numeric|min:0',
                'total_amount' => 'required|numeric|min:0',
                'payment_method' => 'required|string'
            ]);

            $userId = Auth::id();
            
            $mainProductsThisMonth = DB::table('purchase_items')
                ->join('purchases', 'purchase_items.purchase_id', '=', 'purchases.id')
                ->join('products', 'purchase_items.product_id', '=', 'products.id')
                ->where('products.main', 'on')
                ->where('purchases.user_id', $userId)
                ->whereYear('purchases.created_at', date('Y'))
                ->whereMonth('purchases.created_at', date('m'))
                ->sum('purchase_items.quantity');
            
            $newMainProducts = 0;
            foreach ($validated['products'] as $item) {
                $product = DB::table('products')->where('id', $item['product_id'])->first();
                if ($product && $product->main == 'on') {
                    $newMainProducts += $item['quantity'];
                }
            }
            
            if (($mainProductsThisMonth + $newMainProducts) > 10) {
                return response()->json([
                    'success' => false,
                    'message' => "You can only order " . (10 - $mainProductsThisMonth) . " more main product items this month."
                ], 400);
            }

            DB::beginTransaction();
            
            $employee = DB::table('employees')
                ->where('user_id', $userId)
                ->orWhere('id', $userId)
                ->first();
            
            if (!$employee) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Employee record not found. Please contact administrator.'
                ], 404);
            }
            
            $qrCode = $this->generateUniqueQRCode();
            $orderNumber = 'EPO-' . strtoupper(substr(uniqid(), -6));
            
            $purchaseId = DB::table('purchases')->insertGetId([
                'order_number' => $orderNumber,
                'user_id' => $userId,
                'employee_number' => $employee->employee_number,
                'total_items' => $validated['total_items'],
                'subtotal' => $validated['subtotal'],
                'discount' => $validated['discount'] ?? 0,
                'total_amount' => $validated['total_amount'],
                'payment_method' => $validated['payment_method'],
                'status' => 'Processing',
                'qr_code' => $qrCode,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            foreach ($validated['products'] as $item) {
                $product = DB::table('products')
                    ->where('id', $item['product_id'])
                    ->first();
                
                if ($product) {
                    $subtotal = $product->price * $item['quantity'];
                    
                    DB::table('purchase_items')->insert([
                        'purchase_id' => $purchaseId,
                        'product_id' => $product->id,
                        'product_name' => $product->product_name,
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                        'subtotal' => $subtotal,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order_number' => $orderNumber,
                'qr_code' => $qrCode,
                'purchase_id' => $purchaseId
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to place order: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function getProducts()
    {
        try {
            $products = DB::table('products')->orderBy('id', 'asc')->get();
            
            $formatted = [];
            
            foreach($products as $p) {
                $formatted[] = [
                    'id' => $p->id,
                    'name' => $p->product_name,
                    'price' => $p->price,
                    'image' => $p->product_image,
                    'is_main' => $p->main == 'on'
                ];
            }
            
            return response()->json([
                'success' => true,
                'products' => $formatted,
                'total' => count($formatted)
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    private function generateUniqueQRCode()
    {
        do {
            $qrCode = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10));
            $exists = DB::table('purchases')->where('qr_code', $qrCode)->exists();
        } while ($exists);
        
        return $qrCode;
    }
    
    public function approve(Request $request, $id)
    {
        try {
            DB::table('purchases')
                ->where('id', $id)
                ->update([
                    'status' => 'Claimed',
                    'approver_id' => Auth::id(),
                    'approved_at' => now(),
                    'claimed_at' => now(),
                    'updated_at' => now()
                ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Purchase approved successfully!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve purchase: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function claimPage($qr_code)
    {
        try {
            $purchase = Purchase::with(['employee', 'items.product'])
                ->where('qr_code', $qr_code)
                ->first();
            
            if (!$purchase) {
                return view('forms.purchase.claim', [
                    'error' => 'Order Not Found',
                    'message' => 'The QR code you scanned is invalid or the order does not exist in our system.'
                ]);
            }
            
            $currentUser = null;
            if (Auth::check()) {
                $currentUser = Auth::user();
                $currentEmployee = Employee::where('user_id', $currentUser->id)->first();
                
                if ($currentEmployee) {
                    $currentUser->employee_name = trim(
                        ($currentEmployee->first_name ?? '') . ' ' . 
                        ($currentEmployee->middle_name ?? '') . ' ' . 
                        ($currentEmployee->last_name ?? '')
                    );
                    $currentUser->employee_name = preg_replace('/\s+/', ' ', $currentUser->employee_name);
                    $currentUser->employee_number = $currentEmployee->employee_number ?? 'N/A';
                    $currentUser->work_location = $currentEmployee->location ?? 'N/A';
                    $currentUser->position = $currentEmployee->position ?? 'N/A';
                }
            }
            
            $claimerEmployee = null;
            if ($purchase->claimed_by) {
                $claimerEmployee = Employee::where('employee_number', $purchase->claimed_by)
                    ->orWhere(DB::raw("CONCAT(first_name, ' ', COALESCE(middle_name, ''), ' ', last_name)"), 'LIKE', '%' . $purchase->claimed_by . '%')
                    ->first();
                
                if ($claimerEmployee) {
                    $purchase->claimer_name = trim(
                        ($claimerEmployee->first_name ?? '') . ' ' . 
                        ($claimerEmployee->middle_name ?? '') . ' ' . 
                        ($claimerEmployee->last_name ?? '')
                    );
                    $purchase->claimer_name = preg_replace('/\s+/', ' ', $purchase->claimer_name);
                    $purchase->claimer_position = $claimerEmployee->position ?? 'N/A';
                    $purchase->claimer_location = $claimerEmployee->location ?? 'N/A';
                }
            }
            
            if ($purchase->employee) {
                $purchase->employee_name = trim(
                    ($purchase->employee->first_name ?? '') . ' ' . 
                    ($purchase->employee->middle_name ?? '') . ' ' . 
                    ($purchase->employee->last_name ?? '')
                );
                $purchase->employee_name = preg_replace('/\s+/', ' ', $purchase->employee_name);
                $purchase->employee_work_place = $purchase->employee->location ?? 'N/A';
            } else {
                $purchase->employee_name = 'N/A';
                $purchase->employee_work_place = 'N/A';
            }
            
            return view('forms.purchase.claim', compact('purchase', 'currentUser'));
            
        } catch (\Exception $e) {
            return view('forms.purchase.claim', [
                'error' => 'System Error',
                'message' => 'An error occurred while retrieving your order. Please try again later.'
            ]);
        }
    }
    
    public function processClaim(Request $request)
    {
        try {
            $validated = $request->validate([
                'purchase_id' => 'required|integer|exists:purchases,id',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'claimed_by' => 'nullable|string',
                'address' => 'required|string',
                'giver_name' => 'required|string|max:255',
                'giver_position' => 'required|string|max:255',
            ]);
            
            $purchase = DB::table('purchases')->where('id', $validated['purchase_id'])->first();
            
            if (!$purchase) {
                return response()->json([
                    'success' => false,
                    'message' => 'Purchase order not found.'
                ], 404);
            }
            
            $createdAt = new \DateTime($purchase->created_at);
            $now = new \DateTime();
            $expiresAt = $this->addBusinessDays($createdAt, 3);
            
            if ($now > $expiresAt) {
                DB::table('purchases')
                    ->where('id', $validated['purchase_id'])
                    ->update([
                        'status' => 'Forfeited',
                        'updated_at' => now()
                    ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'This order has expired. Orders must be claimed within 3 business days (excluding weekends) of purchase.'
                ], 400);
            }
            
            if ($purchase->status !== 'Processing') {
                return response()->json([
                    'success' => false,
                    'message' => 'This order has already been claimed or is no longer available.'
                ], 400);
            }
            
            DB::table('purchases')
                ->where('id', $validated['purchase_id'])
                ->update([
                    'status' => 'Claimed',
                    'claimed_at' => now(),
                    'claim_latitude' => $validated['latitude'],
                    'claim_longitude' => $validated['longitude'],
                    'claimed_by' => $validated['claimed_by'] ?? 'N/A',
                    'claim_address' => $validated['address'],
                    'giver_name' => $validated['giver_name'],
                    'giver_position' => $validated['giver_position'],
                    'updated_at' => now()
                ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Order claimed successfully!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process claim: ' . $e->getMessage()
            ], 500);
        }
    }

    private function addBusinessDays($date, $days)
    {
        $currentDate = clone $date;
        $addedDays = 0;
        
        while ($addedDays < $days) {
            $currentDate->modify('+1 day');
            $dayOfWeek = (int)$currentDate->format('N');
            if ($dayOfWeek < 6) {
                $addedDays++;
            }
        }
        
        return $currentDate;
    }

    public function export(Request $request)
    {
        $from = $request->input('from', date('Y-m-d'));
        $to = $request->input('to', date('Y-m-d'));
        
        $filename = 'Employee_PO_' . $from . '_to_' . $to . '.xlsx';
        
        return Excel::download(new PurchaseExport($from, $to, null), $filename);
    }

    private function updateExpiredPurchases()
    {
        $purchases = DB::table('purchases')
            ->where('status', 'Processing')
            ->get();
        
        foreach ($purchases as $purchase) {
            $createdAt = new \DateTime($purchase->created_at);
            $expiresAt = $this->addBusinessDays($createdAt, 3);
            $now = new \DateTime();
            
            if ($now > $expiresAt) {
                DB::table('purchases')
                    ->where('id', $purchase->id)
                    ->update([
                        'status' => 'Forfeited',
                        'updated_at' => now()
                    ]);
            }
        }
    }

    public function reports(Request $request)
    {
        $header = 'reports';

        $this->updateExpiredPurchases();
        
        $from = $request->input('from');
        $to = $request->input('to');
        $status = $request->input('status');
        
        $purchases = null;
        
        if ($from && $to) {
            
            $query = Purchase::with('employee')
                ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->orderBy('created_at', 'desc');
            
            if ($status && $status != '') {
                $query->where('status', $status);
            }
            
            $purchases = $query->get();
            
            foreach ($purchases as $purchase) {
                if ($purchase->employee) {
                    $purchase->purchaser_name = trim(
                        ($purchase->employee->first_name ?? '') . ' ' . 
                        ($purchase->employee->middle_name ?? '') . ' ' . 
                        ($purchase->employee->last_name ?? '')
                    );
                    $purchase->purchaser_name = preg_replace('/\s+/', ' ', $purchase->purchaser_name);
                    $purchase->employee_work_place = $purchase->employee->location ?? 'N/A';
                } else {
                    $purchase->purchaser_name = 'N/A';
                    $purchase->employee_work_place = 'N/A';
                }
            }
        }
        
        return view('reports.discounted_refill_reports', compact('header', 'purchases', 'from', 'to', 'status'));
    }
}