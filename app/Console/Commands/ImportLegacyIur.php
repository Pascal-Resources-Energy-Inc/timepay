<?php

namespace App\Console\Commands;

use App\Imports\LegacyIurImport;
use App\Services\Iur\LegacyIurImportService;
use Illuminate\Console\Command;

class ImportLegacyIur extends Command
{
    protected $signature = 'iur:import-legacy
        {file : Full path to the legacy IUR workbook}
        {--dry-run : Preview the import without writing records}
        {--commit : Write valid records to employee_iur}';

    protected $description = 'Preview or import legacy IUR requests from the Form Responses worksheet';

    public function handle(LegacyIurImport $import, LegacyIurImportService $service)
    {
        $path = $this->argument('file');
        $commit = (bool) $this->option('commit');

        if ($commit && $this->option('dry-run')) {
            $this->error('Use either --dry-run or --commit, not both.');
            return 1;
        }

        if (!is_file($path)) {
            $this->error('File not found: ' . $path);
            return 1;
        }

        try {
            $rows = $import->rows($path);
            $plan = $service->prepare($rows);
        } catch (\Throwable $e) {
            $this->error('Unable to prepare the legacy IUR import: ' . $e->getMessage());
            return 1;
        }

        if ($commit) {
            try {
                $service->commit($plan['records']);
                $plan['stats']['imported'] = count($plan['records']);

                foreach ($plan['report'] as &$reportRow) {
                    if ($reportRow['result'] === 'ready') {
                        $reportRow['result'] = 'imported';
                    }
                }
                unset($reportRow);
            } catch (\Throwable $e) {
                $plan['stats']['failed'] = count($plan['records']);

                foreach ($plan['report'] as &$reportRow) {
                    if ($reportRow['result'] === 'ready') {
                        $reportRow['result'] = 'failed';
                        $reportRow['message'] = $e->getMessage();
                    }
                }
                unset($reportRow);

                $this->error('The import was rolled back: ' . $e->getMessage());
            }
        }

        $reportPath = $this->writeReport($plan['report'], $commit ? 'commit' : 'dry-run');
        $stats = $plan['stats'];

        $this->line('');
        $this->info($commit
            ? 'Legacy IUR import completed.'
            : 'Legacy IUR dry-run completed. No database records were written.');
        $this->table(
            ['Rows', 'Ready', 'Imported', 'Invalid', 'Unresolved', 'Conflicts', 'Duplicates', 'Failed'],
            [[
                $stats['rows'],
                $stats['ready'],
                $stats['imported'],
                $stats['invalid'],
                $stats['unresolved'],
                $stats['conflicts'],
                $stats['duplicates'],
                $stats['failed'],
            ]]
        );
        $this->line('Report: ' . $reportPath);

        return $stats['failed'] > 0 ? 1 : 0;
    }

    private function writeReport(array $rows, $mode)
    {
        $directory = storage_path('app/iur-import-reports');

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $path = $directory . DIRECTORY_SEPARATOR
            . 'iur-import-' . $mode . '-' . date('Ymd-His') . '-' . uniqid() . '.csv';
        $handle = fopen($path, 'w');

        fputcsv($handle, [
            'Excel row',
            'Legacy reference',
            'Generated IUR reference',
            'Employee number',
            'User ID',
            'Resolution method',
            'Result',
            'Message',
        ]);

        foreach ($rows as $row) {
            fputcsv($handle, [
                $row['excel_row'],
                $row['legacy_reference'],
                $row['iur_reference'],
                $row['employee_number'],
                $row['user_id'],
                $row['resolution_method'],
                $row['result'],
                $row['message'],
            ]);
        }

        fclose($handle);
        return $path;
    }
}
