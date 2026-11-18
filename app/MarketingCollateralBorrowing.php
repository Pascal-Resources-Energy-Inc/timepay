<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MarketingCollateralBorrowing extends Model {
    protected $table = 'bmc';

    const STATUS_FOR_APPROVAL = 'For Approval';
    const STATUS_APPROVED = 'Approved';
    const STATUS_PARTIALLY_RELEASED = 'Partially Released';
    const STATUS_RELEASED = 'Released';
    const STATUS_CLOSED = 'Closed';
    const STATUS_DECLINED = 'Declined';
    const STATUS_CANCELLED = 'Cancelled';

    const DEPARTMENTS = [
        'Office of the CEO (CEO)',
        'Office of the President (OOP)',
        'Business Devt Dept (BDD)',
        'Business Srvcs Dept (BSD)',
        'Commercial Account Mgt Deparment (CMD)',
        'Corporate Accounting Department (CAD)',
        'HR Dept (HRD)',
        'Internal Audit Department (IAD)',
        'IT Department (ITD)',
        'Marketing & Branding (MBD)',
        'Plant Operations Dept (POD)',
        'Process & Quality Mgt Dept (PQD)',
        'Procurement Dept (PCD)',
        'Retail Sales Dept (RSD)',
        'Treasury Department (TRD)',
        'Area Distributor (AD)'
    ];

    const BORROWABLE_ITEMS = [
        'Booth',
        'Negosyo Partner Roll Up Banner',
        'End User Roll Up Banner',
    ];

    const NON_PREI_BORROWER_PROFILES = [
        'Area Distributor',
        'Provincial Distributor',
    ];

    const DEPOSIT_RATES = [
        'Booth' => 3400,
        'Negosyo Partner Roll Up Banner' => 2000,
        'End User Roll Up Banner' => 2000,
    ];

    protected $fillable = [
        'reference_no',
        'borrower_profile',
        'borrower_first_name',
        'borrower_last_name',
        'department',
        'business_name',
        'designation',
        'email',
        'hub_code',
        'hub_name',
        'phone_number',
        'location',
        'date_needed',
        'event_name',
        'request_details',
        'number_of_days_item_needed',
        'approved_number_of_days',
        'status',
        'user_id',
        'processed_by',
        'processed_at',
        'mbd_remarks',

        // deposit
        'release_items', 
        'payment_method',
        'proof_of_transfer',


        // deposit refund
        'refund_payment_method',
        'proof_of_refund'

    ];

    protected $casts = [
        'number_of_days_item_needed' => 'integer',
        'approved_number_of_days' => 'integer',
        'release_items' => 'array',
    ];

    protected $dates = [
        'date_needed',
        'processed_at',
    ];

    public function accountabilities() {
        return $this->hasMany(
            BmcAccountability::class,
            'marketing_collateral_borrowing_id'
        );
    }

    public function borrowedItems() {
        return $this->hasMany(
            BmcBorrowedItem::class,
            'marketing_collateral_borrowing_id'
        );
    }

    public function requiresDeposit() {
        return in_array(
            $this->borrower_profile,
            self::NON_PREI_BORROWER_PROFILES,
            true
        );
    }

    public function depositRate($item) {
        return (float) (
            self::DEPOSIT_RATES[$item] ?? 0
        );
    }

    public function processedBy() {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
