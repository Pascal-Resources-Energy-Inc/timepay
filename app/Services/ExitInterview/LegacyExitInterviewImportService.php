<?php

namespace App\Services\ExitInterview;

use App\ExitInterview;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class LegacyExitInterviewImportService
{
    private $ratingFields = [
        'rating_job_responsibilities',
        'rating_personal_goals',
        'rating_work_environment',
        'rating_immediate_superior',
        'rating_salary',
        'rating_benefits',
    ];

    public function prepare(array $rows)
    {
        $records = [];
        $report = [];
        $stats = [
            'rows' => count($rows),
            'ready' => 0,
            'invalid' => 0,
            'unresolved' => 0,
        ];

        foreach ($rows as $row) {
            $mapped = $this->mapRecord($row);

            if (!empty($mapped['issues'])) {
                $stats['invalid']++;
                $report[] = $this->reportRow($row, 'invalid', implode('; ', $mapped['issues']));
                continue;
            }

            $records[] = $mapped['record'];
            $stats['ready']++;
            $report[] = $this->reportRow($row, 'ready', 'Ready to import');
        }

        return [
            'records' => $records,
            'report' => $report,
            'stats' => $stats,
        ];
    }

    public function commit(array $records, ExitInterviewReferenceService $references)
    {
        if (empty($records)) {
            return 0;
        }

        return DB::transaction(function () use ($records, $references) {
            $imported = 0;

            foreach ($records as $record) {
                $createdAt = $record['created_at'];
                $updatedAt = $record['updated_at'];
                unset($record['created_at'], $record['updated_at']);

                $record['reference_no'] = $references->next();

                $interview = new ExitInterview();
                $interview->fill($record);
                $interview->created_at = $createdAt;
                $interview->updated_at = $updatedAt;
                $interview->save();
                $imported++;
            }

            return $imported;
        });
    }

    private function mapRecord(array $row)
    {
        $issues = [];
        $submittedAt = $this->parseDate($row['submission_date'] ?? null);
        $hireDate = $this->parseDate($row['hire_date'] ?? null);
        $separationDate = $this->parseDate($row['separation_date'] ?? null);
        $updatedAt = $this->parseDate($row['last_update_date'] ?? null) ?: $submittedAt;
        $leavingForNewJob = $this->yesNo($row['is_leaving_for_new_job'] ?? null);
        $understandsGoals = $this->yesNo($row['understands_company_goals'] ?? null);
        $livesCoreValues = $this->yesNo($row['company_lives_core_values'] ?? null);
        $department = null;
        $supervisor = null;
        $workLocation = null;

        $this->requireValue($submittedAt, 'Invalid or missing submission date', $issues);
        $this->requireValue($hireDate, 'Invalid or missing hiring date', $issues);
        $this->requireValue($separationDate, 'Invalid or missing separation date', $issues);
        $this->requireValue($row['full_name'] ?? null, 'Missing full name', $issues);
        $this->requireValue($row['email'] ?? null, 'Missing email address', $issues);
        $this->requireValue($row['job_title'] ?? null, 'Missing role in the company', $issues);
        $this->requireValue($leavingForNewJob, 'Invalid new-job-opportunity answer', $issues, true);
        $this->requireValue($understandsGoals, 'Invalid department-goals answer', $issues, true);
        $this->requireValue($livesCoreValues, 'Invalid core-values answer', $issues, true);
        $this->requireValue($row['career_type_importance'] ?? null, 'Missing career opportunities', $issues);
        $this->requireValue($row['most_liked_text'] ?? null, 'Missing most-liked response', $issues);
        $this->requireValue($row['least_liked_text'] ?? null, 'Missing least-liked response', $issues);

        if (strtoupper(trim((string) ($row['status'] ?? ''))) !== 'APPROVED') {
            $issues[] = 'Legacy response is not approved';
        }

        if ($hireDate && $separationDate && $hireDate->gt($separationDate)) {
            $issues[] = 'Hiring date is after separation date';
        }

        $fullName = $this->nullableString($row['full_name'] ?? null);
        $email = $this->normalizeEmail($row['email'] ?? null);
        $jobTitle = $this->nullableString($row['job_title'] ?? null);
        $careerImportance = $this->normalizeList($row['career_type_importance'] ?? null);

        $this->checkLength($fullName, 255, 'Full name exceeds 255 characters', $issues);
        $this->checkLength($email, 255, 'Email exceeds 255 characters', $issues);
        $this->checkLength($jobTitle, 150, 'Role exceeds 150 characters', $issues);
        $this->checkLength($department, 150, 'Department exceeds 150 characters', $issues);
        $this->checkLength($supervisor, 255, 'Direct supervisor exceeds 255 characters', $issues);
        $this->checkLength($workLocation, 150, 'Work location exceeds 150 characters', $issues);
        $this->checkLength($careerImportance, 255, 'Career opportunities exceed 255 characters', $issues);

        $ratings = [];
        foreach ($this->ratingFields as $field) {
            $ratings[$field] = $this->rating($row[$field] ?? null, $field, $issues);
        }

        $frequency = $this->frequency($row['frequently_performance_feedback'] ?? null);
        $this->requireValue($frequency, 'Invalid or missing performance-feedback frequency', $issues);

        return [
            'issues' => $issues,
            'record' => array_merge([
                'legacy_reference_no' => $this->nullableString($row['legacy_reference_no'] ?? null),
                'first_name' => null,
                'last_name' => null,
                'full_name' => $fullName,
                'email' => $email,
                'job_title' => $jobTitle,
                'department' => $department,
                'direct_supervisor_name' => $supervisor,
                'work_location' => $workLocation,
                'hire_date' => $hireDate ? $hireDate->format('Y-m-d') : null,
                'separation_date' => $separationDate ? $separationDate->format('Y-m-d') : null,
                'is_separation_voluntary' => false,
                'separation_type' => null,
                'separation_comment' => null,
                'is_leaving_for_new_job' => $leavingForNewJob,
                'new_job_offer_text' => $this->nullableString($row['new_job_offer_text'] ?? null),
                'reason_of_leave' => $this->normalizeList($row['reason_of_leave'] ?? null),
                'reason_of_leave_other' => $this->nullableString($row['reason_of_leave_other'] ?? null),
                'career_type_importance' => $careerImportance,
                'most_liked_text' => $this->nullableString($row['most_liked_text'] ?? null),
                'least_liked_text' => $this->nullableString($row['least_liked_text'] ?? null),
                'understands_company_goals' => $understandsGoals,
                'company_lives_core_values' => $livesCoreValues,
                'goals_explanation' => $this->nullableString($row['goals_explanation'] ?? null),
                'core_values_explanation' => $this->nullableString($row['core_values_explanation'] ?? null),
                'frequently_performance_feedback' => $frequency,
                'suggestion_company' => $this->nullableString($row['suggestion_company'] ?? null),
                'suggestion_department' => null,
                'suggestion_other_employees' => null,
                'other_feedback_text' => $this->nullableString($row['other_feedback_text'] ?? null),
                'submitted_at' => $submittedAt ? $submittedAt->format('Y-m-d H:i:s') : null,
                'employee_id' => null,
                'user_id' => null,
                // Keep this null so multiple legacy submissions can be linked to
                // the same employee without violating the table's unique key.
                'employee_number' => null,
                'submission_source' => 'legacy_import',
                'created_at' => $submittedAt ? $submittedAt->format('Y-m-d H:i:s') : null,
                'updated_at' => $updatedAt ? $updatedAt->format('Y-m-d H:i:s') : null,
            ], $ratings),
        ];
    }

    private function parseDate($value)
    {
        if ($value === null || trim((string) $value) === '') {
            return null;
        }

        try {
            if (is_numeric($value)) {
                return Carbon::instance(ExcelDate::excelToDateTimeObject($value))->startOfDay();
            }

            return Carbon::parse($value)->startOfDay();
        } catch (\Throwable $exception) {
            return null;
        }
    }

    private function yesNo($value)
    {
        $value = strtoupper(trim((string) $value));

        if ($value === 'YES') {
            return true;
        }

        if ($value === 'NO') {
            return false;
        }

        return null;
    }

    private function rating($value, $field, array &$issues)
    {
        if ($value === null || trim((string) $value) === '') {
            return null;
        }

        if (!is_numeric($value) || (int) $value < 1 || (int) $value > 5) {
            $issues[] = 'Invalid rating for ' . str_replace('_', ' ', $field);
            return null;
        }

        return (int) $value;
    }

    private function frequency($value)
    {
        $value = trim((string) $value);
        $map = [
            'Frequently - Madalas' => 'Frequently - Madalas',
            'Occasionally - Minsan Minsan' => 'Occasionally - Minsan',
            'Occasionally - Minsan' => 'Occasionally - Minsan',
            'Rarely - Bihira' => 'Rarely - Bihira',
            'Never - Hindi Kahit Minsan' => 'Never - Hindi Kahit Minsan',
        ];

        return $map[$value] ?? null;
    }

    private function normalizeList($value)
    {
        $value = $this->nullableString($value);

        if ($value === null) {
            return null;
        }

        $items = preg_split('/[\r\n]+/', $value);
        $items = array_values(array_filter(array_map('trim', $items), function ($item) {
            return $item !== '';
        }));

        return implode(', ', $items);
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

    private function requireValue($value, $message, array &$issues, $allowFalse = false)
    {
        if ($value === null || (!$allowFalse && $value === '')) {
            $issues[] = $message;
        }
    }

    private function checkLength($value, $maximum, $message, array &$issues)
    {
        if ($value !== null && mb_strlen($value) > $maximum) {
            $issues[] = $message;
        }
    }

    private function reportRow(array $row, $result, $message)
    {
        return [
            'excel_row' => $row['excel_row'] ?? null,
            'legacy_reference_no' => $row['legacy_reference_no'] ?? null,
            'full_name' => $row['full_name'] ?? null,
            'email' => $row['email'] ?? null,
            'result' => $result,
            'message' => $message,
        ];
    }
}
