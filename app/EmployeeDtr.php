<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeDtr extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public static function adjustmentTypes()
    {
        return [
            'Correction of Mobile In/Out' => '+/- 15mins',
            'Correction of In/Out due to wrong Biometric Action' => '+/- 15mins',
            'Failed to use Biometrics during In/Out' => '+/- 15 mins',
            'Failed to submit Time In/Out at Field' => '+/- 15 mins',
            'Failed to use Biometrics due to power interruption' => 'No penalty time',
            'Late In due to late opening of office' => 'No penalty time',
            'Late Time In/ Out due to poor signal' => 'No penalty time',
            'Correction of In/Out due to travel time' => 'No penalty time',
            'Early Out' => 'No penalty time' 
        ];
    }

    public static function penaltyAdjustmentTypes()
    {
        return [
            'Failed to use Biometrics during In/Out',
            'Failed to submit Time In/Out at Field',
        ];
    }

    public static function isPenaltyAdjustmentType($adjustment_type)
    {
        return in_array($adjustment_type, self::penaltyAdjustmentTypes());
    }

    public static function encodedTimeForEdit($time, $adjustment_type, $field)
    {
        if(!$time){
            return '';
        }

        $minutes = 0;

        if(self::isPenaltyAdjustmentType($adjustment_type)){
            $minutes = $field == 'time_in' ? -15 : 15;
        }

        return Carbon::parse($time)->addMinutes($minutes)->format('Y-m-d\TH:i');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class,'user_id','user_id');
    }     

    public function schedule()
    {
        return $this->hasMany(ScheduleData::class,'schedule_id','schedule_id');
    }   

    public function approver()
    {
        return $this->hasMany(EmployeeApprover::class,'user_id','user_id');
    }  

    public function attachmentFiles() {
        return $this->hasMany(EmployeeDtrAttachment::class, 'employee_dtr_id', 'id')
                    ->orderBy('id');
    }

    // handle backwards compatibility for single attachment and multiple ones
    public function getAvailableAttachmentsAttribute() {
        $attachments = $this->attachmentFiles->map(function ($attachment) {
            return [
                'id' => $attachment->id,
                'path' => $attachment->file_path,
                'legacy' => false,
            ];
        });

        /*
         * for backwards compatibility sheeeesh
         */
        if ($this->attachment && !$attachments->contains('path', $this->attachment)) {
            $attachments->prepend([
                'id' => null,
                'path' => $this->attachment,
                'legacy' => true,
            ]);
        }

        return $attachments->values();
    }

}

