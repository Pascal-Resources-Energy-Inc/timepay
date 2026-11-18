<?php

namespace App\Console\Commands;

use App\AdStoreVisitReport;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportAdStoreVisitReports extends Command
{
    protected $signature = 'ad-store-visits:import
        {file : Absolute path or project-relative path to the Jotform CSV export}
        {--dry-run : Validate the CSV and database readiness without writing data}';

    protected $description = 'Import historical AD Store Visit reports from a Jotform CSV export';

    private $targetTables = [
        'ad_store_visit_attachments',
        'ad_store_visit_other_matters',
        'ad_store_visit_acquired_accounts',
        'ad_store_visit_product_availabilities',
        'ad_store_visit_point_persons',
        'ad_store_visit_reports',
    ];

    private $pointPersonRoles = [
        'Area Distributor',
        'AD Representative 1',
        'AD Representative 2',
    ];

    private $products = [
        1 => 'FILLED 230G CYLINDERS',
        2 => 'FILLED 330G CYLINDERS',
        3 => 'STOVE KIT 230G',
        4 => 'STOVE KIT 330G',
        5 => 'GRILLER KIT 230G',
        6 => 'GRILLER KIT 330G',
        7 => 'TORCH KIT 230G',
        8 => 'TORCH KIT 330G',
        9 => 'GRILLER KIT V2',
        10 => 'TORCH KIT V2',
        11 => 'STOVEKIT V2',
        12 => 'CRATE 230G',
        13 => 'CRATE 330G',
    ];

    private $otherMatters = [
        'Is AD carrying other brand of micro cylinders?',
        'Is Booth available?',
        'Are Dealer Tarpaulin available?',
        'Is Store Signage available?',
        'Status of LTO Permit?',
    ];

    public function handle()
    {
        try {
            $path = $this->resolveInputPath($this->argument('file'));
            list($headers, $csvRows) = $this->readCsv($path);
            $layout = $this->buildLayout($headers);
            list($records, $legacyDistributors) = $this->normalizeRows(
                $csvRows,
                $layout
            );
        } catch (\Throwable $exception) {
            $this->error($exception->getMessage());

            return 1;
        }

        usort($records, function ($left, $right) {
            return $left['sequence'] <=> $right['sequence'];
        });

        $this->displaySummary($path, $records, $legacyDistributors);

        $occupiedTables = $this->occupiedTargetTables();

        if (!empty($occupiedTables)) {
            $this->error('The AD Store Visit target tables must be empty before import.');

            foreach ($occupiedTables as $table => $count) {
                $this->line("- {$table}: {$count} row(s)");
            }

            $this->line('Truncate the AD Store Visit tables separately, then run the dry-run again.');

            return 1;
        }

        if ($this->option('dry-run')) {
            $this->info('Dry run passed. No database records were created.');
            return 0;
        }

        if (!$this->confirm(
            'Import ' . count($records) . ' historical AD Store Visit reports now?'
        )) {
            $this->warn('Import cancelled.');

            return 0;
        }

        try {
            DB::transaction(function () use ($records) {
                $occupiedTables = $this->occupiedTargetTables();

                if (!empty($occupiedTables)) {
                    throw new \RuntimeException(
                        'The target tables changed after validation and are no longer empty.'
                    );
                }

                foreach ($records as $record) {
                    $this->insertRecord($record);
                }
            });
        } catch (\Throwable $exception) {
            $this->error('Import failed and was rolled back: ' . $exception->getMessage());

            return 1;
        }

        $this->info(count($records) . ' AD Store Visit reports imported successfully.');
        $this->line('Approval data and Jotform attachments were intentionally skipped.');

        return 0;
    }

    private function resolveInputPath($path)
    {
        $candidates = [$path, base_path($path)];

        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                $resolved = realpath($candidate);

                if (strtolower(pathinfo($resolved, PATHINFO_EXTENSION)) !== 'csv') {
                    throw new \InvalidArgumentException(
                        'The import file must be a CSV export.'
                    );
                }

                return $resolved;
            }
        }

        throw new \InvalidArgumentException('CSV file not found: ' . $path);
    }

    private function readCsv($path)
    {
        $handle = fopen($path, 'r');

        if ($handle === false) {
            throw new \RuntimeException('Unable to open the CSV file.');
        }

        try {
            $byteOrderMark = fread($handle, 3);

            if ($byteOrderMark !== "\xEF\xBB\xBF") {
                rewind($handle);
            }

            $headers = fgetcsv($handle);

            if ($headers === false || empty($headers)) {
                throw new \InvalidArgumentException('The CSV file has no header row.');
            }

            if (count($headers) !== count(array_unique($headers))) {
                throw new \InvalidArgumentException(
                    'The CSV contains duplicate column headers.'
                );
            }

            $rows = [];
            $lineNumber = 1;

            while (($values = fgetcsv($handle)) !== false) {
                $lineNumber++;

                if ($this->isEmptyCsvRow($values)) {
                    continue;
                }

                if (count($values) !== count($headers)) {
                    throw new \InvalidArgumentException(
                        "CSV line {$lineNumber} has " . count($values)
                        . ' values; expected ' . count($headers) . '.'
                    );
                }

                $rows[] = [
                    'line' => $lineNumber,
                    'values' => array_combine($headers, $values),
                ];
            }
        } finally {
            fclose($handle);
        }

        if (empty($rows)) {
            throw new \InvalidArgumentException('The CSV contains no records.');
        }

        return [$headers, $rows];
    }

    private function isEmptyCsvRow(array $values)
    {
        foreach ($values as $value) {
            if (trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }

    private function buildLayout(array $headers)
    {
        $requiredHeaders = [
            'Submission Date',
            'ASVC No',
            'ASVC Report Submission Details',
            'Area Distributor',
            "AD- Representative's Email Add",
            'Time In:',
            'Issues & Concern discussed by AD or their representative:',
            'HIC Submitted by',
            'Designation',
            "Officer's Email",
        ];

        foreach ($this->pointPersonRoles as $role) {
            $prefix = 'AD Store Visit Point Person - During Visit >> '
                . $role . ' >> ';

            $requiredHeaders[] = $prefix
                . 'Full Name (Last Name, First Name)';
            $requiredHeaders[] = $prefix . 'Present?';
            $requiredHeaders[] = $prefix . 'Comment';
        }

        foreach ($this->otherMatters as $matter) {
            $prefix = 'Other Matters >> ' . $matter . ' >> ';
            $requiredHeaders[] = $prefix . 'Inspected';
            $requiredHeaders[] = $prefix . 'Status';
            $requiredHeaders[] = $prefix . 'Remarks';
        }

        $missingHeaders = array_values(array_diff($requiredHeaders, $headers));

        if (!empty($missingHeaders)) {
            throw new \InvalidArgumentException(
                'Missing required CSV column(s): ' . implode(', ', $missingHeaders)
            );
        }

        $productHeaders = [];
        $accountHeaders = [];

        foreach ($headers as $header) {
            if (preg_match(
                '/^Product Availability >>\s*(\d+)\.\s*(.*?)\s*>>\s*'
                    . '(Inspected|Availability|Remarks)$/',
                $header,
                $matches
            )) {
                $productHeaders[(int) $matches[1]][$matches[3]] = $header;
            }

            if (preg_match(
                '/^Acquired Accounts .* >>\s*(\d+)\s*>>\s*'
                    . '(Customer Name|Customer Type|Amount Purchased)$/',
                $header,
                $matches
            )) {
                $accountHeaders[(int) $matches[1]][$matches[2]] = $header;
            }
        }

        foreach (array_keys($this->products) as $position) {
            foreach (['Inspected', 'Availability', 'Remarks'] as $field) {
                if (empty($productHeaders[$position][$field])) {
                    throw new \InvalidArgumentException(
                        "Missing product {$position} {$field} column."
                    );
                }
            }
        }

        for ($position = 1; $position <= 10; $position++) {
            foreach (['Customer Name', 'Customer Type', 'Amount Purchased'] as $field) {
                if (empty($accountHeaders[$position][$field])) {
                    throw new \InvalidArgumentException(
                        "Missing acquired account {$position} {$field} column."
                    );
                }
            }
        }

        return [
            'products' => $productHeaders,
            'accounts' => $accountHeaders,
        ];
    }

    private function normalizeRows(array $csvRows, array $layout)
    {
        $records = [];
        $errors = [];
        $seenReferences = [];
        $legacyDistributors = [];
        $currentDistributors = array_flip(
            config('ad_store_visit.area_distributors', [])
        );

        foreach ($csvRows as $csvRow) {
            try {
                $record = $this->normalizeRow(
                    $csvRow['values'],
                    $layout
                );

                if (isset($seenReferences[$record['reference_no']])) {
                    throw new \InvalidArgumentException(
                        'Duplicate ASVC No; first found on CSV line '
                        . $seenReferences[$record['reference_no']] . '.'
                    );
                }

                $seenReferences[$record['reference_no']] = $csvRow['line'];

                if (!isset($currentDistributors[$record['parent']['area_distributor']])) {
                    $legacyDistributors[$record['parent']['area_distributor']] = true;
                }

                $records[] = $record;
            } catch (\Throwable $exception) {
                $errors[] = 'Line ' . $csvRow['line'] . ': '
                    . $exception->getMessage();
            }
        }

        if (!empty($errors)) {
            $visibleErrors = array_slice($errors, 0, 20);

            throw new \InvalidArgumentException(
                "CSV validation failed:\n- " . implode("\n- ", $visibleErrors)
                . (count($errors) > 20
                    ? "\n- ... and " . (count($errors) - 20) . ' more error(s).'
                    : '')
            );
        }

        return [$records, array_keys($legacyDistributors)];
    }

    private function normalizeRow(array $row, array $layout)
    {
        $reference = $this->requiredString($row, 'ASVC No');

        if (!preg_match('/^ASVC\s*-\s*(\d+)$/i', $reference, $matches)) {
            throw new \InvalidArgumentException(
                "Invalid ASVC No '{$reference}'."
            );
        }

        $sequence = (int) $matches[1];

        if ($sequence < 1) {
            throw new \InvalidArgumentException('ASVC sequence must be positive.');
        }

        $visitDate = $this->parseDateTime(
            $this->requiredString($row, 'Submission Date'),
            ['M j, Y'],
            'Submission Date'
        );
        $submittedAt = $this->parseDateTime(
            $this->requiredString($row, 'ASVC Report Submission Details'),
            ['M j, Y h:i A', 'M j, Y g:i A'],
            'ASVC Report Submission Details'
        );
        $timeIn = $this->parseDateTime(
            $this->requiredString($row, 'Time In:'),
            ['h:i A', 'g:i A'],
            'Time In'
        );

        $representativeEmail = $this->nullableString(
            $row["AD- Representative's Email Add"]
        );
        $submittedByEmail = $this->requiredString($row, "Officer's Email");

        if ($representativeEmail !== null
            && !filter_var($representativeEmail, FILTER_VALIDATE_EMAIL)
        ) {
            throw new \InvalidArgumentException(
                "Invalid representative email '{$representativeEmail}'."
            );
        }

        if (!filter_var($submittedByEmail, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException(
                "Invalid officer email '{$submittedByEmail}'."
            );
        }

        $pointPersons = [];

        foreach ($this->pointPersonRoles as $role) {
            $prefix = 'AD Store Visit Point Person - During Visit >> '
                . $role . ' >> ';

            $pointPersons[] = [
                'role' => $role,
                'full_name' => $this->nullableString(
                    $row[$prefix . 'Full Name (Last Name, First Name)']
                ),
                'is_present' => $this->checkboxValue(
                    $row[$prefix . 'Present?'],
                    $role . ' Present?'
                ),
                'comment' => $this->nullableString(
                    $row[$prefix . 'Comment']
                ),
            ];
        }

        $products = [];

        foreach ($this->products as $position => $productName) {
            $headers = $layout['products'][$position];

            $products[] = [
                'product_name' => $productName,
                'sort_order' => $position,
                'is_inspected' => $this->checkboxValue(
                    $row[$headers['Inspected']],
                    $productName . ' Inspected'
                ),
                'is_available' => $this->nullableBoolean(
                    $row[$headers['Availability']],
                    $productName . ' Availability'
                ),
                'remarks' => $this->nullableString(
                    $row[$headers['Remarks']]
                ),
            ];
        }

        $accounts = [];

        for ($position = 1; $position <= 10; $position++) {
            $headers = $layout['accounts'][$position];
            $name = $this->nullableString($row[$headers['Customer Name']]);
            $type = $this->nullableString($row[$headers['Customer Type']]);
            $amount = $this->nullableString($row[$headers['Amount Purchased']]);

            if ($name === null && $type === null && $amount === null) {
                continue;
            }

            if ($name === null || $type === null || $amount === null) {
                throw new \InvalidArgumentException(
                    "Acquired account {$position} is incomplete."
                );
            }

            if (!in_array($type, ['End User', 'Dealer', 'Mega Dealer'], true)) {
                throw new \InvalidArgumentException(
                    "Acquired account {$position} has invalid customer type '{$type}'."
                );
            }

            $accounts[] = [
                'customer_name' => $name,
                'customer_type' => $type,
                'amount_purchased' => $this->normalizeAmount(
                    $amount,
                    "Acquired account {$position} amount"
                ),
            ];
        }

        $otherMatters = [];

        foreach ($this->otherMatters as $matter) {
            $prefix = 'Other Matters >> ' . $matter . ' >> ';

            $otherMatters[] = [
                'matter' => $matter,
                'is_inspected' => $this->checkboxValue(
                    $row[$prefix . 'Inspected'],
                    $matter . ' Inspected'
                ),
                'status' => $this->nullableBoolean(
                    $row[$prefix . 'Status'],
                    $matter . ' Status'
                ),
                'remarks' => $this->nullableString(
                    $row[$prefix . 'Remarks']
                ),
            ];
        }

        return [
            'sequence' => $sequence,
            'reference_no' => $reference,
            'parent' => [
                'reference_no' => $reference,
                'area_distributor' => $this->requiredString(
                    $row,
                    'Area Distributor'
                ),
                'area_distributor_email' => null,
                'representative_email' => $representativeEmail,
                'visit_date' => $visitDate->format('Y-m-d'),
                'time_in' => $timeIn->format('H:i:s'),
                'time_out' => null,
                'issues_and_concerns' => $this->requiredString(
                    $row,
                    'Issues & Concern discussed by AD or their representative:'
                ),
                'recommendations' => null,
                'submitted_by_name' => $this->requiredString(
                    $row,
                    'HIC Submitted by'
                ),
                'submitted_by_designation' => $this->requiredString(
                    $row,
                    'Designation'
                ),
                'submitted_by_email' => $submittedByEmail,
                'user_id' => null,
                'approval_status' => AdStoreVisitReport::APPROVAL_FROM_IMPORT,
                'submitted_at' => $submittedAt,
            ],
            'created_at' => $submittedAt,
            'point_persons' => $pointPersons,
            'products' => $products,
            'accounts' => $accounts,
            'other_matters' => $otherMatters,
        ];
    }

    private function requiredString(array $row, $header)
    {
        $value = $this->nullableString($row[$header] ?? null);

        if ($value === null) {
            throw new \InvalidArgumentException("{$header} is required.");
        }

        return $value;
    }

    private function nullableString($value)
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    private function checkboxValue($value, $field)
    {
        $value = strtolower(trim((string) $value));

        if ($value === '') {
            return false;
        }

        if (in_array(
            $value,
            ['present?', 'present', 'inspected', 'yes', '1', 'checked'],
            true
        )) {
            return true;
        }

        throw new \InvalidArgumentException(
            "{$field} has unsupported value '{$value}'."
        );
    }

    private function nullableBoolean($value, $field)
    {
        $value = strtolower(trim((string) $value));

        if ($value === '') {
            return null;
        }

        if (in_array($value, ['yes', '1', 'true'], true)) {
            return true;
        }

        if (in_array($value, ['no', '0', 'false'], true)) {
            return false;
        }

        throw new \InvalidArgumentException(
            "{$field} has unsupported value '{$value}'."
        );
    }

    private function normalizeAmount($value, $field)
    {
        $normalized = preg_replace('/[^0-9.\-]/u', '', $value);

        if ($normalized === '' || !is_numeric($normalized)) {
            throw new \InvalidArgumentException(
                "{$field} has invalid amount '{$value}'."
            );
        }

        $amount = (float) $normalized;

        if ($amount < 0 || $amount > 999999999999.99) {
            throw new \InvalidArgumentException(
                "{$field} is outside the supported range."
            );
        }

        return number_format($amount, 2, '.', '');
    }

    private function parseDateTime($value, array $formats, $field)
    {
        foreach ($formats as $format) {
            try {
                $date = Carbon::createFromFormat($format, $value);

                if ($date !== false) {
                    return $date;
                }
            } catch (\Throwable $exception) {
                // Try the next accepted Jotform format.
            }
        }

        throw new \InvalidArgumentException(
            "{$field} has invalid date/time '{$value}'."
        );
    }

    private function occupiedTargetTables()
    {
        $occupied = [];

        foreach ($this->targetTables as $table) {
            $count = DB::table($table)->count();

            if ($count > 0) {
                $occupied[$table] = $count;
            }
        }

        return $occupied;
    }

    private function displaySummary($path, array $records, array $legacyDistributors)
    {
        $pointPersonCount = 0;
        $productCount = 0;
        $accountCount = 0;
        $otherMatterCount = 0;

        foreach ($records as $record) {
            $pointPersonCount += count($record['point_persons']);
            $productCount += count($record['products']);
            $accountCount += count($record['accounts']);
            $otherMatterCount += count($record['other_matters']);
        }

        $this->line('Source: ' . $path);
        $this->table(
            ['Validated item', 'Count'],
            [
                ['Reports', count($records)],
                ['Point persons', $pointPersonCount],
                ['Product availability rows', $productCount],
                ['Acquired accounts', $accountCount],
                ['Other matters', $otherMatterCount],
                ['Attachments imported', 0],
            ]
        );

        if (!empty($legacyDistributors)) {
            $this->warn(
                'Preserving ' . count($legacyDistributors)
                . ' historical distributor value(s) not found in the current options:'
            );

            foreach ($legacyDistributors as $distributor) {
                $this->line('- ' . $distributor);
            }
        }

        $this->line('Original ASVC numbers and historical timestamps will be preserved.');
        $this->line('user_id, time_out, recommendations, and area_distributor_email will be null.');
    }

    private function insertRecord(array $record)
    {
        $report = new AdStoreVisitReport($record['parent']);
        $report->id = $record['sequence'];
        $report->created_at = $record['created_at'];
        $report->updated_at = $record['created_at'];
        $report->save();

        $report->pointPersons()->createMany($record['point_persons']);
        $report->productAvailabilities()->createMany($record['products']);

        if (!empty($record['accounts'])) {
            $report->acquiredAccounts()->createMany($record['accounts']);
        }

        $report->otherMatters()->createMany($record['other_matters']);

    }
}
