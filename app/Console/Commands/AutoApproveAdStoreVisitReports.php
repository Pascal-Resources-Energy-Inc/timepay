<?php

namespace App\Console\Commands;

use App\AdStoreVisitReport;
use App\Services\AdStoreVisitApprovalService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AutoApproveAdStoreVisitReports extends Command {
    protected $signature = 'ad-store-visits:auto-approve';

    protected $description = 'Approve AD Store Visit reports after their 24-hour approval ';

    public function handle(AdStoreVisitApprovalService $approvalService) {
        $approvedCount = 0;
        $failedCount = 0;

        AdStoreVisitReport::where(
            'approval_status',
            AdStoreVisitReport::APPROVAL_PENDING
        )
            ->whereNotNull('user_id')
            ->where('submitted_at', '<=', now()->subDay())
            ->orderBy('id')
            ->chunkById(100, function ($reports) use (
                $approvalService,
                &$approvedCount,
                &$failedCount
            ) {
                foreach ($reports as $report) {
                    try {
                        if ($approvalService->approve($report->id, true)) {
                            $approvedCount++;
                        }
                    } catch (\Throwable $exception) {
                        $failedCount++;

                        Log::error(
                            'AD Store Visit automatic approval failed.',
                            [
                                'report_id' => $report->id,
                                'message' => $exception->getMessage(),
                            ]
                        );
                    }
                }
            });

        $this->info(
            "Automatically approved {$approvedCount} AD Store Visit report(s)."
        );

        if ($failedCount > 0) {
            $this->error(
                "Failed to approve {$failedCount} AD Store Visit report(s)."
            );
        }

        return $failedCount > 0 ? 1 : 0;
    }
}
