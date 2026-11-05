<?php

namespace App\Services\Iur;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class LegacyIurImportService
{
    public function prepare(array $rows)
    {
        $employeeMaps = $this->employeeMaps();
        $referenceState = $this->referenceState();
        $records = [];
        $report = [];
        $stats = [
            'rows' => 0,
            'ready' => 0,
            'imported' => 0,
            'invalid' => 0,
            'unresolved' => 0,
            'conflicts' => 0,
            'duplicates' => 0,
            'failed' => 0,
        ];

        foreach ($rows as $row) {
            $stats['rows']++;
            $legacyReference = trim((string) ($row['legacy_reference'] ?? ''));
            $legacyReference = $legacyReference === '' ? null : strtoupper($legacyReference);

            if ($legacyReference !== null && !preg_match('/^UIR-\d+$/', $legacyReference)) {
                $stats['invalid']++;
                $report[] = $this->reportRow($row, $legacyReference, null, null, null, 'invalid', 'Invalid legacy reference');
                continue;
            }

            $resolution = $this->resolveUser($row, $employeeMaps);
            $userId = $resolution['status'] === 'resolved' ? $resolution['user_id'] : null;
            $resolutionMethod = $resolution['status'] === 'resolved' ? $resolution['method'] : null;
            $resolutionMessage = $resolution['status'] === 'resolved'
                ? ''
                : $resolution['message'] . '; imported with null user_id';

            $mapped = $this->mapRecord($row, null, $legacyReference, $userId);

            if (!empty($mapped['issues'])) {
                $stats['invalid']++;
                $report[] = $this->reportRow(
                    $row,
                    $legacyReference,
                    null,
                    $userId,
                    $resolutionMethod,
                    'invalid',
                    implode('; ', $mapped['issues'])
                );
                continue;
            }

            $iurReference = $this->nextReferenceNumber($referenceState);
            $mapped['record']['iur_reference'] = $iurReference;
            $records[] = $mapped['record'];
            $stats['ready']++;
            $report[] = $this->reportRow(
                $row,
                $legacyReference,
                $iurReference,
                $userId,
                $resolutionMethod,
                'ready',
                $resolutionMessage
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
            return;
        }

        DB::transaction(function () use ($records) {
            foreach ($records as $record) {
                DB::table('employee_iur')->insert($record);
            }
        });
    }

    private function employeeMaps()
    {
        $maps = [
            'employee_number' => [],
            'employee_code' => [],
        ];

        $employees = DB::table('employees')
            ->whereNotNull('user_id')
            ->select('user_id', 'employee_number', 'employee_code')
            ->get();

        foreach ($employees as $employee) {
            $this->addToMap($maps['employee_number'], $this->normalizeIdentifier($employee->employee_number), $employee->user_id);
            $this->addToMap($maps['employee_code'], $this->normalizeIdentifier($employee->employee_code), $employee->user_id);
        }

        return $maps;
    }

    private function referenceState()
    {
        $references = DB::table('employee_iur')
            ->whereNotNull('iur_reference')
            ->pluck('iur_reference')
            ->map(function ($reference) {
                return strtoupper(trim((string) $reference));
            })
            ->all();

        $reserved = [];
        $currentNumber = 0;

        foreach ($references as $reference) {
            $reserved[$reference] = true;

            if (preg_match('/^UIR-(\d+)$/', $reference, $matches)) {
                $currentNumber = max($currentNumber, (int) $matches[1]);
            }
        }

        return [
            'reserved' => $reserved,
            'current_number' => $currentNumber,
        ];
    }

    private function nextReferenceNumber(array &$referenceState)
    {
        do {
            $referenceState['current_number']++;
            $reference = 'UIR-' . str_pad($referenceState['current_number'], 5, '0', STR_PAD_LEFT);
        } while (isset($referenceState['reserved'][$reference]));

        $referenceState['reserved'][$reference] = true;
        return $reference;
    }

    private function resolveUser(array $row, array $maps)
    {
        $identifier = $this->normalizeIdentifier($row['employee_number'] ?? null);
        $candidateGroups = [
            'employee number' => $identifier ? ($maps['employee_number'][$identifier] ?? []) : [],
            'employee code' => $identifier ? ($maps['employee_code'][$identifier] ?? []) : [],
        ];
        $allCandidates = [];

        foreach ($candidateGroups as $candidates) {
            foreach ($candidates as $userId) {
                $allCandidates[$userId] = true;
            }
        }

        if (count($allCandidates) > 1) {
            return [
                'status' => 'conflict',
                'message' => 'Employee number and employee code resolve to different users',
            ];
        }

        if (count($allCandidates) === 0) {
            return [
                'status' => 'unresolved',
                'message' => 'No employee matched the employee number or employee code',
            ];
        }

        $userId = (int) array_keys($allCandidates)[0];
        $method = 'employee number';

        if (in_array($userId, $candidateGroups['employee number'], true)) {
            $method = 'employee number';
        } elseif (in_array($userId, $candidateGroups['employee code'], true)) {
            $method = 'employee code';
        }

        return [
            'status' => 'resolved',
            'user_id' => $userId,
            'method' => $method,
        ];
    }

    private function mapRecord(array $row, $iurReference, $legacyReference, $userId)
    {
        $issues = [];
        $type = $this->normalizeType($row['type'] ?? null);
        $workLocation = $this->normalizeWorkLocation($row['work_location'] ?? null);
        $requestFor = $this->normalizeRequestFor($row['request_for'] ?? null);
        $issued = $this->normalizeIssued($row['issued'] ?? null);
        $createdAt = $this->parseDate($row['submission_date'] ?? null);
        $legacyFirst = $this->nullableString($row['first_name'] ?? null);
        $legacyLast = $this->nullableString($row['last_name'] ?? null);
        $legacyDesignation = $this->nullableString($row['designation'] ?? null);
        $legacyDateHiredRaw = $row['date_hired'] ?? null;
        $legacyDateHired = $this->parseDate($legacyDateHiredRaw);
        $details = $this->nullableString($row['details'] ?? null);
        $issuedRemarks = $this->nullableString($row['issued_remarks'] ?? null);
        $issuedReasons = $this->nullableString($row['issued_reasons'] ?? null);
        $size = $this->nullableString($row['size'] ?? null);
        $notes = $this->nullableString($row['notes'] ?? null);
        $idRequest = $this->nullableString($row['id_request'] ?? null);
        $idPicture = $this->nullableString($row['id_picture'] ?? null);

        $this->requireValue($type, 'Invalid or missing employee type', $issues);
        $this->requireValue($workLocation, 'Invalid or missing work location', $issues);
        $this->requireValue($requestFor, 'Invalid or missing request type', $issues);
        $this->requireValue($details, 'Missing work-assignment details', $issues);
        $this->requireValue($createdAt, 'Invalid or missing submission date', $issues);

        if ($this->nullableString($legacyDateHiredRaw) !== null && $legacyDateHired === null) {
            $issues[] = 'Invalid legacy date hired';
        }

        $this->checkLength($legacyReference, 255, 'Legacy reference exceeds 255 characters', $issues);
        $this->checkLength($legacyFirst, 255, 'Legacy first name exceeds 255 characters', $issues);
        $this->checkLength($legacyLast, 255, 'Legacy last name exceeds 255 characters', $issues);
        $this->checkLength($legacyDesignation, 255, 'Legacy designation exceeds 255 characters', $issues);
        $this->checkLength($type, 255, 'Employee type exceeds 255 characters', $issues);
        $this->checkLength($workLocation, 255, 'Work location exceeds 255 characters', $issues);
        $this->checkLength($requestFor, 255, 'Request type exceeds 255 characters', $issues);
        $this->checkLength($issued, 255, 'Issued value exceeds 255 characters', $issues);
        $this->checkLength($issuedRemarks, 255, 'Issued remarks exceed 255 characters', $issues);
        $this->checkLength($size, 255, 'Size exceeds 255 characters', $issues);
        $this->checkLength($notes, 255, 'Notes exceed 255 characters', $issues);
        $this->checkLength($idRequest, 255, 'ID request reason exceeds 255 characters', $issues);
        $this->checkLength($idPicture, 255, 'ID picture URL exceeds 255 characters', $issues);

        $timestamp = $createdAt ? $createdAt->format('Y-m-d H:i:s') : null;

        return [
            'issues' => $issues,
            'record' => [
                'iur_reference' => $iurReference,
                'legacy_reference' => $legacyReference,
                'legacy_first' => $legacyFirst,
                'legacy_last' => $legacyLast,
                'legacy_designation' => $legacyDesignation,
                'legacy_date_hired' => $legacyDateHired ? $legacyDateHired->format('Y-m-d') : null,
                'user_id' => $userId,
                'type' => $type,
                'work_location' => $workLocation,
                'request_for' => $requestFor,
                'details' => $details,
                'issued' => $issued,
                'issued_remarks' => $issuedRemarks,
                'issued_reasons' => $issuedReasons,
                'size' => $size,
                'notes' => $notes,
                'id_request' => $idRequest,
                'id_picture' => $idPicture,
                'status' => 'From Import',
                'prod_qty' => 0,
                'white_qty' => 0,
                'black_qty' => 0,
                'collar_qty' => 0,
                'received_prod_qty' => 0,
                'received_white_qty' => 0,
                'received_black_qty' => 0,
                'received_collar_qty' => 0,
                'created_by' => $userId,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
        ];
    }

    private function normalizeType($value)
    {
        $value = strtolower(trim((string) $value));

        if ($value === 'new hire') {
            return 'New Hire';
        }

        if ($value === 'active employee') {
            return 'Active Employee';
        }

        return null;
    }

    private function normalizeWorkLocation($value)
    {
        $value = strtolower(trim((string) $value));

        $locations = [
            'head office' => 'HO',
            'ho' => 'HO',
            'plant' => 'Plant',
            'warehouse' => 'Guinobatan Warehouse / Lubao Office',
            'guinobatan warehouse / lubao office' => 'Guinobatan Warehouse / Lubao Office',
            'field' => 'Field',
            'retail hub' => 'Retail Hub',
        ];

        return $locations[$value] ?? null;
    }

    private function normalizeRequestFor($value)
    {
        $value = strtolower(trim((string) $value));

        if ($value === 'uniform') {
            return 'Uniform';
        }

        if ($value === 'id') {
            return 'ID';
        }

        if ($value === 'both') {
            return 'Both';
        }

        return null;
    }

    private function normalizeIssued($value)
    {
        $value = strtolower(trim((string) $value));

        if ($value === 'yes') {
            return 'Yes';
        }

        if ($value === 'no') {
            return 'No';
        }

        return null;
    }

    private function parseDate($value)
    {
        if ($value instanceof \DateTimeInterface) {
            return Carbon::instance(\DateTime::createFromFormat('Y-m-d H:i:s', $value->format('Y-m-d H:i:s')));
        }

        if (is_numeric($value)) {
            try {
                return Carbon::instance(ExcelDate::excelToDateTimeObject((float) $value));
            } catch (\Throwable $e) {
                return null;
            }
        }

        if (!is_string($value) || trim($value) === '') {
            return null;
        }

        $value = trim($value);
        $formats = [
            'Y-m-d H:i:s',
            'Y-m-d H:i',
            'Y-m-d',
            'd-m-Y H:i:s',
            'd-m-Y H:i',
            'd-m-Y',
            'd/m/Y H:i:s',
            'd/m/Y H:i',
            'd/m/Y',
            'm/d/Y H:i:s',
            'm/d/Y H:i',
            'm/d/Y',
        ];

        foreach ($formats as $format) {
            $date = \DateTime::createFromFormat('!' . $format, $value);
            $errors = \DateTime::getLastErrors();

            if ($date && ($errors === false || ($errors['warning_count'] === 0 && $errors['error_count'] === 0))) {
                return Carbon::instance($date);
            }
        }

        return null;
    }

    private function addToMap(array &$map, $key, $userId)
    {
        if ($key === null || $userId === null) {
            return;
        }

        $userId = (int) $userId;

        if (!isset($map[$key])) {
            $map[$key] = [];
        }

        if (!in_array($userId, $map[$key], true)) {
            $map[$key][] = $userId;
        }
    }

    private function normalizeIdentifier($value)
    {
        if ($value === null) {
            return null;
        }

        $value = strtoupper(trim((string) $value));

        if (preg_match('/^\d+\.0$/', $value)) {
            $value = substr($value, 0, -2);
        }

        $value = preg_replace('/[^A-Z0-9]/', '', $value);
        return $value === '' ? null : $value;
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

    private function reportRow(array $row, $legacyReference, $iurReference, $userId, $method, $result, $message)
    {
        return [
            'excel_row' => $row['excel_row'] ?? null,
            'legacy_reference' => $legacyReference ?: null,
            'iur_reference' => $iurReference,
            'employee_number' => $row['employee_number'] ?? null,
            'user_id' => $userId,
            'resolution_method' => $method,
            'result' => $result,
            'message' => $message,
        ];
    }
}
