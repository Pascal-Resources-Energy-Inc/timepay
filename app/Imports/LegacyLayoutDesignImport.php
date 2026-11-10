<?php

namespace App\Imports;

use PhpOffice\PhpSpreadsheet\IOFactory;

class LegacyLayoutDesignImport
{
    private $requiredHeaders = [
        'Submission Date',
        'Ref #',
        'Date Requested',
        "Requestor's name",
        'Department',
        "Requestor's Email Add",
        'Intended Use',
        'Medium Type',
        'Marketing Collateral Type',
        'Purpose of the Layout Request (Indicate where it will be used)',
        'Project Description (E.g. text to include, size, quantity, etc.)',
        'Peg Sample (E.g theme, design, ideas)',
        'Date Needed',
        'Last Update Date',
    ];

    public function rows($path)
    {
        $spreadsheet = IOFactory::load($path);
        $worksheetRows = null;
        $headers = [];
        $closestMissingHeaders = $this->requiredHeaders;

        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
            $candidateRows = $worksheet->toArray(null, true, false, false);

            if (empty($candidateRows)) {
                continue;
            }

            $candidateHeaders = $this->headerMap($candidateRows[0]);
            $missingHeaders = $this->missingHeaders($candidateHeaders);

            if (count($missingHeaders) < count($closestMissingHeaders)) {
                $closestMissingHeaders = $missingHeaders;
            }

            if (empty($missingHeaders)) {
                $worksheetRows = $candidateRows;
                $headers = $candidateHeaders;
                break;
            }
        }

        $spreadsheet->disconnectWorksheets();

        if ($worksheetRows === null) {
            throw new \RuntimeException(
                'No worksheet contains the required columns. Missing columns: '
                . implode(', ', $closestMissingHeaders)
            );
        }

        if (count($worksheetRows) < 2) {
            throw new \RuntimeException('The legacy Layout Design worksheet does not contain any data rows.');
        }

        $rows = [];

        foreach (array_slice($worksheetRows, 1) as $index => $row) {
            if ($this->rowIsEmpty($row)) {
                continue;
            }

            $rows[] = [
                'excel_row' => $index + 2,
                'submission_date' => $this->rawCell($row, $headers, 'Submission Date'),
                'reference_no' => $this->cell($row, $headers, 'Ref #'),
                'date_requested' => $this->rawCell($row, $headers, 'Date Requested'),
                'full_name' => $this->cell($row, $headers, "Requestor's name"),
                'department' => $this->cell($row, $headers, 'Department'),
                'requestor_email' => $this->cell($row, $headers, "Requestor's Email Add"),
                'intended_use' => $this->cell($row, $headers, 'Intended Use'),
                'medium_type' => $this->cell($row, $headers, 'Medium Type'),
                'marketing_collateral_type' => $this->cell($row, $headers, 'Marketing Collateral Type'),
                'purpose_of_layout_request' => $this->cell(
                    $row,
                    $headers,
                    'Purpose of the Layout Request (Indicate where it will be used)'
                ),
                'project_description' => $this->cell(
                    $row,
                    $headers,
                    'Project Description (E.g. text to include, size, quantity, etc.)'
                ),
                'peg_sample' => $this->cell($row, $headers, 'Peg Sample (E.g theme, design, ideas)'),
                'date_needed' => $this->rawCell($row, $headers, 'Date Needed'),
                'last_update_date' => $this->rawCell($row, $headers, 'Last Update Date'),
            ];
        }

        return $rows;
    }

    private function missingHeaders(array $headers)
    {
        return array_values(array_filter($this->requiredHeaders, function ($header) use ($headers) {
            return !array_key_exists($header, $headers);
        }));
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
