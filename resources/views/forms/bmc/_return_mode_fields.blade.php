@php
    $returnModeHints = [
        'Drop off' => 'e.g., HO, Plant, Warehouse',
        'Courier' => 'e.g., SPX4321, Tracking No. 4123',
        'Officer Name' => 'e.g., Designation - Name',
        'Field location' => 'e.g., Field location',
    ];
@endphp

<div class="card border shadow-xs mb-3">
    <div class="card-header bg-light py-2 rounded-top">
        <h6 class="mb-0 font-weight-bold">
            Mode of Return
        </h6>
    </div>
    <div class="card-body">
        @if ($modeError)
            <div class="alert alert-danger py-2" role="alert">{{ $modeError }}</div>
        @endif
        <div class="row">
            <div class="col-md-5 form-group mb-md-0">
                <label class="font-weight-bold small text-uppercase" for="{{ $fieldPrefix }}Type">
                    Type <span class="text-danger" aria-hidden="true">*</span>
                </label>
                <select class="form-control" id="{{ $fieldPrefix }}Type" name="return_mode"
                    onchange="this.form.elements.namedItem('return_mode_details').placeholder = this.options[this.selectedIndex].dataset.detailPlaceholder || '';"
                    required>
                    <option value="">-- Select Type --</option>
                    @foreach (\App\BmcAccountabilityReturn::RETURN_MODES as $mode => $label)
                        <option value="{{ $mode }}" data-detail-placeholder="{{ $returnModeHints[$mode] }}"
                            {{ $selectedMode === $mode ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-7 form-group mb-0">
                <label class="font-weight-bold small text-uppercase" for="{{ $fieldPrefix }}Details">
                    Details <span class="text-danger" aria-hidden="true">*</span>
                </label>
                <input type="text" class="form-control" id="{{ $fieldPrefix }}Details"
                    name="return_mode_details" maxlength="1900"
                    placeholder="{{ is_string($selectedMode) ? ($returnModeHints[$selectedMode] ?? '') : '' }}"
                    value="{{ is_string($details) ? $details : '' }}" required>
            </div>
        </div>
    </div>
</div>
