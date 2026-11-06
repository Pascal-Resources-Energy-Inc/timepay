<?php

namespace App\Http\Controllers;

use App\ApproverSetting;
use App\IUR;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class IurPrintController extends Controller {
    public function batchPrintIds(Request $request) {
        $refs = $request->query('refs');

        if (!$refs || !is_array($refs)) {
            Alert::error('No IUR references provided for printing.')->persistent();
            return back();
        }

        $iurs = IUR::with('user.contact_person')
            ->whereNotNull('user_id')
            ->whereHas('user')
            ->whereIn('iur_reference', $refs)
            ->get();

        if ($iurs->isEmpty()) {
            Alert::error('No valid IUR records found.')->persistent();
            return back();
        }

        $approvers = ApproverSetting::with('user')
            ->where('type_of_form', 'uir')
            ->where('status', 'Active')
            ->get();

        $coordinatorIds = $approvers->pluck('user_id')->toArray();

        if (!in_array(auth()->user()->id, $coordinatorIds)) {
            Alert::error('Unauthorized. You do not have access to this resource')->persistent();
            return back();
        }

        $iurs = $iurs->filter(function ($iur) {
            return in_array($iur->request_for, ['ID', 'Both']);
        })->values();

        if ($iurs->isEmpty()) {
            Alert::error('No IUR records with ID request found.')->persistent();
            return back();
        }

        return view('batch-print-ids', ['iurs' => $iurs]);
    }

    public function batchPrintForm(Request $request) {
        $perPage = (int) $request->input('per_page', 10);

        if (!in_array($perPage, [25, 30, 50, 100])) {
            $perPage = 25;
        }

        $query = IUR::with([
            'user.department',
            'accountabilities',
        ])
            ->whereNotNull('user_id')
            ->whereHas('user')
            ->whereIn('request_for', ['ID', 'Both'])
            ->whereNotNull('iur_reference');

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($query) use ($search) {
                $query->where('iur_reference', 'like', "%{$search}%")
                    ->orWhere('work_location', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($employeeQuery) use ($search) {
                        $employeeQuery
                            ->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('employee_code', 'like', "%{$search}%")
                            ->orWhere('position', 'like', "%{$search}%")
                            ->orWhereHas('department', function ($departmentQuery) use ($search) {
                                $departmentQuery->where('name', 'like', "%{$search}%");
                            });
                    });
            });
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->input('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->input('to'));
        }

        $progress = $request->input('progress');

        if ($progress === 'from-import') {
            $query->where('status', 'From Import');
        }

        if (in_array($progress, [
            'not-printed',
            'released-not-printed',
            'printed-awaiting-release',
            'printed-released',
        ])) {
            $query->where('status', '!=', 'From Import');
        }

        if ($progress === 'released-not-printed') {
            $query->whereNull('id_printed_at')
                ->whereHas('accountabilities', function ($query) {
                    $query->where('released_id', 1);
                });
        }

        if ($progress === 'printed-awaiting-release') {
            $query->whereNotNull('id_printed_at')
                ->whereDoesntHave('accountabilities', function ($query) {
                    $query->where('released_id', 1);
                });
        }

        if ($progress === 'printed-released') {
            $query->whereNotNull('id_printed_at')
                ->whereHas('accountabilities', function ($query) {
                    $query->where('released_id', 1);
                });
        }

        if ($progress === 'not-printed') {
            $query->whereNull('id_printed_at')
                ->whereDoesntHave('accountabilities', function ($query) {
                    $query->where('released_id', 1);
                });
        }

        /* if ($progress === 'missing-photo') { */
        /*     $query->where(function ($query) { */
        /*         $query->whereNull('id_picture') */
        /*               ->orWhere('id_picture', ''); */
        /*     }); */
        /* } */

        $iurs = $query
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->except('page'));

        return view('batch-print-form', [
            'header' => 'for-approval',
            'iurs' => $iurs,
            'selectedRefs' => collect(),
        ]);
    }

    public function confid() {
        $path = storage_path('app/private/images/signature_pres.png');

        abort_unless(is_file($path), 404);

        $hasPrivilege = ApproverSetting::where('type_of_form', 'uir')
            ->where('status', 'Active')
            ->where('user_id', auth()->id())
            ->exists();

        $isExplicitlyAllowed = (int) auth()->id() === 856;

        abort_unless($hasPrivilege || $isExplicitlyAllowed, 403);

        return response()->file($path, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'private, no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'X-Content-Type-Options' => 'nosniff',
        ]);

    }
}
