<?php

namespace App\Services\LayoutDesign;

use App\LayoutDesign;
use App\LayoutDesignAttachment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class LegacyLayoutDesignImportService
{
    public function prepare(array $rows, array $existingReferences = null)
    {
        $referenceState = $this->referenceState($existingReferences);
        $records = [];
        $report = [];
        $stats = [
            'rows' => 0,
            'ready' => 0,
            'imported' => 0,
            'invalid' => 0,
            'duplicates' => 0,
            'attachments' => 0,
            'failed' => 0,
        ];

        foreach ($rows as $row) {
            $stats['rows']++;
            $reference = $this->normalizeReference($row['reference_no'] ?? null);

            if ($reference === null) {
                $stats['invalid']++;
                $report[] = $this->reportRow($row, null, 'invalid', 'Invalid or missing LDR reference');
                continue;
            }

            $referenceKey = $this->referenceKey($reference);

            if (isset($referenceState[$referenceKey])) {
                $stats['duplicates']++;
                $report[] = $this->reportRow(
                    $row,
                    $reference,
                    'duplicate',
                    'A Layout Design request with this reference already exists or is repeated in the workbook'
                );
                continue;
            }

            $mapped = $this->mapRecord($row, $reference);

            if (!empty($mapped['issues'])) {
                $stats['invalid']++;
                $report[] = $this->reportRow(
                    $row,
                    $reference,
                    'invalid',
                    implode('; ', $mapped['issues'])
                );
                continue;
            }

            $referenceState[$referenceKey] = true;
            $records[] = $mapped;
            $stats['ready']++;
            $stats['attachments'] += count($mapped['attachments']);
            $report[] = $this->reportRow(
                $row,
                $reference,
                'ready',
                count($mapped['attachments']) . ' peg sample link(s) ready'
            );
        }

        return [
            'records' => $records,
            'report' => $report,
            'stats' => $stats,
        ];
    }

    public function commit(array $records)
    {
        if (empty($records)) {
            return 0;
        }

        return DB::transaction(function () use ($records) {
            $imported = 0;

            foreach ($records as $mapped) {
                $requestId = DB::table('layout_design_requests')->insertGetId($mapped['record']);
                $attachmentTimestamp = $mapped['record']['created_at'];

                foreach ($mapped['attachments'] as $url) {
                    DB::table('layout_design_attachments')->insert([
                        'layout_design_request_id' => $requestId,
                        'output_attachment_path' => $url,
                        'original_name' => 'Imported peg sample link',
                        'mime_type' => LayoutDesignAttachment::PEG_SAMPLE_LINK_MIME_TYPE,
                        'file_size' => null,
                        'created_at' => $attachmentTimestamp,
                        'updated_at' => $attachmentTimestamp,
                    ]);
                }

                $imported++;
            }

            return $imported;
        });
    }

    private function mapRecord(array $row, $reference)
    {
        $issues = [];
        $submissionDate = $this->parseDate($row['submission_date'] ?? null);
        $dateRequested = $this->parseDate($row['date_requested'] ?? null);
        $dateNeeded = $this->parseDate($row['date_needed'] ?? null);
        $lastUpdateDate = $this->parseDate($row['last_update_date'] ?? null);
        $fullName = $this->nullableString($row['full_name'] ?? null);
        $department = $this->nullableString($row['department'] ?? null);
        $email = $this->normalizeEmail($row['requestor_email'] ?? null);
        $intendedUse = $this->normalizeList($row['intended_use'] ?? null);
        $mediumType = $this->nullableString($row['medium_type'] ?? null);
        $collateralTypes = $this->normalizeList($row['marketing_collateral_type'] ?? null);
        $purpose = $this->nullableString($row['purpose_of_layout_request'] ?? null);
        $description = $this->nullableString($row['project_description'] ?? null);
        $attachments = $this->normalizePegSampleLinks($row['peg_sample'] ?? null, $issues);

        $this->requireValue($submissionDate, 'Invalid or missing submission date', $issues);
        $this->requireValue($dateRequested, 'Invalid or missing date requested', $issues);
        $this->requireValue($fullName, 'Missing requestor name', $issues);
        $this->requireValue($department, 'Missing department', $issues);
        $this->requireValue($email, 'Invalid or missing requestor email', $issues);
        $this->requireValue($intendedUse, 'Missing intended use', $issues);
        $this->requireValue($mediumType, 'Missing medium type', $issues);
        $this->requireValue($collateralTypes, 'Missing marketing collateral type', $issues);
        $this->requireValue($purpose, 'Missing purpose of layout request', $issues);
        $this->requireValue($description, 'Missing project description', $issues);

        if ($this->nullableString($row['date_needed'] ?? null) !== null && $dateNeeded === null) {
            $issues[] = 'Invalid date needed';
        }

        if ($this->nullableString($row['last_update_date'] ?? null) !== null && $lastUpdateDate === null) {
            $issues[] = 'Invalid last update date';
        }

        if ($email !== null && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $issues[] = 'Invalid requestor email';
        }

        $this->validateSelections($intendedUse, LayoutDesign::INTENDED_USES, 'intended use', $issues);
        $this->validateSelections(
            $collateralTypes,
            array_merge(LayoutDesign::COLLATERAL_TYPES, LayoutDesign::STORE_SIGNAGE_OPTIONS),
            'marketing collateral type',
            $issues
        );

        if ($mediumType !== null && !in_array($mediumType, LayoutDesign::MEDIUM_TYPES, true)) {
            $issues[] = 'Invalid medium type: ' . $mediumType;
        }

        $this->checkLength($reference, 255, 'Reference exceeds 255 characters', $issues);
        $this->checkLength($fullName, 255, 'Requestor name exceeds 255 characters', $issues);
        $this->checkLength($department, 100, 'Department exceeds 100 characters', $issues);
        $this->checkLength($email, 255, 'Requestor email exceeds 255 characters', $issues);
        $this->checkLength($mediumType, 50, 'Medium type exceeds 50 characters', $issues);

        $createdAt = $submissionDate ? $submissionDate->format('Y-m-d H:i:s') : null;
        $updatedAt = $lastUpdateDate
            ? $lastUpdateDate->format('Y-m-d H:i:s')
            : $createdAt;

        return [
            'issues' => $issues,
            'record' => [
                'reference_no' => $reference,
                'date_requested' => $dateRequested ? $dateRequested->format('Y-m-d H:i:s') : null,
                'requestor_first_name' => null,
                'requestor_last_name' => null,
                'full_name' => $fullName,
                'department' => $department,
                'requestor_email' => $email,
                'intended_use' => json_encode($intendedUse),
                'medium_type' => $mediumType,
                'marketing_collateral_type' => json_encode($collateralTypes),
                'purpose_of_layout_request' => $purpose,
                'project_description' => $description,
                'priority_level' => null,
                'peg_sample_path' => null,
                'date_needed' => $dateNeeded ? $dateNeeded->startOfDay()->format('Y-m-d H:i:s') : null,
                'due_date' => null,
                'status' => LayoutDesign::STATUS_FROM_IMPORT,
                'user_id' => null,
                'submitted_at' => $createdAt,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ],
            'attachments' => $attachments,
        ];
    }

    private function referenceState(array $existingReferences = null)
    {
        if ($existingReferences === null) {
            $existingReferences = DB::table('layout_design_requests')
                ->whereNotNull('reference_no')
                ->pluck('reference_no')
                ->all();
        }

        $state = [];

        foreach ($existingReferences as $reference) {
            $key = $this->referenceKey($reference);

            if ($key !== null) {
                $state[$key] = true;
            }
        }

        return $state;
    }

    private function normalizeReference($value)
    {
        $value = strtoupper(trim((string) $value));

        if (!preg_match('/^LDR\s*-\s*0*(\d+)$/', $value, $matches)) {
            return null;
        }

        $number = (int) $matches[1];

        if ($number < 1) {
            return null;
        }

        return 'LDR - ' . str_pad((string) $number, 4, '0', STR_PAD_LEFT);
    }

    private function referenceKey($value)
    {
        if (!preg_match('/^LDR\s*-\s*0*(\d+)$/i', trim((string) $value), $matches)) {
            return null;
        }

        return 'ldr:' . (int) $matches[1];
    }

    private function parseDate($value)
    {
        if ($value instanceof \DateTimeInterface) {
            return Carbon::instance(
                \DateTime::createFromFormat('Y-m-d H:i:s', $value->format('Y-m-d H:i:s'))
            );
        }

        if (is_numeric($value)) {
            try {
                return Carbon::instance(ExcelDate::excelToDateTimeObject((float) $value));
            } catch (\Throwable $exception) {
                return null;
            }
        }

        if (!is_string($value) || trim($value) === '') {
            return null;
        }

        try {
            return Carbon::parse(trim($value));
        } catch (\Throwable $exception) {
            return null;
        }
    }

    private function normalizeList($value)
    {
        $value = $this->nullableString($value);

        if ($value === null) {
            return [];
        }

        return array_values(array_unique(array_filter(array_map('trim', preg_split('/[\r\n]+/', $value)), function ($item) {
            return $item !== '';
        })));
    }

    private function normalizePegSampleLinks($value, array &$issues)
    {
        $links = $this->normalizeList($value);

        foreach ($links as $link) {
            if (mb_strlen($link) > 255) {
                $issues[] = 'Peg sample link exceeds 255 characters';
                continue;
            }

            if (!filter_var($link, FILTER_VALIDATE_URL)
                || !preg_match('/^https?:\/\//i', $link)) {
                $issues[] = 'Invalid peg sample link: ' . $link;
            }
        }

        return $links;
    }

    private function validateSelections(array $values, array $allowed, $label, array &$issues)
    {
        foreach ($values as $value) {
            if (!in_array($value, $allowed, true)) {
                $issues[] = 'Invalid ' . $label . ': ' . $value;
            }
        }
    }

    private function normalizeEmail($value)
    {
        $value = $this->nullableString($value);
        return $value === null ? null : strtolower($value);
    }

    private function nullableString($value)
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);
        return $value === '' ? null : $value;
    }

    private function requireValue($value, $message, array &$issues)
    {
        if ($value === null || $value === '' || $value === []) {
            $issues[] = $message;
        }
    }

    private function checkLength($value, $maximum, $message, array &$issues)
    {
        if ($value !== null && mb_strlen((string) $value) > $maximum) {
            $issues[] = $message;
        }
    }

    private function reportRow(array $row, $reference, $result, $message)
    {
        return [
            'excel_row' => $row['excel_row'] ?? null,
            'reference_no' => $reference ?: ($row['reference_no'] ?? null),
            'full_name' => $row['full_name'] ?? null,
            'department' => $row['department'] ?? null,
            'result' => $result,
            'message' => $message,
        ];
    }
}
