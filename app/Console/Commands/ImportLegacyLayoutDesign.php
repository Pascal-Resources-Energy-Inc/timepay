<?php

namespace App\Console\Commands;

use App\Imports\LegacyLayoutDesignImport;
use App\Services\LayoutDesign\LegacyLayoutDesignImportService;
use Illuminate\Console\Command;

class ImportLegacyLayoutDesign extends Command
{
    protected $signature = 'ldr:import-legacy
        {file : Full path to the legacy Layout Design workbook}
        {--dry-run : Preview the import without writing records}
        {--commit : Write valid records and peg sample links}';

    protected $description = 'Preview or import legacy Layout Design requests from an Excel workbook';

    public function handle(LegacyLayoutDesignImport $import, LegacyLayoutDesignImportService $service)
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
        } catch (\Throwable $exception) {
            $this->error('Unable to prepare the legacy Layout Design import: ' . $exception->getMessage());
            return 1;
        }

        if ($commit) {
            try {
                $plan['stats']['imported'] = $service->commit($plan['records']);

                foreach ($plan['report'] as &$reportRow) {
                    if ($reportRow['result'] === 'ready') {
                        $reportRow['result'] = 'imported';
                    }
                }
                unset($reportRow);
            } catch (\Throwable $exception) {
                $plan['stats']['failed'] = count($plan['records']);

                foreach ($plan['report'] as &$reportRow) {
                    if ($reportRow['result'] === 'ready') {
                        $reportRow['result'] = 'failed';
                        $reportRow['message'] = $exception->getMessage();
                    }
                }
                unset($reportRow);

                $this->error('The import was rolled back: ' . $exception->getMessage());
            }
        }

        $reportPath = $this->writeReport($plan['report'], $commit ? 'commit' : 'dry-run');
        $stats = $plan['stats'];

        $this->line('');
        $this->info($commit
            ? 'Legacy Layout Design import completed.'
            : 'Legacy Layout Design dry-run completed. No database records were written.');
        $this->table(
            ['Rows', 'Ready', 'Imported', 'Invalid', 'Duplicates', 'Peg links', 'Failed'],
            [[
                $stats['rows'],
                $stats['ready'],
                $stats['imported'],
                $stats['invalid'],
                $stats['duplicates'],
                $stats['attachments'],
                $stats['failed'],
            ]]
        );
        $this->line('Report: ' . $reportPath);

        return $stats['failed'] > 0 ? 1 : 0;
    }

    private function writeReport(array $rows, $mode)
    {
        $directory = storage_path('app/layout-design-import-reports');

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $path = $directory . DIRECTORY_SEPARATOR
            . 'layout-design-import-' . $mode . '-' . date('Ymd-His') . '-' . uniqid() . '.csv';
        $handle = fopen($path, 'w');

        fputcsv($handle, [
            'Excel row',
            'Reference',
            'Requestor name',
            'Department',
            'Result',
            'Message',
        ]);

        foreach ($rows as $row) {
            fputcsv($handle, [
                $row['excel_row'],
                $row['reference_no'],
                $row['full_name'],
                $row['department'],
                $row['result'],
                $row['message'],
            ]);
        }

        fclose($handle);
        return $path;
    }
}
