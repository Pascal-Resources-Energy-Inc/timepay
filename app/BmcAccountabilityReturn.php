<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BmcAccountabilityReturn extends Model
{
    protected $table = 'bmc_accountability_returns';

    const RETURN_MODES = [
        'Drop off' => 'Drop off',
        'Courier' => 'Courier',
        'Officer Name' => 'Thru Officer',
        'Field location' => 'Thru Plant/Warehouse',
    ];

    protected $fillable = [
        'bmc_accountability_id',
        'bmc_accountability_item_id',
        'returned_quantity',
        'serial_numbers',
        'returned_at',
        'return_mode',
        'received_by',
        'condition_on_return',
        'borrower_remarks',
        'approver_remarks',
        'attachment'
    ];

    protected $dates = [
        'returned_at',
    ];

    public function getAttachmentAttribute($path)
    {
        $previousDirectory = 'bmc/return-proofs/';

        if (is_string($path) && strpos($path, $previousDirectory) === 0) {
            return 'bmc_return_proofs/' . substr($path, strlen($previousDirectory));
        }

        return $path;
    }

    public function getAttachmentPathsAttribute()
    {
        $attachment = $this->attachment;

        if (!$attachment) {
            return [];
        }

        $paths = json_decode($attachment, true);

        return is_array($paths) ? $paths : [$attachment];
    }

    public function getReturnModeDetailsAttribute()
    {
        $remarks = trim((string) $this->borrower_remarks);

        return preg_match('/^Details:[ \t]*(.+?)(?:\r?\n|$)/', $remarks, $matches)
            ? trim($matches[1])
            : null;
    }

    public function getMorAttribute()
    {
        $mode = trim((string) $this->return_mode);
        $mode = self::RETURN_MODES[$mode] ?? $mode;
        $details = $this->return_mode_details;

        if ($mode === '') {
            return $details;
        }

        return $details !== null && $details !== '' ? $mode . ': ' . $details : $mode;
    }

    public function getReturnRemarksAttribute()
    {
        $remarks = trim((string) $this->borrower_remarks);

        if ($remarks === '') {
            return null;
        }

        $returnRemarks = preg_replace(
            '/^Details:\s*.*?(?:\r?\n|$)/',
            '',
            $remarks,
            1
        );

        $returnRemarks = trim($returnRemarks);

        return $returnRemarks !== '' ? $returnRemarks : null;
    }

    public function accountability()
    {
        return $this->belongsTo(
            BmcAccountability::class,
            'bmc_accountability_id'
        );
    }

    public function item()
    {
        return $this->belongsTo(
            BmcAccountabilityItem::class,
            'bmc_accountability_item_id'
        );
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
