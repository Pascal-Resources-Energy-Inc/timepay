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

            <!-- Add Nav Tabs -->
            <ul class="nav nav-tabs px-3 pt-2" id="managementTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" 
                            id="hub-tab" 
                            data-toggle="tab" 
                            data-target="#hub-content" 
                            type="button" 
                            role="tab" 
                            aria-controls="hub-content" 
                            aria-selected="true">
                        Hub Management
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" 
                            id="user-tab" 
                            data-toggle="tab" 
                            data-target="#user-content" 
                            type="button" 
                            role="tab" 
                            aria-controls="user-content" 
                            aria-selected="false">
                        User
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Hub Management Tab (Your existing content) -->
                <div class="tab-pane fade show active" id="hub-content" role="tabpanel" aria-labelledby="hub-tab">
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

                            <div class="mb-4">
                                <h6 class="mb-3"><strong>Coordinates</strong></h6>
                            </div>

                            <div class="row">
                                <!-- Latitude -->
                                <div class="col-md-6 mb-3">
                                    <label for="modal_lat" class="form-label">
                                        Latitude
                                    </label>
                                    <input type="number" 
                                           step="any" 
                                           name="lat" 
                                           id="modal_lat" 
                                           class="form-control" 
                                           value="{{ $hub->lat }}"
                                           placeholder="Enter latitude (e.g., 14.6042)" 
                                           min="-90" 
                                           max="90">
                                    <small class="form-text text-muted">Optional: Enter latitude for precise location mapping</small>
                                </div>

                                <!-- Longitude -->
                                <div class="col-md-6 mb-3">
                                    <label for="modal_long" class="form-label">
                                        Longitude
                                    </label>
                                    <input type="number" 
                                           step="any" 
                                           name="long" 
                                           id="modal_long" 
                                           class="form-control" 
                                           value="{{ $hub->long }}"
                                           placeholder="Enter longitude (e.g., 121.0700)" 
                                           min="-180" 
                                           max="180">
                                    <small class="form-text text-muted">Optional: Enter longitude for precise location mapping</small>
                                </div>
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

                <!-- User Tab -->
                <div class="tab-pane fade" id="user-content" role="tabpanel" aria-labelledby="user-tab">
                    <form id="userForm" method="POST" action="{{ route('create-user-for-hub') }}">
                    @csrf
                    <input type="hidden" id="user_id" name="id" value="">
                    
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="hub_id" value="{{ $hub->id }}">
                                <div class="col-md-6 mb-3">
                                    <label for="user_hub_location" class="form-label">
                                        Hub Location <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           name="hub_location" 
                                           id="user_hub_location" 
                                           class="form-control"
                                           value="{{ $hub->territory}} - {{ $hub->hub_name}} Hub"
                                           placeholder="Enter hub location" 
                                           required>
                                </div>


                                <!-- Employee -->
                                <div class="col-md-6 mb-3">
                                    <label for="user_employee" class="form-label">
                                        Employee <span class="text-danger">*</span>
                                    </label>
                                    
                                    <select data-placeholder="Filter By Employee" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='employee[]' multiple>
                                        <option value="">Please Select Employee</option>
                                        @if(isset($users))
                                            @foreach($users->where('login', 1) as $user)
                                                @if($user->employee && $user->employee->employee_number)
                                                    @php
                                                        // Check if this user is already assigned to any hub
                                                        $isAssigned = DB::table('hub_per_location_id')
                                                            ->where('user_id', $user->id)  // Changed from employee_number to user_id
                                                            ->exists();
                                                    @endphp
                                                    @if(!$isAssigned)
                                                        <option value="{{ $user->id }}" {{ isset($selectedEmployee) && $selectedEmployee->id == $user->id ? 'selected' : '' }}>
                                                            {{ $user->employee->employee_number }} - {{ $user->name }}
                                                        </option>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                    <small class="form-text text-muted">Only employees not assigned to any hub are shown</small>
                                </div>
                            </div>
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="bi bi-people"></i> Hub Employee Assignments
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if(isset($hub->hubAssignments) && $hub->hubAssignments->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-bordered" style="font-size: 14px; width: 100%;">
                                                <thead style="background-color: #f8f9fa;">
                                                    <tr>
                                                        <th style="width: 25%; padding: 12px; border: 1px solid #dee2e6;">Hub Name</th>
                                                        <th style="width: 25%; padding: 12px; border: 1px solid #dee2e6;">Employee Number</th>
                                                        <th style="width: 35%; padding: 12px; border: 1px solid #dee2e6;">Employee Name</th>
                                                        <th style="width: 15%; padding: 12px; text-align: center; border: 1px solid #dee2e6;">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($hub->hubAssignments as $assignment)
                                                        <tr>
                                                            <td style="padding: 12px; border: 1px solid #dee2e6;">{{ $assignment->hub_name }}</td>
                                                            <td style="padding: 12px; border: 1px solid #dee2e6;">{{ $assignment->employee_number }}</td>
                                                            <td style="padding: 12px; border: 1px solid #dee2e6;">{{ $assignment->employee_name }}</td>
                                                            <td style="padding: 12px; text-align: center; border: 1px solid #dee2e6;">
                                                                <button type="button" 
                                                                        class="btn btn-sm btn-outline-danger" 
                                                                        onclick="removeAssignmentById('{{ $assignment->user_id }}', '{{ $assignment->hub_per_location_id }}', '{{ $assignment->employee_name }}', '{{ $assignment->hub_name }}')"
                                                                        title="Remove Assignment">
                                                                    <i class="bi bi-person-dash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <!-- Summary Info -->
                                        <div class="mt-3">
                                            <small class="text-muted">
                                                <i class="bi bi-info-circle"></i> 
                                                Total Assignments: <strong>{{ $hub->hubAssignments->count() }}</strong>
                                            </small>
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="bi bi-person-x fs-1 text-muted"></i>
                                            <h5 class="text-muted mt-2">No Employee Assignments Found</h5>
                                            <p class="text-muted">No employees have been assigned to this hub yet.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-person-plus"></i> Save User
                            </button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                Close
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    function initializeTabs() {
        const tabButtons = document.querySelectorAll('[data-toggle="tab"]');
        
        tabButtons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const modal = this.closest('.modal');
                if (!modal) return;
                
                const targetId = this.getAttribute('data-target');
                const targetPanel = modal.querySelector(targetId);
                
                if (targetPanel) {
                    const modalTabButtons = modal.querySelectorAll('[data-toggle="tab"]');
                    const modalTabContents = modal.querySelectorAll('.tab-pane');
                    
                    modalTabButtons.forEach(function(btn) {
                        btn.classList.remove('active');
                        btn.setAttribute('aria-selected', 'false');
                    });
                    
                    modalTabContents.forEach(function(content) {
                        content.classList.remove('show', 'active');
                    });
                    this.classList.add('active');
                    this.setAttribute('aria-selected', 'true');
                    targetPanel.classList.add('show', 'active');
                }
            });
        });
    }
    
    initializeTabs();
    
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                const hasNewModal = Array.from(mutation.addedNodes).some(node => 
                    node.nodeType === Node.ELEMENT_NODE && 
                    (node.classList && node.classList.contains('modal') || node.querySelector('.modal'))
                );
                
                if (hasNewModal) {
                    setTimeout(initializeTabs, 100);
                }
            }
        });
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
    
    document.addEventListener('show.bs.modal', function(event) {
        const modal = event.target;
        resetModalToFirstTab(modal);
    });
    
    document.addEventListener('show.modal', function(event) {
        const modal = event.target;
        resetModalToFirstTab(modal);
    });
    
    function resetModalToFirstTab(modal) {
        setTimeout(function() {
            const hubTab = modal.querySelector('#hub-tab');
            const userTab = modal.querySelector('#user-tab');
            const hubContent = modal.querySelector('#hub-content');
            const userContent = modal.querySelector('#user-content');
            
            if (hubTab && userTab && hubContent && userContent) {
                hubTab.classList.add('active');
                hubTab.setAttribute('aria-selected', 'true');
                userTab.classList.remove('active');
                userTab.setAttribute('aria-selected', 'false');
                
                hubContent.classList.add('show', 'active');
                userContent.classList.remove('show', 'active');
            }
        }, 50);
    }
});
</script>

<script>
    function removeAssignmentById(userId, hubId, employeeName, hubName) {
    if (confirm(`Are you sure you want to remove ${employeeName} from ${hubName}?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("remove-user-from-hub-by-id") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        const userIdInput = document.createElement('input');
        userIdInput.type = 'hidden';
        userIdInput.name = 'user_id';
        userIdInput.value = userId;
        form.appendChild(userIdInput);
        
        const hubIdInput = document.createElement('input');
        hubIdInput.type = 'hidden';
        hubIdInput.name = 'hub_id';
        hubIdInput.value = hubId;
        form.appendChild(hubIdInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>