<?php

namespace App\Services;

use App\AdStoreVisitReport;
use App\Mail\AdStoreVisitMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AdStoreVisitApprovalService {
    // mancom, ad secretariat, pod
    // 'francis.ramos@pascalresources.com.ph'
    private const RECIPIENTS = [
        'siuping.par@pascalresources.com.ph',
        'nelson.par@pascalresources.com.ph',
        'matthew.par@pascalresources.com.ph',
        'nathaniel.camat@pascalresources.com.ph',
        'elmer.binuya@pascalresources.com.ph',
        'dennis.villareal@pascalresources.com.ph',
        'rey.david@pascalresources.com.ph',
        'maricel.solis@pascalresources.com.ph',
        'sharon.santos@pascalresources.com.ph',

        'jhona.rosario@pascalresources.com.ph',

        'jadd.tampingco@pascalresources.com.ph',
        'alden.monilla@pascalresources.com.ph',
    ];

    public function approve($reportId, $automatic = false) {
        return DB::transaction(function () use ($reportId, $automatic) {
            $report = AdStoreVisitReport::where('id', $reportId)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                $report->user_id === null ||
                !$report->isApprovalPending()
            ) {
                return false;
            }

            $report->status = true;
            $report->approval_status = AdStoreVisitReport::APPROVAL_APPROVED;
            $report->disapproval_reason = null;
            $report->responded_at = now();

            Mail::to(self::RECIPIENTS)->send(
                new AdStoreVisitMail(
                    $report,
                    'approved',
                    [
                        'approval_recipient' => $this->approvalRecipient($report),
                        'automatic_approval' => $automatic,
                    ]
                )
            );

            $report->save();

            return true;
        });
    }

    public function disapprove($reportId, $reason) {
        return DB::transaction(function () use ($reportId, $reason) {
            $report = AdStoreVisitReport::where('id', $reportId)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                $report->user_id === null ||
                !$report->isApprovalPending()
            ) {
                return false;
            }

            $report->status = false;
            $report->approval_status = AdStoreVisitReport::APPROVAL_DISAPPROVED;
            $report->disapproval_reason = trim($reason);
            $report->responded_at = now();

            Mail::to(self::RECIPIENTS)->send(
                new AdStoreVisitMail($report, 'disapproved')
            );

            $report->save();

            return true;
        });
    }
    private function approvalRecipient(AdStoreVisitReport $report) {
        return $report->area_distributor_email
            ?: $report->representative_email;
    }
}
