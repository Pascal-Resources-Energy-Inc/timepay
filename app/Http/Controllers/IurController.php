<?php

namespace App\Http\Controllers;

use App\IUR;
use App\EmployeeContactPerson;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class IurController extends Controller {
    public function index(Request $request) {
        $today = date('Y-m-d');
        $from = isset($request->from) ? $request->from : date('Y-m-d', (strtotime('-1 month', strtotime($today))));
        $to = isset($request->to) ? $request->to : date('Y-m-d');
        $status = isset($request->status) ? $request->status : 'Pending';

        $query = IUR::with('user')
            ->where('user_id', auth()->user()->id)
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to);

        if ($status === 'Closed') {
            $query->whereIn('status', ['Declined', 'Cancelled']);
        } else {
            $query->where('status', $status);
        }

        $iurs = $query
            ->orderBy('created_at', 'DESC')
            ->get();
        // dd($iurs);

        $iur_all = IUR::where('user_id', auth()->user()->id)->get();
        // dd($iur_all);
        // $all_approvers = $get_approvers->get_approvers(auth()->user()->id);

        return view('forms.iur.index', [
            'header' => 'forms',
            'from' => $from,
            'to' => $to,
            'status' => $status,
            'iurs' => $iurs,
            'iur_all' => $iur_all
        ]);
    }

    public function create() {
        return redirect()->route('iur.index', ['create' => 1]);
    }

    public function store(Request $request) {
        $request->validate([
            'request_for' => 'required|in:Uniform,ID,Both',
            'work_location' => 'required|in:HO,Plant,Guinobatan Warehouse / Lubao Office,Field',
            'type' => 'required|in:New Hire,Active Employee',
            'details' => 'required|string',
        ]);

        // for active employee
        $requiresUniformConditionImage = $request->type === 'Active Employee' && in_array($request->request_for, ['Uniform', 'Both']);
        $request->validate([
            'uniform_condition_images' => ($requiresUniformConditionImage ? 'required' : 'nullable')
                . '|array|max:3',
            'uniform_condition_images.*' => 'mimes:jpg,jpeg,png|max:5120',
        ], [
            'uniform_condition_images.required' => 'Please upload at least one attachment.',
            'uniform_condition_images.max' => 'You may upload a maximum of 3 images.',
            'uniform_condition_images.*.mimes' => 'Uniform condition proofs must be JPG, JPEG, or PNG images.',
            'uniform_condition_images.*.max' => 'Each attachment must not exceed 5MB.',
        ]);
        
        // UNIFORM VALIDATION
        if (in_array($request->request_for, ['Uniform', 'Both'])) {
            $uniformLimits = [
                'HO' => [
                    'prod_qty' => 0,
                    'white_qty' => 1,
                    'black_qty' => 1,
                    'collar_qty' => 2,
                ],
                'Plant' => [
                    'prod_qty' => 5,
                    'white_qty' => 1,
                    'black_qty' => 0,
                    'collar_qty' => 0,
                ],
                'Guinobatan Warehouse / Lubao Office' => [
                    'prod_qty' => 0,
                    'white_qty' => 1,
                    'black_qty' => 1,
                    'collar_qty' => 3,
                ],
                'Field' => [
                    'prod_qty' => 0,
                    'white_qty' => 0,
                    'black_qty' => 3,
                    'collar_qty' => 3,
                ],
            ];
            $locationLimits = $uniformLimits[$request->work_location];
            $rules = [];

            foreach ($locationLimits as $field => $max) {
                $rules[$field] = ($max > 0 ? 'required' : 'nullable') . '|integer|min:0|max:' . $max;
            }

            $validator = Validator::make($request->all(), $rules);

            $validator->after(function ($v) use ($request, $locationLimits) {
                $total = (int)$request->prod_qty + (int)$request->white_qty + (int)$request->black_qty + (int)$request->collar_qty;
                $totalLimit = array_sum($locationLimits);

                if ($total < 1) {
                    $v->errors()->add('white_qty', 'Required at least 1 uniform quantity.');
                }
                if ($total > $totalLimit) {
                    $v->errors()->add('white_qty', 'The total uniform quantity exceeds the allocation for this location.');
                }
            });

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $request->validate([
                'issued' => 'required',
                'size' => 'required',
            ]);

            if ($request->issued === 'Yes') {
                $request->validate([
                    'issued_remarks' => 'required|string',
                    'issued_reasons' => 'required|string',
                ]);
            }

            if ($request->issued === 'No') {
                $request->validate([
                    'issued_reasons' => 'required|string',
                ]);
            }

            if ($request->size === 'other') {
                $request->validate([
                    'other_size' => 'required|string|max:10',
                ]);
            }
        }

        if (in_array($request->request_for, ['ID', 'Both'])) {
            $request->validate([
                'id_request' => 'required|string',
                'id_picture' => 'required|mimes:jpg,jpeg,png|max:5120',
                'emergency_name' => 'required|string',
                'emergency_contact_number' => 'required|string',
                'emergency_relation' => 'required|string',
            ]);
        }

        try {
            // FILE UPLOAD
            $imagePath = null;

            if ($request->hasFile('id_picture')) {

                $uploadPath = public_path('uploads/id_pictures');

                $file = $request->file('id_picture');
                $filename = time() . '_id_' . uniqid() . '.' . $file->getClientOriginalExtension();

                $file->move($uploadPath, $filename);

                $imagePath = 'uploads/id_pictures/' . $filename;
            }

            $finalSize = null;

            if (in_array($request->request_for, ['Uniform', 'Both'])) {
                $finalSize = $request->size === 'other'
                    ? $request->other_size
                    : $request->size;
            }

            // Generate IUR Reference
            $latestIur = IUR::orderBy('id', 'desc')->first();

            if ($latestIur && $latestIur->iur_reference) {
                $number = intval(substr($latestIur->iur_reference, 4)) + 1;
            } else {
                $number = 1;
            }

            $iur_reference = 'UIR-' . str_pad($number, 5, '0', STR_PAD_LEFT);

            $new_iur = new IUR();
            $new_iur->iur_reference = $iur_reference;
            $new_iur->user_id = auth()->id();
            $new_iur->type = $request->type;
            $new_iur->work_location = $request->work_location;
            $new_iur->request_for = $request->request_for;
            $new_iur->details = $request->details;
            $new_iur->status = 'Pending';

            // Uniform
            $new_iur->issued = $request->issued ?? null;
            $new_iur->issued_remarks = $request->issued_remarks ?? null;
            $new_iur->issued_reasons = $request->issued_reasons ?? null;
            $new_iur->size = $finalSize;

            $new_iur->prod_qty = $request->prod_qty ?? 0;
            $new_iur->white_qty = $request->white_qty ?? 0;
            $new_iur->black_qty = $request->black_qty ?? 0;
            $new_iur->collar_qty = $request->collar_qty ?? 0;

            $new_iur->notes = $request->notes ?? null;

            // ID
            $new_iur->id_request = $request->id_request ?? null;
            $new_iur->id_picture = $imagePath;

            $new_iur->created_by = auth()->id();

            $new_iur->save();

            if ($request->hasFile('uniform_condition_images')) {
                $uploadPath = public_path('uploads/iur_uniform_conditions');

                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                foreach ($request->file('uniform_condition_images') as $file) {
                    $filename = time() . '_uniform_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move($uploadPath, $filename);

                    $new_iur->uniformConditionImages()->create([
                        'image_path' => 'uploads/iur_uniform_conditions/' . $filename,
                    ]);
                }
            }

            // Save/update emergency contact
            if (in_array($request->request_for, ['ID', 'Both'])) {
                $contact = EmployeeContactPerson::where('user_id', auth()->id())->first();
                if ($contact) {
                    $contact->name = $request->emergency_name;
                    $contact->contact_number = $request->emergency_contact_number;
                    $contact->relation = $request->emergency_relation;
                    $contact->save();
                } else {
                    $new = new EmployeeContactPerson;
                    $new->user_id = auth()->id();
                    $new->name = $request->emergency_name;
                    $new->contact_number = $request->emergency_contact_number;
                    $new->relation = $request->emergency_relation;
                    $new->save();
                }
            }

            // Send email to approver
            try {
                $approverLocations = collect();

                /*
                 * HO receives every request except:
                 * Plant work_location + Uniform-only request.
                 */
                $sendToHo = !($new_iur->work_location === 'Plant' && $new_iur->request_for === 'Uniform');

                if ($sendToHo) {
                    $approverLocations->push('HO');
                }

                /*
                 * Plant approver receives only:
                 * Plant + Uniform
                 * Plant + Both
                 *
                 */
                $sendToPlant = $new_iur->work_location === 'Plant' && in_array($new_iur->request_for, ['Uniform', 'Both'], true);

                if ($sendToPlant) {
                    $approverLocations->push('Lubao Office');
                }

                $approversEmail = \App\ApproverSetting::with('user')
                    ->where('type_of_form', 'uir')
                    ->where('status', 'Active')
                    ->whereHas('user.employee', function ($query) use ($approverLocations) {
                        $query->whereIn('location', $approverLocations->all());
                    })
                    ->get()
                    ->pluck('user.email')
                    ->filter()
                    ->unique();

                foreach ($approversEmail as $email) {
                    Mail::to($email)->send(
                        new \App\Mail\IurRequestMail($new_iur, 'Pending')
                    );
                }
            } catch (\Exception $e) {
                \Log::warning(
                    'IUR email failed but request saved: ' . $e->getMessage()
                );
            }

            Alert::success('Request sent')->persistent('Dismiss');
            return back();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show($id) {
        $iur = IUR::with('uniformConditionImages')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('forms.iur.view', [
            'header' => 'forms',
            'iur' => $iur,
        ]);
    }

    // view edit form
    public function edit($id) {
        $iur = IUR::with('uniformConditionImages')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('forms.iur.edit', [
            'header' => 'forms',
            'iur' => $iur
        ]);
    }

    public function update(Request $request, $id) {
        $iur = IUR::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $request->validate([
            'request_for' => 'required|in:Uniform,ID,Both',
            'work_location' => 'required|in:HO,Plant,Guinobatan Warehouse / Lubao Office,Field',
            'type' => 'required|in:New Hire,Active Employee',
            'details' => 'required|string',
        ]);

        $requiresUniformConditionImage = $request->type === 'Active Employee'
            && in_array($request->request_for, ['Uniform', 'Both']);

        $request->validate([
            'uniform_condition_images' => 'nullable|array|max:3',
            'uniform_condition_images.*' => 'mimes:jpg,jpeg,png|max:5120',
            'remove_uniform_condition_images' => 'nullable|array',
            'remove_uniform_condition_images.*' => 'integer|distinct',
        ], [
            'uniform_condition_images.max' => 'You may upload a maximum of 3 uniform condition proof images.',
            'uniform_condition_images.*.mimes' => 'Attachment must be JPG, JPEG, or PNG images.',
            'uniform_condition_images.*.max' => 'Each attachment must not exceed 5MB.',
        ]);

        $iur->load('uniformConditionImages');

        $removeImageIds = collect($request->input('remove_uniform_condition_images', []));
        $imagesToRemove = $iur->uniformConditionImages
            ->filter(function ($image) use ($removeImageIds) {
                return $removeImageIds->contains((string) $image->id)
                    || $removeImageIds->contains($image->id);
            });
        $legacyImageCount = $iur->uniformConditionImages->isEmpty() && $iur->uniform_condition_image ? 1 : 0;
        $remainingImageCount = $iur->uniformConditionImages->count() - $imagesToRemove->count() + $legacyImageCount;
        $newImageCount = count($request->file('uniform_condition_images', []));
        $finalImageCount = $remainingImageCount + $newImageCount;

        if ($finalImageCount > 3) {
            return back()
                ->withErrors(['uniform_condition_images' => 'You may upload a maximum of 3 uniform condition images.'])
                ->withInput();
        }

        if ($requiresUniformConditionImage && $finalImageCount < 1) {
            return back()
                ->withErrors(['uniform_condition_images' => 'At least one uniform condition image is required.'])
                ->withInput();
        }

        // set default value for "Both" option
        // if ($request->request_for === 'Both') {
        //     $request->merge([
        //         'white_qty' => 1,
        //         'black_qty' => 1,
        //         'collar_qty' => 1,
        //         'prod_qty' => 1
        //     ]);
        // }

        // uniform validation
        if (in_array($request->request_for, ['Uniform', 'Both'])) {
            $uniformLimits = [
                'HO' => [
                    'prod_qty' => 0,
                    'white_qty' => 1,
                    'black_qty' => 1,
                    'collar_qty' => 2,
                ],
                'Plant' => [
                    'prod_qty' => 5,
                    'white_qty' => 1,
                    'black_qty' => 0,
                    'collar_qty' => 0,
                ],
                'Guinobatan Warehouse / Lubao Office' => [
                    'prod_qty' => 0,
                    'white_qty' => 1,
                    'black_qty' => 1,
                    'collar_qty' => 3,
                ],
                'Field' => [
                    'prod_qty' => 0,
                    'white_qty' => 0,
                    'black_qty' => 3,
                    'collar_qty' => 3,
                ],
            ];
            $locationLimits = $uniformLimits[$request->work_location];
            $rules = [];

            foreach ($locationLimits as $field => $max) {
                $rules[$field] = ($max > 0 ? 'required' : 'nullable') . '|integer|min:0|max:' . $max;
            }

            $validator = Validator::make($request->all(), $rules);

            $validator->after(function ($v) use ($request, $locationLimits) {
                $total = (int)$request->prod_qty
                    + (int)$request->white_qty
                    + (int)$request->black_qty
                    + (int)$request->collar_qty;
                $totalLimit = array_sum($locationLimits);

                if ($total < 1) {
                    $v->errors()->add('white_qty', 'Required at least 1 uniform quantity.');
                }

                if ($total > $totalLimit) {
                    $v->errors()->add('white_qty', 'The total uniform quantity exceeds the allocation for this location.');
                }
            });

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $request->validate([
                'issued' => 'required',
                'size' => 'required'
            ]);

            if ($request->issued === 'Yes') {
                $request->validate([
                    'issued_remarks' => 'required|string',
                    'issued_reasons' => 'required|string',
                ]);
            }

            if ($request->issued === 'No') {
                $request->validate([
                    'issued_reasons' => 'required|string',
                ]);
            }

            if ($request->size === 'other') {
                $request->validate([
                    'other_size' => 'required|string|max:10',
                ]);
            }
        }

        if (in_array($request->request_for, ['ID', 'Both'])) {
            $request->validate([
                'id_request' => 'required|string',
                'id_picture' => ($iur->id_picture ?  'nullable' : 'required') . '|mimes:jpg,jpeg,png|max:5120',
                'emergency_name' => 'required|string',
                'emergency_contact_number' => 'required|string',
                'emergency_relation' => 'required|string',
            ]);
        }

        // Handle image
        if ($request->hasFile('id_picture')) {
            $uploadPath = public_path('uploads/id_pictures');

            // delete old
            if ($iur->id_picture && file_exists(public_path($iur->id_picture))) {
                unlink(public_path($iur->id_picture));
            }

            $file = $request->file('id_picture');
            $filename = time() . '_id_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);

            $iur->id_picture = 'uploads/id_pictures/' . $filename;
        }

        foreach ($imagesToRemove as $image) {
            if ($image->image_path && file_exists(public_path($image->image_path))) {
                unlink(public_path($image->image_path));
            }

            $image->delete();
        }

        if ($request->hasFile('uniform_condition_images')) {
            $uploadPath = public_path('uploads/iur_uniform_conditions');

            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            foreach ($request->file('uniform_condition_images') as $file) {
                $filename = time() . '_uniform_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);

                $iur->uniformConditionImages()->create([
                    'image_path' => 'uploads/iur_uniform_conditions/' . $filename,
                ]);
            }
        }

        // Size logic
        $isUniformRequest = in_array(
            $request->request_for,
            ['Uniform', 'Both'],
            true
        );

        $finalSize = null;

        // remove unnecessary size details when the request is just ID only
        if ($isUniformRequest) {
            $finalSize = $request->size === 'other'
                ? $request->other_size
                : $request->size;
        }

        $iur->update([
            'request_for' => $request->request_for,
            'work_location' => $request->work_location,
            'type' => $request->type,
            'details' => $request->details,
            'issued' => $isUniformRequest ? $request->issued : null,
            'issued_remarks' => $isUniformRequest ? $request->issued_remarks : null,
            'issued_reasons' => $isUniformRequest ? $request->issued_reasons : null,
            'size' => $finalSize,
            'notes' => $request->notes,
            'id_request' => in_array($request->request_for, ['ID', 'Both'], true)
                ? $request->id_request
                : null,
            'prod_qty' => $isUniformRequest ? ($request->prod_qty ?? 0) : 0,
            'white_qty' => $isUniformRequest ? ($request->white_qty ?? 0) : 0,
            'black_qty' => $isUniformRequest ? ($request->black_qty ?? 0) : 0,
            'collar_qty' => $isUniformRequest ? ($request->collar_qty ?? 0) : 0,
        ]);

        // Save/update emergency contact
        if (in_array($request->request_for, ['ID', 'Both'])) {
            $contact = EmployeeContactPerson::where('user_id', $iur->user_id)->first();
            if ($contact) {
                $contact->name = $request->emergency_name;
                $contact->contact_number = $request->emergency_contact_number;
                $contact->relation = $request->emergency_relation;
                $contact->save();
            } else {
                $new = new EmployeeContactPerson;
                $new->user_id = $iur->user_id;
                $new->name = $request->emergency_name;
                $new->contact_number = $request->emergency_contact_number;
                $new->relation = $request->emergency_relation;
                $new->save();
            }
        }

        Alert::success('Updated successfully')->persistent('Dismiss');
        return back();
    }

    public function cancel($id) {
        $iur = IUR::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($iur->status !== 'Pending') {
            return back()->with('error', 'Only pending requests can be cancelled.');
        }

        $iur->status = 'Cancelled';
        $iur->save();

        Alert::success('Request cancelled successfully.')->persistent('Dismiss');
        return back();
    }
}
