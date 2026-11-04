<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MarketingMaterialRelease extends Model {
    protected $table = 'marketing_material_releases';

    const EDIT_WINDOW_HOURS = 24;

    const GENDERS = [
        'Male',
        'Female',
    ];

    const NAME_USAGE_FIELDS = [
        'consent_complete_name',
        'consent_first_name_only',
        'consent_nickname',
        'consent_anonymous_photos',
    ];

    const AUTHORIZATION_FIELDS = [
        'authorize_capture',
        'authorize_social_media',
        'authorize_editing',
        'agree_company_ip_ownership',
        'agree_no_compensation',
    ];

    protected $fillable = [
        'reference_no',
        'first_name',
        'last_name',
        'birth_date',
        'gender',
        'phone_number',
        'email',
        'address_line',
        'address_city',
        'address_state_province',
        'address_postal_code',
        'consent_complete_name',
        'consent_first_name_only',
        'consent_nickname',
        'consent_anonymous_photos',
        'authorize_capture',
        'authorize_social_media',
        'authorize_editing',
        'agree_company_ip_ownership',
        'agree_no_compensation',
        'signature',
        'date_signed',
        'status',
        'approved_date',
        'approval_remarks',
        'approved_by',
        'user_id',
        'submitted_at',
    ];

    protected $casts = [
        'consent_complete_name' => 'boolean',
        'consent_first_name_only' => 'boolean',
        'consent_nickname' => 'boolean',
        'consent_anonymous_photos' => 'boolean',
        'authorize_capture' => 'boolean',
        'authorize_social_media' => 'boolean',
        'authorize_editing' => 'boolean',
        'agree_company_ip_ownership' => 'boolean',
        'agree_no_compensation' => 'boolean',
        'birth_date' => 'date',
        'date_signed' => 'date',
        'approved_date' => 'datetime',
        'submitted_at' => 'datetime',
        'edited_at' => 'datetime',
    ];

    public function canBeEditedBy($userId) {
        return $this->status === 'For Approval'
            && !is_null($userId)
            && !is_null($this->user_id)
            && (int) $this->user_id === (int) $userId
            && is_null($this->edited_at)
            && $this->submitted_at
            && $this->submitted_at->gt(now()->subHours(self::EDIT_WINDOW_HOURS));
    }

    public function canBeEditedPublicly() {
        return $this->status === 'For Approval'
            && is_null($this->user_id)
            && is_null($this->edited_at)
            && $this->submitted_at
            && $this->submitted_at->gt(now()->subHours(self::EDIT_WINDOW_HOURS));
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function approver() {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }
}
