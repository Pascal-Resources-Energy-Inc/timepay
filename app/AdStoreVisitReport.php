<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdStoreVisitReport extends Model {
    const APPROVAL_PENDING = 'pending';
    const APPROVAL_FROM_IMPORT = 'from_import';
    const APPROVAL_APPROVED = 'approved';
    const APPROVAL_DISAPPROVED = 'disapproved';

    protected $table = 'ad_store_visit_reports';

    protected $fillable = [
        'reference_no',
        'area_distributor',
        'area_distributor_email',
        'representative_email',
        'visit_date',
        'time_in',
        'time_out',
        'issues_and_concerns',
        'recommendations',
        'submitted_by_name',
        'submitted_by_designation',
        'submitted_by_email',
        'user_id',
        'status',

        'approval_status',
        'disapproval_reason',
        'responded_at',

        'submitted_at',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'status' => 'boolean',
        'submitted_at' => 'datetime',
    ];

    public function isApprovalPending() {
        return $this->approval_status === self::APPROVAL_PENDING;
    }

    public function isFromImport()
    {
        return $this->approval_status === self::APPROVAL_FROM_IMPORT;
    }

    public function isApproved()
    {
        return $this->approval_status === self::APPROVAL_APPROVED;
    }

    public function isDisapproved()
    {
        return $this->approval_status === self::APPROVAL_DISAPPROVED;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function pointPersons()
    {
        return $this->hasMany(
            AdStoreVisitPointPerson::class,
            'ad_store_visit_report_id'
        );
    }

    public function productAvailabilities()
    {
        return $this->hasMany(
            AdStoreVisitProductAvailability::class,
            'ad_store_visit_report_id'
        )->orderBy('sort_order');
    }

    public function acquiredAccounts()
    {
        return $this->hasMany(
            AdStoreVisitAcquiredAccount::class,
            'ad_store_visit_report_id'
        );
    }

    public function otherMatters()
    {
        return $this->hasMany(
            AdStoreVisitOtherMatter::class,
            'ad_store_visit_report_id'
        );
    }

    public function attachments()
    {
        return $this->hasMany(
            AdStoreVisitAttachment::class,
            'ad_store_visit_report_id'
        );
    }
}
