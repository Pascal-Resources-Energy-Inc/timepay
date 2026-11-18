<?php

namespace App\Http\Controllers;

use App\AdStoreVisitReport;
use App\Mail\AdStoreVisitMail;
use App\Services\AdStoreVisitApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class AdStoreVisitReportController extends Controller {
    private const ALL_REPORT_VIEWER_EMAILS = [
        'siuping.par@pascalresources.com.ph',
        'nelson.par@pascalresources.com.ph',
        'matthew.par@pascalresources.com.ph',
        'nathaniel.camat@pascalresources.com.ph',
        'elmer.binuya@pascalresources.com.ph',
        'dennis.villareal@pascalresources.com.ph',
        'rey.david@pascalresources.com.ph',
        'maricel.solis@pascalresources.com.ph',
        'siuping.par@pascalresources.com.ph',
        'sharon.santos@pascalresources.com.ph',

        'jhona.rosario@pascalresources.com.ph',

        'jadd.tampingco@pascalresources.com.ph',
        'alden.monilla@pascalresources.com.ph',
        'francis.ramos@pascalresources.com.ph'
    ];

    private $approvalService;

    public function __construct(AdStoreVisitApprovalService $approvalService) {
        $this->approvalService = $approvalService;
   }

    public function index(Request $request) {
        $canViewAll = $this->canViewAllReports();
        $visibleReports = $this->reportsVisibleToCurrentUser();

        $totalReports = (clone $visibleReports)->count();
        $thisMonthReports = (clone $visibleReports)
            ->whereYear('visit_date', now()->year)
            ->whereMonth('visit_date', now()->month)
            ->count();
        $uniqueDistributors = (clone $visibleReports)
            ->distinct()
            ->count('area_distributor');

        $query = (clone $visibleReports)->orderBy('submitted_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($query) use ($search) {
                $query->where('reference_no', 'like', "%{$search}%")
                    ->orWhere('area_distributor', 'like', "%{$search}%")
                    ->orWhere('area_distributor_email', 'like', "%{$search}%")
                    ->orWhere('submitted_by_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('from')) {
            $query->whereDate('visit_date', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('visit_date', '<=', $request->to);
        }

        $reports = $query->paginate(20)->appends($request->query());

        return view('forms.ad_store_visit.index', [
            'reports' => $reports,
            'search' => $request->search,
            'from' => $request->from,
            'to' => $request->to,
            'canViewAll' => $canViewAll,
            'totalReports' => $totalReports,
            'thisMonthReports' => $thisMonthReports,
            'uniqueDistributors' => $uniqueDistributors,
            'header' => 'adStoreVisit',
        ]);
    }

    public function create()
    {
        return view('forms.ad_store_visit.create', [
            'areaDistributors' => config(
                'ad_store_visit.area_distributors',
                []
            ),
            'header' => 'adStoreVisit',
        ]);
    }

    public function store(Request $request) {
        $request->merge([
            'acquired_accounts' => array_values(array_filter(
                $request->input('acquired_accounts', []),
                function ($account) {
                    return !empty($account['customer_name'])
                        || !empty($account['customer_type'])
                        || isset($account['amount_purchased'])
                            && $account['amount_purchased'] !== '';
                }
            )),
        ]);

        $validated = $request->validate([
            'area_distributor' => [
                'required',
                'string',
                'max:500',
                Rule::in(config('ad_store_visit.area_distributors', [])),
            ],
            'area_distributor_email' => 'nullable|required_without:representative_email|email|max:255',
            'representative_email' => 'nullable|required_without:area_distributor_email|email|max:255',
            'visit_date' => 'required|date',
            'time_in' => 'required|date_format:H:i',
            'issues_and_concerns' => 'required|string',
            'recommendations' => 'required|string',
            'submitted_by_name' => 'required|string|max:255',
            'submitted_by_designation' => 'required|string|max:150',
            'submitted_by_email' => 'required|email|max:255',

            'point_persons' => 'required|array|size:3',
            'point_persons.*.role' => [
                'required',
                'distinct',
                Rule::in([
                    'Area Distributor',
                    'AD Representative 1',
                    'AD Representative 2',
                ]),
            ],
            'point_persons.*.full_name' => 'nullable|string|max:255',
            'point_persons.*.is_present' => 'nullable|boolean',
            'point_persons.*.comment' => 'nullable|string',

            'product_availabilities' => 'required|array|size:13',
            'product_availabilities.*.product_name' => 'required|string|max:255',
            'product_availabilities.*.sort_order' => 'required|integer|min:1|max:255|distinct',
            'product_availabilities.*.is_inspected' => 'nullable|boolean',
            'product_availabilities.*.is_available' => 'nullable|in:Yes,No,1,0',
            'product_availabilities.*.remarks' => 'nullable|string',

            'acquired_accounts' => 'nullable|array|max:10',
            'acquired_accounts.*.customer_name' => 'required|string|max:255',
            'acquired_accounts.*.customer_type' => 'required|in:End User,Dealer,Mega Dealer',
            'acquired_accounts.*.amount_purchased' => 'required|numeric|min:0|max:999999999999.99',

            'other_matters' => 'required|array|size:5',
            'other_matters.*.matter' => 'required|string|max:255',
            'other_matters.*.is_inspected' => 'nullable|boolean',
            'other_matters.*.status' => 'nullable|in:Yes,No,1,0',
            'other_matters.*.remarks' => 'nullable|string',

            'attachments' => 'required|array|min:1',
            'attachments.*' => 'required|file|max:30720',
        ]);

        $storedFiles = [];

        try {
            $report = DB::transaction(function () use (
                $request,
                $validated,
                &$storedFiles
            ) {
                $autoTimeOut = now();

                $report = AdStoreVisitReport::create([
                    'reference_no' => 'TEMP-' . uniqid('', true),
                    'area_distributor' => $validated['area_distributor'],
                    'area_distributor_email' => $validated['area_distributor_email'] ?? null,
                    'representative_email' => $validated['representative_email'] ?? null,
                    'visit_date' => $validated['visit_date'],
                    'time_in' => $validated['time_in'],
                    'time_out' => $autoTimeOut->format('H:i:s'),
                    'issues_and_concerns' => $validated['issues_and_concerns'],
                    'recommendations' => $validated['recommendations'],
                    'submitted_by_name' => $validated['submitted_by_name'],
                    'submitted_by_designation' => $validated['submitted_by_designation'],
                    'submitted_by_email' => $validated['submitted_by_email'],
                    'user_id' => auth()->id(),
                    'submitted_at' => now(),
                ]);

                $report->reference_no = 'ASVC - ' . str_pad(
                    $report->id,
                    6,
                    '0',
                    STR_PAD_LEFT
                );
                $report->save();

                $pointPersons = collect($validated['point_persons'])
                    ->map(function ($person) {
                        return [
                            'role' => $person['role'],
                            'full_name' => $person['full_name'] ?? null,
                            'is_present' => $this->toBoolean(
                                $person['is_present'] ?? false
                            ),
                            'comment' => $person['comment'] ?? null,
                        ];
                    })
                    ->all();

                $report->pointPersons()->createMany($pointPersons);

                $productAvailabilities = collect(
                    $validated['product_availabilities']
                )->map(function ($product) {
                    return [
                        'product_name' => $product['product_name'],
                        'sort_order' => $product['sort_order'],
                        'is_inspected' => $this->toBoolean(
                            $product['is_inspected'] ?? false
                        ),
                        'is_available' => $this->toNullableBoolean(
                            $product['is_available'] ?? null
                        ),
                        'remarks' => $product['remarks'] ?? null,
                    ];
                })->all();

                $report->productAvailabilities()->createMany(
                    $productAvailabilities
                );

                if (!empty($validated['acquired_accounts'])) {
                    $report->acquiredAccounts()->createMany(
                        $validated['acquired_accounts']
                    );
                }

                $otherMatters = collect($validated['other_matters'])
                    ->map(function ($matter) {
                        return [
                            'matter' => $matter['matter'],
                            'is_inspected' => $this->toBoolean(
                                $matter['is_inspected'] ?? false
                            ),
                            'status' => $this->toNullableBoolean(
                                $matter['status'] ?? null
                            ),
                            'remarks' => $matter['remarks'] ?? null,
                        ];
                    })
                    ->all();

                $report->otherMatters()->createMany($otherMatters);

                $destination = public_path(
                    'ad_store_visit_attachments/' . $report->id
                );

                if (!File::isDirectory($destination)) {
                    File::makeDirectory($destination, 0755, true);
                }

                foreach ($request->file('attachments', []) as $file) {
                    $originalName = $file->getClientOriginalName();
                    $safeName = preg_replace(
                        '/[^A-Za-z0-9._-]/',
                        '_',
                        $originalName
                    );
                    $fileName = uniqid('', true) . '_' . $safeName;
                    $file->move($destination, $fileName);

                    $absolutePath = $destination . DIRECTORY_SEPARATOR . $fileName;
                    $storedFiles[] = $absolutePath;

                    $report->attachments()->create([
                        'file_path' => '/ad_store_visit_attachments/'
                            . $report->id . '/' . $fileName,
                        'original_name' => $originalName,
                        'mime_type' => $file->getClientMimeType(),
                        'file_size' => File::size($absolutePath),
                    ]);
                }

                return $report;
            });
        } catch (\Throwable $exception) {
            foreach ($storedFiles as $storedFile) {
                File::delete($storedFile);
            }

            throw $exception;
        }

        $approvalRecipient = $this->approvalRecipient($report);

        try {
            $approvalExpiresAt = ($report->submitted_at ?: now())
                ->copy()
                ->addDay();
            $approvalUrl = URL::temporarySignedRoute(
                'ad-store-visit-reports.distributor-approval',
                $approvalExpiresAt,
                ['id' => $report->id]
            );

            Mail::to($approvalRecipient)->send(
                new AdStoreVisitMail(
                    $report,
                    'approval_request',
                    [
                        'approval_url' => $approvalUrl,
                        'recipient_email' => $approvalRecipient,
                    ]
                )
            );

            Alert::success(
                'Report submitted successfully.'
            )->persistent('Dismiss');
        } catch (\Throwable $exception) {
            Log::error('AD Store Visit approval email failed.', [
                'report_id' => $report->id,
                'recipient' => $approvalRecipient,
                'message' => $exception->getMessage(),
            ]);

            Alert::warning(
                'The report was submitted, but the AD approval email could not be sent.'
            )->persistent('Dismiss');
        }

        return redirect()->route('ad-store-visit-reports.index');
    }

    public function showDistributorApproval($id) {
        $report = AdStoreVisitReport::findOrFail($id);

        return view(
            'forms.ad_store_visit.approve',
            $this->distributorApprovalViewData($report)
        );
    }

    public function approveByDistributor(Request $request, $id) {
        // Preserve the existing approval form until the view sends a decision.
        $request->merge([
            'decision' => $request->input('decision', 'approve'),
        ]);

        $validated = $request->validate(
            [
                'decision' => 'required|in:approve,disapprove',
                'disapproval_reason' =>
                    'required_if:decision,disapprove|nullable|string|max:1000',
            ],
            [
                'disapproval_reason.required_if' =>
                    'Please enter the reason for disapproving this report.',
            ]
        );

        $report = AdStoreVisitReport::findOrFail($id);

        if (!$report->isApprovalPending()) {
            return view(
                'forms.ad_store_visit.approve',
                $this->distributorApprovalViewData($report)
            );
        }

        $isDisapproval = $validated['decision'] === 'disapprove';

        try {
            $decisionCompleted = $isDisapproval
                ? $this->approvalService->disapprove(
                    $report->id,
                    $validated['disapproval_reason']
                )
                : $this->approvalService->approve($report->id);
        } catch (\Throwable $exception) {
            Log::error('AD Store Visit distributor decision failed.', [
                'report_id' => $report->id,
                'decision' => $validated['decision'],
                'message' => $exception->getMessage(),
            ]);

            return response()->view(
                'forms.ad_store_visit.approve',
                $this->distributorApprovalViewData($report, [
                    'approvalFailed' => true,
                ]),
                500
            );
        }

        $report->refresh();

        if (!$decisionCompleted) {
            return view(
                'forms.ad_store_visit.approve',
                $this->distributorApprovalViewData($report)
            );
        }

        return view(
            'forms.ad_store_visit.approve',
            $this->distributorApprovalViewData($report, [
                'approvalCompleted' => !$isDisapproval,
                'disapprovalCompleted' => $isDisapproval,
            ])
        );
    }
    public function show($id) {
        $report = $this->reportsVisibleToCurrentUser()
            ->with([
                'pointPersons',
                'productAvailabilities',
                'acquiredAccounts',
                'otherMatters',
                'attachments',
                'user',
            ])
            ->findOrFail($id);

        return view('forms.ad_store_visit.show', [
            'report' => $report,
            'header' => 'adStoreVisit',
        ]);
    }

    private function distributorApprovalViewData(
        AdStoreVisitReport $report,
        array $overrides = []
    ) {
        return array_merge([
            'report' => $report,
            'alreadyApproved' => $report->isApproved(),
            'alreadyDisapproved' => $report->isDisapproved(),
            'approvalCompleted' => false,
            'disapprovalCompleted' => false,
            'approvalFailed' => false,
        ], $overrides);
    }
    private function reportsVisibleToCurrentUser() {
        $query = AdStoreVisitReport::query();

        if (!$this->canViewAllReports()) {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }

    private function canViewAllReports() {
        return in_array(
            strtolower((string) auth()->user()->email),
            self::ALL_REPORT_VIEWER_EMAILS,
            true
        );
    }

    private function approvalRecipient(AdStoreVisitReport $report) {
        return $report->area_distributor_email
            ?: $report->representative_email;
    }

    private function toBoolean($value) {
        return in_array($value, [true, 1, '1', 'Yes', 'yes'], true);
    }

    private function toNullableBoolean($value) {
        if ($value === null || $value === '') {
            return null;
        }

        return $this->toBoolean($value);
    }
}
