<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<div class="modal fade" id="edit-hub-{{ $hub->id }}" tabindex="-1" aria-labelledby="hubModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hubModalLabel">
                    <strong>HUB MANAGEMENT</strong>
                </h5>
                <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>

            <form id="hubForm" method="POST" action="{{ route('edit-hub', $hub->id) }}">
                @csrf
                <input type="hidden" id="hub_id" name="id" value="">
                
                <div class="modal-body">
                    <div class="mb-4">
                        <h6 class="mb-3"><strong>Hub Location Information</strong></h6>
                    </div>

                    <div class="row">
                        <!-- Region -->
                        <div class="col-md-6 mb-3">
                            <label for="modal_region" class="form-label">
                                Region <span class="text-danger">*</span>
                            </label>
                            <select name="region" id="modal_region" class="form-control" required>
                                <option value="">Please Select Region</option>
                                <option value="NCR" {{ $hub->region == 'NCR' ? 'selected' : '' }}>National Capital Region (NCR)</option>
                                <option value="CAR" {{ $hub->region == 'CAR' ? 'selected' : '' }}>Cordillera Administrative Region (CAR)</option>
                                <option value="Region I" {{ $hub->region == 'Region I' ? 'selected' : '' }}>Ilocos Region (Region I)</option>
                                <option value="Region II" {{ $hub->region == 'Region II' ? 'selected' : '' }}>Cagayan Valley (Region II)</option>
                                <option value="Region III" {{ $hub->region == 'Region III' ? 'selected' : '' }}>Central Luzon (Region III)</option>
                                <option value="Region IV-A" {{ $hub->region == 'Region IV-A' ? 'selected' : '' }}>CALABARZON (Region IV-A)</option>
                                <option value="Region IV-B" {{ $hub->region == 'Region IV-B' ? 'selected' : '' }}>MIMAROPA (Region IV-B)</option>
                                <option value="Region V" {{ $hub->region == 'Region V' ? 'selected' : '' }}>Bicol Region (Region V)</option>
                                <option value="Region VI" {{ $hub->region == 'Region VI' ? 'selected' : '' }}>Western Visayas (Region VI)</option>
                                <option value="Region VII" {{ $hub->region == 'Region VII' ? 'selected' : '' }}>Central Visayas (Region VII)</option>
                                <option value="Region VIII" {{ $hub->region == 'Region VIII' ? 'selected' : '' }}>Eastern Visayas (Region VIII)</option>
                                <option value="Region IX" {{ $hub->region == 'Region IX' ? 'selected' : '' }}>Zamboanga Peninsula (Region IX)</option>
                                <option value="Region X" {{ $hub->region == 'Region X' ? 'selected' : '' }}>Northern Mindanao (Region X)</option>
                                <option value="Region XI" {{ $hub->region == 'Region XI' ? 'selected' : '' }}>Davao Region (Region XI)</option>
                                <option value="Region XII" {{ $hub->region == 'Region XII' ? 'selected' : '' }}>SOCCSKSARGEN (Region XII)</option>
                                <option value="Region XIII" {{ $hub->region == 'Region XIII' ? 'selected' : '' }}>Caraga (Region XIII)</option>
                                <option value="BARMM" {{ $hub->region == 'BARMM' ? 'selected' : '' }}>Bangsamoro Autonomous Region in Muslim Mindanao (BARMM)</option>
                            </select>
                        </div>

                        <!-- Territory -->
                        <div class="col-md-6 mb-3">
                            <label for="modal_territory" class="form-label">
                                Territory <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="territory" 
                                   id="modal_territory" 
                                   class="form-control" 
                                   value="{{ $hub->territory }}"
                                   placeholder="Enter territory name" 
                                   required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Area -->
                        <div class="col-md-6 mb-3">
                            <label for="modal_area" class="form-label">
                                Area <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="area" 
                                   id="modal_area" 
                                   class="form-control" 
                                   value="{{ $hub->area }}"
                                   placeholder="Enter area name" 
                                   required>
                        </div>

                        <!-- Hub Status -->
                        <div class="col-md-6 mb-3">
                            <label for="modal_hub_status" class="form-label">
                                Hub Status <span class="text-danger">*</span>
                            </label>
                            <select name="hub_status" id="modal_hub_status" class="form-control" required>
                                <option value="">Please Select Status</option>
                                <option value="Open" {{ $hub->hub_status == 'Open' ? 'selected' : '' }}>Open</option>
                                <option value="Temporarily Closed" {{ $hub->hub_status == 'Temporarily Closed' ? 'selected' : '' }}>Temporarily Closed</option>
                                <option value="Inactive" {{ $hub->hub_status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="mb-3"><strong>Hub Details</strong></h6>
                    </div>

                    <div class="row">
                        <!-- Hub Name -->
                        <div class="col-md-6 mb-3">
                            <label for="modal_hub_name" class="form-label">
                                Hub Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="hub_name" 
                                   id="modal_hub_name" 
                                   class="form-control" 
                                   value="{{ $hub->hub_name }}"
                                   placeholder="Enter hub name" 
                                   required>
                        </div>

                        <!-- Hub Code -->
                        <div class="col-md-6 mb-3">
                            <label for="modal_hub_code" class="form-label">
                                Hub Code <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="hub_code" 
                                   id="modal_hub_code" 
                                   class="form-control" 
                                   value="{{ $hub->hub_code }}"
                                   placeholder="Enter unique hub code" 
                                   required>
                            <small class="form-text text-muted">Must be unique (e.g., HUB001, QC-MAIN)</small>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="mb-3">
                        <label for="modal_retail_hub_address" class="form-label">
                            Hub Address <span class="text-danger">*</span>
                        </label>
                        <textarea name="retail_hub_address" 
                                  id="modal_retail_hub_address" 
                                  class="form-control" 
                                  rows="3"
                                  placeholder="Enter complete hub address" 
                                  required>{{ $hub->retail_hub_address }}</textarea>
                    </div>

                    <!-- Google Map Link -->
                    <div class="mb-3">
                        <label for="modal_google_map_location_link" class="form-label">
                            Google Map Location Link
                        </label>
                        <input type="url" 
                               name="google_map_location_link" 
                               id="modal_google_map_location_link" 
                               class="form-control" 
                               value="{{ $hub->google_map_location_link }}"
                               placeholder="https://maps.google.com/...">
                        <small class="form-text text-muted">Optional: Paste the Google Maps URL for location reference</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="submitBtn">
                        <i class="bi bi-send"></i> Save Hub
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>