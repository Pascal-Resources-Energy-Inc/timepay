<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LayoutDesign extends Model {
    protected $table = 'layout_design_requests';

    const STATUS_PENDING = 'Pending';
    const STATUS_APPROVED = 'Approved';
    const STATUS_PROCESSED = 'Processed';
    const STATUS_DECLINED = 'Declined';
    const STATUS_CANCELLED = 'Cancelled';
    const STATUS_FROM_IMPORT = 'From Import';

    const PRIORITY_LEVELS = [
        'Minor',
        'Major',
    ];

    const DEPARTMENTS = [
        'Office of the CEO (CEO)',
        'Office of the President (OOP)',
        'Business Devt Dept (BDD)',
        'Business Srvcs Dept (BSD)',
        'Commercial Account Mgt Department (CMD)',
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
    ];

    const INTENDED_USES = [
        'Internal Use',
        'External Use',
    ];

    const MEDIUM_TYPES = [
        'Digital Media',
        'Print Media',
    ];

    const COLLATERAL_TYPES = [
        'Business Card Layout',
        'Announcement Layout',
        'Poster Layout',
        'Tarpaulin Layout',
        'Promotional Item Layout',
        'Video',
        'Other (Put details in project description)',
    ];

    const STORE_SIGNAGE_OPTIONS = [
        'Layout file of store signage only',
        'Refurbished Store Signage (P5,000)',
    ];

    protected $casts = [
        'intended_use' => 'array',
        'marketing_collateral_type' => 'array',
        'date_needed' => 'datetime',
        'due_date' => 'datetime',
        'date_requested' => 'datetime',
        'issuance_date' => 'date',
    ];

    protected $fillable = [
        'reference_no',
        'date_requested',
        'requestor_first_name',
        'requestor_last_name',
        'department',
        'requestor_email',
        'intended_use',
        'medium_type',
        'marketing_collateral_type',
        'purpose_of_layout_request',
        'project_description',
        'priority_level',
        'peg_sample_path',
        'date_needed',
        'due_date',
        'status',
        'user_id',
        'remarks',
        'issuance_date',
        'closing_remarks',

        'business_name', 
        'ad_territory',
        'business_address',
        'contact_number',
        'additional_notes'
    ];

    protected $dates = [
        'submitted_at',
    ];

    public function outputAttachments() {
        return $this->hasMany(
            LayoutDesignAttachment::class, 'layout_design_request_id'
        )
            ->where('output_attachment_path', 'not like', '/peg_samples/%')
            ->where(function ($query) {
                $query->whereNull('mime_type')
                    ->orWhere('mime_type', '!=', LayoutDesignAttachment::PEG_SAMPLE_LINK_MIME_TYPE);
            });
    }

    public function pegSamples() {
        return $this->hasMany(
            LayoutDesignAttachment::class, 'layout_design_request_id'
        )->where(function ($query) {
            $query->where('output_attachment_path', 'like', '/peg_samples/%')
                ->orWhere('mime_type', LayoutDesignAttachment::PEG_SAMPLE_LINK_MIME_TYPE);
        });
    }
}
