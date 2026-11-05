<?php

namespace App\Imports;

use PhpOffice\PhpSpreadsheet\IOFactory;

class LegacyIurImport
{
    private $requiredHeaders = [
        'Submission Date',
        'Uniform & ID Request Ref',
        'Employee Number',
        'First Name',
        'Last Name',
        'Designation',
        'Date Hired',
        'Status',
        'Work Location',
        'Details of Work Assignment',
        'I would like to request for',
        'Have you been issued with Uniform before?',
        'Reason for request of new set of uniforms',
        'How many was issued to you before? And where are they?',
        'Size',
        'Notes',
        'Reason for ID Request',
        'Upload ID Picture with White Background',
        "Requestor's Email Add",
    ];

    public function rows($path)
    {
        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getSheetByName('Form Responses');

        if (!$sheet) {
            throw new \RuntimeException('Worksheet "Form Responses" was not found.');
        }

        $worksheetRows = $sheet->toArray(null, true, false, false);
        $spreadsheet->disconnectWorksheets();

        if (count($worksheetRows) < 2) {
            throw new \RuntimeException('The Form Responses worksheet does not contain any data rows.');
        }

        $headers = $this->headerMap($worksheetRows[0]);
        $missingHeaders = array_values(array_filter($this->requiredHeaders, function ($header) use ($headers) {
            return !array_key_exists($header, $headers);
        }));

        if (!empty($missingHeaders)) {
            throw new \RuntimeException('Missing required columns: ' . implode(', ', $missingHeaders));
        }

        $rows = [];

        foreach (array_slice($worksheetRows, 1) as $index => $row) {
            if ($this->rowIsEmpty($row)) {
                continue;
            }

            $rows[] = [
                'excel_row' => $index + 2,
                'submission_date' => $this->rawCell($row, $headers, 'Submission Date'),
                'legacy_reference' => $this->cell($row, $headers, 'Uniform & ID Request Ref'),
                'employee_number' => $this->cell($row, $headers, 'Employee Number'),
                'first_name' => $this->cell($row, $headers, 'First Name'),
                'last_name' => $this->cell($row, $headers, 'Last Name'),
                'designation' => $this->cell($row, $headers, 'Designation'),
                'date_hired' => $this->rawCell($row, $headers, 'Date Hired'),
                'type' => $this->cell($row, $headers, 'Status'),
                'work_location' => $this->cell($row, $headers, 'Work Location'),
                'details' => $this->cell($row, $headers, 'Details of Work Assignment'),
                'request_for' => $this->cell($row, $headers, 'I would like to request for'),
                'issued' => $this->cell($row, $headers, 'Have you been issued with Uniform before?'),
                'issued_reasons' => $this->cell($row, $headers, 'Reason for request of new set of uniforms'),
                'issued_remarks' => $this->cell($row, $headers, 'How many was issued to you before? And where are they?'),
                'size' => $this->cell($row, $headers, 'Size'),
                'notes' => $this->cell($row, $headers, 'Notes'),
                'id_request' => $this->cell($row, $headers, 'Reason for ID Request'),
                'id_picture' => $this->cell($row, $headers, 'Upload ID Picture with White Background'),
                'email' => $this->cell($row, $headers, "Requestor's Email Add"),
            ];
        }

        return $rows;
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
}
