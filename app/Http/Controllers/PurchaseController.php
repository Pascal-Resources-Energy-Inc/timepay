<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $header = 'purchase';
        
        $from = $request->input('from', date('Y-m-d'));
        $to = $request->input('to', date('Y-m-d'));
        $status = $request->input('status');
        
        $userId = Auth::id();
        
        $query = DB::table('purchases')
            ->where('user_id', $userId)
            ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->orderBy('created_at', 'desc');
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $purchases = $query->get();
        
        $totalPurchase = DB::table('purchases')
            ->where('user_id', $userId)
            ->sum('total_items');
        
        $totalThisMonth = DB::table('purchases')
            ->where('user_id', $userId)
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->count();
        
        $remaining = DB::table('purchases')
            ->where('user_id', $userId)
            ->where('status', 'Processing')
            ->count();
        
        $totalItemsThisMonth = DB::table('purchases')
            ->where('user_id', $userId)
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->sum('total_items');
        
        $stats = [
            'total_purchase' => $totalPurchase,
            'total_this_month' => $totalThisMonth,
            'remaining' => $remaining,
            'total_items_sum' => $totalItemsThisMonth
        ];
        
        return view('forms.purchase.purchase', compact('header', 'purchases', 'stats', 'from', 'to', 'status'));
    }
    
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'employee_number' => 'nullable|string|max:50',
                'employee_name' => 'nullable|string|max:255',
                'total_items' => 'required|integer|min:1',
                'subtotal' => 'required|numeric|min:0',
                'discount' => 'nullable|numeric|min:0',
                'total_amount' => 'required|numeric|min:0',
                'payment_method' => 'nullable|string|max:50',
                'notes' => 'nullable|string'
            ]);
            
            $lastOrder = DB::table('purchases')->orderBy('id', 'desc')->first();
            $orderNumber = 'ON-' . ($lastOrder ? ($lastOrder->id + 1) : 1);
            
            // Generate unique QR code (10 characters)
            $qrCode = $this->generateUniqueQRCode();
            
            // Generate the full claim URL for QR code
            $claimUrl = route('purchase.claim', ['qr_code' => $qrCode]);
            
            DB::table('purchases')->insert([
                'order_number' => $orderNumber,
                'user_id' => Auth::id(),
                'employee_number' => $validated['employee_number'] ?? null,
                'employee_name' => $validated['employee_name'] ?? null,
                'total_items' => $validated['total_items'],
                'subtotal' => $validated['subtotal'],
                'discount' => $validated['discount'] ?? 0,
                'total_amount' => $validated['total_amount'],
                'payment_method' => $validated['payment_method'] ?? null,
                'status' => 'Processing',
                'qr_code' => $qrCode,
                'notes' => $validated['notes'] ?? null,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Purchase order created successfully!',
                'qr_code' => $qrCode,
                'claim_url' => $claimUrl
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create purchase order: ' . $e->getMessage()
            ], 500);
        }
    }
    
    private function generateUniqueQRCode()
    {
        do {
            // Generate random 10-character alphanumeric code
            $qrCode = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10));
            
            // Check if QR code already exists
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
            // Find purchase by QR code
            $purchase = DB::table('purchases')
                ->where('qr_code', $qr_code)
                ->first();
            
            // If purchase not found
            if (!$purchase) {
                return view('forms.purchase.claim', [
                    'error' => 'Order Not Found',
                    'message' => 'The QR code you scanned is invalid or the order does not exist in our system.'
                ]);
            }
            
            // Return the claim page with purchase details
            return view('forms.purchase.claim', compact('purchase'));
            
        } catch (\Exception $e) {
            return view('forms.purchase.claim', [
                'error' => 'System Error',
                'message' => 'An error occurred while retrieving your order. Please try again later.'
            ]);
        }
    }
}