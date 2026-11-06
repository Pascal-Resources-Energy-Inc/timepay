<?php

namespace App\Http\Controllers;

use App\Imports\LegacyExitInterviewImport;
use App\Services\ExitInterview\ExitInterviewReferenceService;
use App\Services\ExitInterview\LegacyExitInterviewImportService;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class LegacyExitInterviewImportController extends Controller
{
    public function preview(
        Request $request,
        LegacyExitInterviewImport $import,
        LegacyExitInterviewImportService $service
    ) {
        $this->authorizeImport();

        $request->validate([
            'legacy_file' => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        $directory = storage_path('app/exit-interview-imports/' . auth()->id());

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $this->cleanupExpiredPreviews($directory);
        $this->deletePreviousPreview($request);

        $token = bin2hex(random_bytes(20));
        $path = $directory . DIRECTORY_SEPARATOR . $token . '.xlsx';
        $request->file('legacy_file')->move($directory, $token . '.xlsx');

        try {
            $rows = $import->rows($path);
            $plan = $service->prepare($rows);
        } catch (\Throwable $exception) {
            @unlink($path);
            Alert::error('Unable to preview the legacy import: ' . $exception->getMessage())
                ->persistent('Dismiss');
            return redirect()->back()->withInput();
        }

        $request->session()->put('exit_interview_legacy_import', [
            'token' => $token,
            'path' => $path,
            'original_name' => $request->file('legacy_file')->getClientOriginalName(),
        ]);

        $header = 'exit_interviews';

        return view('forms.exit_interviews.import_preview', compact(
            'header',
            'plan',
            'token'
        ));
    }

    public function commit(
        Request $request,
        LegacyExitInterviewImport $import,
        LegacyExitInterviewImportService $service,
        ExitInterviewReferenceService $references
    ) {
        $this->authorizeImport();

        $request->validate([
            'import_token' => 'required|string|size:40',
        ]);

        $preview = $request->session()->get('exit_interview_legacy_import');

        if (!$preview
            || !hash_equals((string) $preview['token'], (string) $request->import_token)
            || !is_file($preview['path'])) {
            Alert::error('The import preview expired. Please upload the workbook again.')
                ->persistent('Dismiss');
            return redirect('exit-interviews');
        }

        try {
            $rows = $import->rows($preview['path']);
            $plan = $service->prepare($rows);

            if ($plan['stats']['ready'] === 0) {
                $this->deletePreviousPreview($request);
                Alert::error('No valid legacy records are ready to import.')
                    ->persistent('Dismiss');
                return redirect('exit-interviews');
            }

            $imported = $service->commit($plan['records'], $references);
        } catch (\Throwable $exception) {
            \Log::error('Legacy exit interview import failed: ' . $exception->getMessage());
            Alert::error('The import was rolled back: ' . $exception->getMessage())
                ->persistent('Dismiss');
            return redirect()->back();
        }

        @unlink($preview['path']);
        $request->session()->forget('exit_interview_legacy_import');

        Alert::success(
            $imported . ' legacy exit interview record(s) imported. '
            . ($plan['stats']['invalid'] + $plan['stats']['unresolved'])
            . ' record(s) were skipped.'
        )->persistent('Dismiss');

        return redirect('exit-interviews');
    }

    public function cancel(Request $request)
    {
        $this->authorizeImport();
        $this->deletePreviousPreview($request);

        return redirect('exit-interviews');
    }

    private function authorizeImport()
    {
        $employee = optional(auth()->user())->employee;

        if (!$employee || $employee->position !== 'HR Head') {
            abort(403);
        }
    }

    private function deletePreviousPreview(Request $request)
    {
        $preview = $request->session()->get('exit_interview_legacy_import');

        if ($preview && isset($preview['path']) && is_file($preview['path'])) {
            @unlink($preview['path']);
        }

        $request->session()->forget('exit_interview_legacy_import');
    }

    private function cleanupExpiredPreviews($directory)
    {
        foreach (glob($directory . DIRECTORY_SEPARATOR . '*.xlsx') ?: [] as $path) {
            if (is_file($path) && filemtime($path) < time() - 86400) {
                @unlink($path);
            }
        }
    }
}
