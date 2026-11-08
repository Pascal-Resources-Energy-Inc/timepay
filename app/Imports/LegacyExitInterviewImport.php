<?php

namespace App\Imports;

use PhpOffice\PhpSpreadsheet\IOFactory;

class LegacyExitInterviewImport
{
    private $expectedHeaders = [
        'Submission Date',
        'Status (Approval: Employee Exit Interview)',
        'Ee Exit Interview Ref',
        'Your Name',
        'Email',
        'Your Role in the Company',
        'Your Hiring Date',
        'Your Separation Date',
        'Is your reason for leaving a new job opportunity? Ikaw ba ay umalis dahil sa ibang trabaho?',
        'What made it a better opportunity? Ibahagi ang dahilan kung bakit sa tingin mo ay mabuti ang iyong lilipatan.',
        'What made you decide to leave?',
        'Reason for leaving not listed above?',
        'Job Responsibilities',
        'What types of career  opportunities are important to you?',
        'What did you like MOST about working here? Ano ang pinaka- nagustuhan mo dito sa PREI?',
        'What did you like LEAST about working here? Ano ang pinaka- HINDI mo nagustuhan dito sa PREI?',
        "Did you have a strong, clear understanding of the main goals and direction of your <em>department</em> and its contribution to the company's overall vision?",
        "If 'no', please explain",
        "These are our Company's Core Values: Honesty & Integrity, Innovation, Teamwork, Commitment to Serve, Communication and Respect, and Hard work. Do you believe PREI live up to these values?",
        "If 'no', please explain",
        'How frequently did you get performance feedback?',
        'Any changes you would like to suggest? Give us your recommendations!',
        "Any other feedback you'd like to give us?",
        'Opportunities for Achieving Personal Goals',
        'Work Environment',
        'Immediate Superior',
        'Salary',
        'Benefits',
        'Last Update Date',
        'Submission IP',
        'Submission ID',
    ];

    public function rows($path)
    {
        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getSheet(0);
        $worksheetRows = $sheet->toArray(null, true, false, false);
        $spreadsheet->disconnectWorksheets();

        if (count($worksheetRows) < 2) {
            throw new \RuntimeException('The workbook does not contain any exit interview rows.');
        }

        $this->validateHeaders($worksheetRows[0]);
        $rows = [];

        foreach (array_slice($worksheetRows, 1) as $index => $row) {
            if ($this->rowIsEmpty($row)) {
                continue;
            }

            $rows[] = [
                'excel_row' => $index + 2,
                'submission_date' => $this->raw($row, 0),
                'status' => $this->cell($row, 1),
                'legacy_reference_no' => $this->cell($row, 2),
                'full_name' => $this->cell($row, 3),
                'email' => $this->cell($row, 4),
                'job_title' => $this->cell($row, 5),
                'hire_date' => $this->raw($row, 6),
                'separation_date' => $this->raw($row, 7),
                'is_leaving_for_new_job' => $this->cell($row, 8),
                'new_job_offer_text' => $this->cell($row, 9),
                'reason_of_leave' => $this->cell($row, 10),
                'reason_of_leave_other' => $this->cell($row, 11),
                'rating_job_responsibilities' => $this->raw($row, 12),
                'career_type_importance' => $this->cell($row, 13),
                'most_liked_text' => $this->cell($row, 14),
                'least_liked_text' => $this->cell($row, 15),
                'understands_company_goals' => $this->cell($row, 16),
                'goals_explanation' => $this->cell($row, 17),
                'company_lives_core_values' => $this->cell($row, 18),
                'core_values_explanation' => $this->cell($row, 19),
                'frequently_performance_feedback' => $this->cell($row, 20),
                'suggestion_company' => $this->cell($row, 21),
                'other_feedback_text' => $this->cell($row, 22),
                'rating_personal_goals' => $this->raw($row, 23),
                'rating_work_environment' => $this->raw($row, 24),
                'rating_immediate_superior' => $this->raw($row, 25),
                'rating_salary' => $this->raw($row, 26),
                'rating_benefits' => $this->raw($row, 27),
                'last_update_date' => $this->raw($row, 28),
                'submission_ip' => $this->cell($row, 29),
                'submission_id' => $this->cell($row, 30),
            ];
        }

        return $rows;
    }

    private function validateHeaders(array $headers)
    {
        $issues = [];

        foreach ($this->expectedHeaders as $index => $expected) {
            $actual = trim((string) ($headers[$index] ?? ''));

            if ($actual !== $expected) {
                $issues[] = 'Column ' . ($index + 1) . ' must be "' . $expected . '".';
            }
        }

        if (!empty($issues)) {
            throw new \RuntimeException('Unexpected workbook format. ' . implode(' ', $issues));
        }
    }

    private function raw(array $row, $index)
    {
        return $row[$index] ?? null;
    }

    private function cell(array $row, $index)
    {
        $value = $this->raw($row, $index);

        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);
        return $value === '' ? null : $value;
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
