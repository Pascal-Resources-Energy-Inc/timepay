<?php

namespace App\Services\ExitInterview;

use App\ExitInterview;

class ExitInterviewReferenceService
{
    public function next()
    {
        $references = ExitInterview::where('reference_no', 'like', 'EEIQ-%')
            ->lockForUpdate()
            ->pluck('reference_no')
            ->all();

        $used = [];
        $highest = 0;

        foreach ($references as $reference) {
            if (!preg_match('/^EEIQ-(\d+)$/', strtoupper(trim((string) $reference)), $matches)) {
                continue;
            }

            $number = (int) $matches[1];
            $used[$number] = true;
            $highest = max($highest, $number);
        }

        do {
            $highest++;
        } while (isset($used[$highest]));

        return 'EEIQ-' . str_pad($highest, 6, '0', STR_PAD_LEFT);
    }
}
