<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class ImportEmployeeCoesFromJotform extends Command
{
    protected $signature = 'coe:import-jotform
        {file : Full path to the JotForm workbook}
        {--dry-run : Preview the import without writing records}
        {--commit : Write valid records to employee_coes}';

    protected $description = 'Import resolved historical COE requests from the JotForm workbook';

    private $requiredHeaders = [
        'NALD',
        'Last Update Date',
        'COE Request',
        'Employment Status',
        'Hiring Date',
        'Separation Date',
        'First Name',
        'Last Name',
        'Designation',
        'Salary',
        'Purpose for COE Request',
        'I would like to receive my COE thru:',
        'Phone Number',
        'Email',
        'Additional Notes, if any',
        'Remarks',
        'DATE OF ISSUANCE',
    ];

    public function handle()
    {
        $path = $this->argument('file');
        $commit = (bool) $this->option('commit');

        if ($commit && $this->option('dry-run')) {
            $this->error('Use either --dry-run or --commit, not both.');
            return 1;
        }

        if (!file_exists($path)) {
            $this->error('File not found: ' . $path);
            return 1;
        }

        $hasLegacyColumn = Schema::hasColumn('employee_coes', 'legacy_reference_number');

        if ($commit && !$hasLegacyColumn) {
            $this->error('The employee_coes.legacy_reference_number column is required before committing the import.');
            return 1;
        }

        if (!$hasLegacyColumn) {
            $this->warn('legacy_reference_number is not present. Dry-run will continue, but --commit is unavailable.');
        }

        try {
            $spreadsheet = IOFactory::load($path);
        } catch (\Throwable $e) {
            $this->error('Unable to open workbook: ' . $e->getMessage());
            return 1;
        }

        $sheet = $spreadsheet->getSheetByName('Form Responses');

        if (!$sheet) {
            $this->error('Worksheet "Form Responses" was not found.');
            return 1;
        }

        $rows = $sheet->toArray(null, true, false, false);

        if (count($rows) < 2) {
            $this->error('The Form Responses worksheet does not contain any data rows.');
            return 1;
        }

        $headers = $this->headerMap($rows[0]);
        $missingHeaders = array_values(array_filter($this->requiredHeaders, function ($header) use ($headers) {
            return !array_key_exists($header, $headers);
        }));

        if (!empty($missingHeaders)) {
            $this->error('Missing required columns: ' . implode(', ', $missingHeaders));
            return 1;
        }

        $existingLegacyReferences = [];
        if ($hasLegacyColumn) {
            $existingLegacyReferences = array_fill_keys(
                DB::table('employee_coes')
                    ->whereNotNull('legacy_reference_number')
                    ->pluck('legacy_reference_number')
                    ->map(function ($reference) {
                        return trim((string) $reference);
                    })
                    ->all(),
                true
            );
        }

        $reservedReferences = array_fill_keys(
            DB::table('employee_coes')
                ->whereNotNull('reference_number')
                ->pluck('reference_number')
                ->map(function ($reference) {
                    return trim((string) $reference);
                })
                ->all(),
            true
        );

        $currentNumber = $this->currentReferenceNumber();
        $stats = [
            'rows' => 0,
            'ready' => 0,
            'imported' => 0,
            'unresolved' => 0,
            'invalid' => 0,
            'duplicates' => 0,
            'failed' => 0,
            'salary_omitted' => 0,
        ];
        $reportRows = [];

        foreach (array_slice($rows, 1) as $index => $row) {
            $excelRow = $index + 2;

            if ($this->rowIsEmpty($row)) {
                continue;
            }

            $stats['rows']++;
            $mapped = $this->mapRow($row, $headers);
            $legacyReference = $mapped['legacy_reference'];

            if ($mapped['salary_omitted']) {
                $stats['salary_omitted']++;
            }

            if ($mapped['unresolved']) {
                $stats['unresolved']++;
                $reportRows[] = $this->reportRow(
                    $excelRow,
                    $legacyReference,
                    null,
                    null,
                    'skipped',
                    'No reliable final status'
                );
                continue;
            }

            if (!empty($mapped['issues'])) {
                $stats['invalid']++;
                $reportRows[] = $this->reportRow(
                    $excelRow,
                    $legacyReference,
                    null,
                    $mapped['data']['status'] ?? null,
                    'invalid',
                    implode('; ', $mapped['issues'])
                );
                continue;
            }

            if ($legacyReference !== null && isset($existingLegacyReferences[$legacyReference])) {
                $stats['duplicates']++;
                $reportRows[] = $this->reportRow(
                    $excelRow,
                    $legacyReference,
                    null,
                    $mapped['data']['status'],
                    'skipped',
                    'Legacy reference already imported'
                );
                continue;
            }

            $newReference = $this->nextReferenceNumber($currentNumber, $reservedReferences);
            $mapped['data']['reference_number'] = $newReference;
            $stats['ready']++;

            if (!$commit) {
                $reportRows[] = $this->reportRow(
                    $excelRow,
                    $legacyReference,
                    $newReference,
                    $mapped['data']['status'],
                    'ready',
                    $mapped['salary_omitted'] ? 'Salary value omitted because there is no target column' : ''
                );
                continue;
            }

            try {
                DB::transaction(function () use ($mapped) {
                    DB::table('employee_coes')->insert($mapped['data']);
                });

                if ($legacyReference !== null) {
                    $existingLegacyReferences[$legacyReference] = true;
                }

                $stats['imported']++;
                $reportRows[] = $this->reportRow(
                    $excelRow,
                    $legacyReference,
                    $newReference,
                    $mapped['data']['status'],
                    'imported',
                    $mapped['salary_omitted'] ? 'Salary value omitted because there is no target column' : ''
                );
            } catch (\Throwable $e) {
                $stats['failed']++;
                $reportRows[] = $this->reportRow(
                    $excelRow,
                    $legacyReference,
                    $newReference,
                    $mapped['data']['status'],
                    'failed',
                    $e->getMessage()
                );
            }
        }

        $reportPath = $this->writeReport($reportRows, $commit ? 'commit' : 'dry-run');

        $this->line('');
        $this->info($commit ? 'COE import completed.' : 'COE import dry-run completed. No records were written.');
        $this->table(
            ['Rows', 'Ready', 'Imported', 'Unresolved', 'Invalid', 'Duplicates', 'Failed', 'Salary omitted'],
            [[
                $stats['rows'],
                $stats['ready'],
                $stats['imported'],
                $stats['unresolved'],
                $stats['invalid'],
                $stats['duplicates'],
                $stats['failed'],
                $stats['salary_omitted'],
            ]]
        );
        $this->line('Report: ' . $reportPath);

        return $stats['failed'] > 0 ? 1 : 0;
    }

    private function mapRow(array $row, array $headers)
    {
        $legacyReference = $this->resolveLegacyReference(
            $this->cell($row, $headers, 'Last Update Date')
        );
        $remarks = $this->cell($row, $headers, 'Remarks');
        $issuanceValue = $this->rawCell($row, $headers, 'DATE OF ISSUANCE');
        $approvedDate = $this->parseDate($issuanceValue);
        $status = 'From Import';
        $salaryOmitted = $this->cell($row, $headers, 'Salary') !== null;

        $createdAt = $this->parseDate($this->rawCell($row, $headers, 'NALD'));
        $hiringDate = $this->parseDate($this->rawCell($row, $headers, 'Hiring Date'));
        $resignDate = $this->parseDate($this->rawCell($row, $headers, 'Separation Date'));
        $firstName = $this->cell($row, $headers, 'First Name');
        $lastName = $this->cell($row, $headers, 'Last Name');
        $designation = $this->cell($row, $headers, 'Designation');
        $purpose = $this->cell($row, $headers, 'Purpose for COE Request');
        $email = $this->cell($row, $headers, 'Email');
        $additionalNotes = $this->cell($row, $headers, 'Additional Notes, if any');
        $reasonForRequest = $this->normalizeRequestType($this->cell($row, $headers, 'COE Request'));
        $employmentStatus = $this->normalizeEmploymentStatus($this->cell($row, $headers, 'Employment Status'));
        $receiveMethod = $this->normalizeReceiveMethod($this->cell($row, $headers, 'I would like to receive my COE thru:'));
        $rawPhone = $this->cell($row, $headers, 'Phone Number');
        $phone = $this->normalizePhone($rawPhone);
        $issues = [];

        if ($purpose !== null && mb_strlen($purpose) > 255) {
            $purposeContinuation = 'Purpose continued: ' . ltrim(mb_substr($purpose, 255));
            $purpose = mb_substr($purpose, 0, 255);
            $additionalNotes = $additionalNotes
                ? $additionalNotes . ' ' . $purposeContinuation
                : $purposeContinuation;
        }

        $this->requireValue($createdAt, 'Invalid submission date', $issues);
        $this->requireValue($firstName, 'Missing first name', $issues);
        $this->requireValue($lastName, 'Missing last name', $issues);
        $this->requireValue($reasonForRequest, 'Invalid COE request type', $issues);
        $this->requireValue($employmentStatus, 'Invalid employment status', $issues);
        $this->requireValue($hiringDate, 'Invalid hiring date', $issues);
        $this->requireValue($designation, 'Missing designation', $issues);
        $this->requireValue($purpose, 'Missing purpose', $issues);
        $this->requireValue($receiveMethod, 'Invalid delivery method', $issues);
        $this->requireValue($email, 'Missing email', $issues);

        if ($employmentStatus === 'Separated' && !$resignDate) {
            $issues[] = 'Invalid or missing separation date';
        }

        if ($email !== null && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $issues[] = 'Invalid email address';
        }

        $this->checkLength($legacyReference, 50, 'Legacy reference exceeds 50 characters', $issues);
        $this->checkLength($firstName, 255, 'First name exceeds 255 characters', $issues);
        $this->checkLength($lastName, 255, 'Last name exceeds 255 characters', $issues);
        $this->checkLength($designation, 255, 'Designation exceeds 255 characters', $issues);
        $this->checkLength($purpose, 255, 'Purpose exceeds 255 characters', $issues);
        $this->checkLength($email, 255, 'Email exceeds 255 characters', $issues);
        $this->checkLength($additionalNotes, 255, 'Additional notes exceed 255 characters', $issues);
        $this->checkLength($remarks, 255, 'Remarks exceed 255 characters', $issues);

        $issuanceRemarks = null;
        if (is_string($issuanceValue)) {
            $cleanIssuance = trim($issuanceValue);
            if ($cleanIssuance !== '' && strpos($cleanIssuance, '#') !== 0) {
                $issuanceRemarks = $cleanIssuance;
            }
        }

        $createdAtValue = $createdAt ? $createdAt->format('Y-m-d H:i:s') : null;
        $updatedAt = $approvedDate && $createdAt && $approvedDate->gt($createdAt)
            ? $approvedDate
            : $createdAt;

        return [
            'legacy_reference' => $legacyReference,
            'unresolved' => false,
            'issues' => $issues,
            'salary_omitted' => $salaryOmitted,
            'data' => [
                'reference_number' => null,
                'legacy_reference_number' => $legacyReference,
                'user_id' => null,
                'applied_date' => $createdAt ? $createdAt->format('Y-m-d') : null,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'gender' => null,
                'marital_status' => null,
                'reason_for_request' => $reasonForRequest,
                'purpose' => $purpose,
                'employment_status' => $employmentStatus,
                'hiring_date' => $hiringDate ? $hiringDate->format('Y-m-d') : null,
                'resign_date' => $resignDate ? $resignDate->format('Y-m-d') : null,
                'designation' => $designation,
                'email' => $email,
                'viber_number' => $receiveMethod === 'Viber' ? ($phone ?: $rawPhone) : null,
                'receive_method' => $receiveMethod,
                'additional_notes' => $additionalNotes,
                'attachment' => null,
                'attachment_remarks' => null,
                'proof' => null,
                'issuance_remarks' => $issuanceRemarks,
                'status' => $status,
                'approvers' => null,
                'approved_by' => null,
                'approval_remarks' => $remarks,
                'approved_date' => $approvedDate ? $approvedDate->format('Y-m-d H:i:s') : null,
                'level' => 0,
                'schedule_id' => null,
                'created_by' => null,
                'created_at' => $createdAtValue,
                'updated_at' => $updatedAt ? $updatedAt->format('Y-m-d H:i:s') : $createdAtValue,
                'processed_at' => null,
            ],
        ];
    }

    private function resolveLegacyReference($lastUpdateDate)
    {
        if (preg_match('/COE-(?:REQ|RE)-\d{4}-\d+/i', (string) $lastUpdateDate, $matches)) {
            return strtoupper($matches[0]);
        }

        return null;
    }

    private function resolveStatus($remarks, $issuanceValue, $approvedDate)
    {
        $normalizedRemarks = strtolower((string) $remarks);

        if (strpos($normalizedRemarks, 'reject') !== false) {
            return 'Declined';
        }

        if ($approvedDate) {
            return 'Approved';
        }

        if (is_string($issuanceValue)) {
            $issuanceText = trim($issuanceValue);
            if ($issuanceText !== '' && strpos($issuanceText, '#') !== 0 && !is_numeric($issuanceText)) {
                return 'Approved';
            }
        }

        return null;
    }

    private function parseDate($value)
    {
        if ($value instanceof \DateTimeInterface) {
            return Carbon::instance(\DateTime::createFromFormat('Y-m-d H:i:s', $value->format('Y-m-d H:i:s')));
        }

        if (is_numeric($value)) {
            $numericValue = (float) $value;
            if ($numericValue <= 0 || $numericValue > 2958465) {
                return null;
            }

            try {
                return Carbon::instance(ExcelDate::excelToDateTimeObject($numericValue));
            } catch (\Throwable $e) {
                return null;
            }
        }

        if (!is_string($value)) {
            return null;
        }

        $value = trim($value);
        if ($value === '' || strpos($value, '#') === 0) {
            return null;
        }

        $formats = [
            'd-m-Y H:i:s',
            'd-m-Y H:i',
            'd-m-Y',
            'd/m/Y H:i:s',
            'd/m/Y',
            'Y-m-d H:i:s',
            'Y-m-d',
        ];

        foreach ($formats as $format) {
            $date = \DateTime::createFromFormat('!' . $format, $value);
            $errors = \DateTime::getLastErrors();

            if ($date && ($errors === false || ($errors['warning_count'] === 0 && $errors['error_count'] === 0))) {
                return Carbon::instance($date);
            }
        }

        if (preg_match('/[A-Za-z]{3,9}\.?(?:\s+)\d{1,2},(?:\s+)\d{4}/', $value, $matches)) {
            try {
                return Carbon::parse(str_replace('Sept.', 'Sep', $matches[0]));
            } catch (\Throwable $e) {
                return null;
            }
        }

        return null;
    }

    private function normalizeRequestType($value)
    {
        $value = strtolower((string) $value);

        if ($value === 'plain') {
            return 'Plain';
        }

        if (strpos($value, 'with salary') !== false) {
            return 'With Salary';
        }

        return null;
    }

    private function normalizeEmploymentStatus($value)
    {
        $value = strtolower((string) $value);

        if (strpos($value, 'active') !== false) {
            return 'Active';
        }

        if (strpos($value, 'separated') !== false || strpos($value, 'former') !== false) {
            return 'Separated';
        }

        return null;
    }

    private function normalizeReceiveMethod($value)
    {
        $value = strtolower((string) $value);

        if ($value === 'email') {
            return 'Email';
        }

        if ($value === 'viber') {
            return 'Viber';
        }

        return null;
    }

    private function normalizePhone($value)
    {
        if ($value === null) {
            return null;
        }

        $digits = preg_replace('/\D/', '', (string) $value);

        if (strlen($digits) === 10 && strpos($digits, '9') === 0) {
            return '0' . $digits;
        }

        if (strlen($digits) === 11 && strpos($digits, '09') === 0) {
            return $digits;
        }

        return null;
    }

    private function currentReferenceNumber()
    {
        $result = DB::table('employee_coes')
            ->whereRaw("reference_number REGEXP '^COE-[0-9]+$'")
            ->selectRaw('MAX(CAST(SUBSTRING(reference_number, 5) AS UNSIGNED)) AS max_number')
            ->first();

        return $result && $result->max_number !== null ? (int) $result->max_number : 0;
    }

    private function nextReferenceNumber(&$currentNumber, array &$reservedReferences)
    {
        do {
            $currentNumber++;
            $reference = 'COE-' . str_pad($currentNumber, 5, '0', STR_PAD_LEFT);
        } while (isset($reservedReferences[$reference]));

        $reservedReferences[$reference] = true;
        return $reference;
    }

    private function headerMap(array $headerRow)
    {
        $headers = [];

        foreach ($headerRow as $index => $value) {
            $header = trim((string) $value);
            if ($header !== '') {
                $headers[$header] = $index;
            }
        }

        return $headers;
    }

    private function cell(array $row, array $headers, $header)
    {
        $value = $this->rawCell($row, $headers, $header);

        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);
        return $value === '' ? null : $value;
    }

    private function rawCell(array $row, array $headers, $header)
    {
        if (!array_key_exists($header, $headers)) {
            return null;
        }

        return $row[$headers[$header]] ?? null;
    }

    private function rowIsEmpty(array $row)
    {
        foreach ($row as $value) {
            if ($value !== null && trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }

    private function requireValue($value, $message, array &$issues)
    {
        if ($value === null || $value === '') {
            $issues[] = $message;
        }
    }

    private function checkLength($value, $maximum, $message, array &$issues)
    {
        if ($value !== null && mb_strlen((string) $value) > $maximum) {
            $issues[] = $message;
        }
    }

    private function reportRow($excelRow, $legacyReference, $newReference, $status, $result, $message)
    {
        return [
            $excelRow,
            $legacyReference,
            $newReference,
            $status,
            $result,
            $message,
        ];
    }

    private function writeReport(array $rows, $mode)
    {
        $directory = storage_path('app/coe-import-reports');
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $path = $directory . DIRECTORY_SEPARATOR
            . 'coe-import-' . $mode . '-' . date('Ymd-His') . '-' . uniqid() . '.csv';
        $handle = fopen($path, 'w');

        fputcsv($handle, ['Excel row', 'Legacy reference', 'New reference', 'Status', 'Result', 'Message']);
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);
        return $path;
    }
}
