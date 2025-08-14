<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<div class="modal fade" id="hubModal" tabindex="-1" aria-labelledby="hubModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hubModalLabel">
                    <strong>HUB MANAGEMENT</strong>
                </h5>
                <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>

            <form id="hubForm" method="POST" action="new-hub">
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
                                <option value="NCR">National Capital Region (NCR)</option>
                                <option value="CAR">Cordillera Administrative Region (CAR)</option>
                                <option value="Region I">Ilocos Region (Region I)</option>
                                <option value="Region II">Cagayan Valley (Region II)</option>
                                <option value="Region III">Central Luzon (Region III)</option>
                                <option value="Region IV-A">CALABARZON (Region IV-A)</option>
                                <option value="Region IV-B">MIMAROPA (Region IV-B)</option>
                                <option value="Region V">Bicol Region (Region V)</option>
                                <option value="Region VI">Western Visayas (Region VI)</option>
                                <option value="Region VII">Central Visayas (Region VII)</option>
                                <option value="Region VIII">Eastern Visayas (Region VIII)</option>
                                <option value="Region IX">Zamboanga Peninsula (Region IX)</option>
                                <option value="Region X">Northern Mindanao (Region X)</option>
                                <option value="Region XI">Davao Region (Region XI)</option>
                                <option value="Region XII">SOCCSKSARGEN (Region XII)</option>
                                <option value="Region XIII">Caraga (Region XIII)</option>
                                <option value="BARMM">Bangsamoro Autonomous Region in Muslim Mindanao (BARMM)</option>
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
                                <option value="Open">Open</option>
                                <option value="Temporarily Closed">Temporarily Closed</option>
                                <option value="Inactive">Inactive</option>
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
                                  required></textarea>
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
                               placeholder="https://maps.app.goo.gl/....">
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
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const googleMapInput = document.getElementById('modal_google_map_location_link');
    const coordinatesDisplay = document.createElement('div');
    coordinatesDisplay.id = 'coordinates-display';
    coordinatesDisplay.className = 'mt-2';
    coordinatesDisplay.style.display = 'none';
    
    if (googleMapInput) {
        googleMapInput.parentNode.insertBefore(coordinatesDisplay, googleMapInput.nextSibling);
        
        googleMapInput.addEventListener('input', function() {
            const url = this.value.trim();
            if (url) {
                const coordinates = extractLatLngFromUrl(url);
                displayCoordinates(coordinates);
            } else {
                hideCoordinates();
            }
        });
        
        googleMapInput.addEventListener('paste', function() {
            setTimeout(() => {
                const url = this.value.trim();
                if (url) {
                    const coordinates = extractLatLngFromUrl(url);
                    displayCoordinates(coordinates);
                }
            }, 100);
        });
    }
    
    function extractLatLngFromUrl(url) {
        let lat = null;
        let lng = null;
        
        try {
            
            let matches = url.match(/@(-?\d+\.?\d*),(-?\d+\.?\d*)/);
            if (matches) {
                lat = parseFloat(matches[1]);
                lng = parseFloat(matches[2]);
                return { lat, lng, source: 'Standard URL' };
            }
            
            matches = url.match(/\/place\/[^\/]+\/@(-?\d+\.?\d*),(-?\d+\.?\d*)/);
            if (matches) {
                lat = parseFloat(matches[1]);
                lng = parseFloat(matches[2]);
                return { lat, lng, source: 'Place URL' };
            }
            
            matches = url.match(/[?&]q=(-?\d+\.?\d*),(-?\d+\.?\d*)/);
            if (matches) {
                lat = parseFloat(matches[1]);
                lng = parseFloat(matches[2]);
                return { lat, lng, source: 'Query Parameter' };
            }
            
            matches = url.match(/[?&]ll=(-?\d+\.?\d*),(-?\d+\.?\d*)/);
            if (matches) {
                lat = parseFloat(matches[1]);
                lng = parseFloat(matches[2]);
                return { lat, lng, source: 'LL Parameter' };
            }
            
            if (url.match(/(?:maps\.app\.goo\.gl|goo\.gl\/maps)/)) {
                return { lat: null, lng: null, source: 'Shortened URL', needsResolution: true };
            }
            
        } catch (error) {
            console.error('Error extracting coordinates:', error);
        }
        
        return { lat: null, lng: null, source: 'Unknown' };
    }
    
    function displayCoordinates(coordinates) {
        if (coordinates.lat && coordinates.lng) {
            coordinatesDisplay.innerHTML = `
                <div class="alert alert-success alert-sm mb-0">
                    <i class="bi bi-geo-alt-fill"></i> 
                    <strong>Coordinates detected:</strong> 
                    Latitude: ${coordinates.lat}, Longitude: ${coordinates.lng}
                    <small class="text-muted">(${coordinates.source})</small>
                </div>
            `;
            coordinatesDisplay.style.display = 'block';
        } else if (coordinates.needsResolution) {
            coordinatesDisplay.innerHTML = `
                <div class="alert alert-warning alert-sm mb-0">
                    <i class="bi bi-hourglass-split"></i> 
                    <strong>Shortened URL detected.</strong> 
                    Coordinates will be extracted when you save the hub.
                </div>
            `;
            coordinatesDisplay.style.display = 'block';
        } else {
            coordinatesDisplay.innerHTML = `
                <div class="alert alert-warning alert-sm mb-0">
                    <i class="bi bi-exclamation-triangle"></i> 
                    <strong>No coordinates found.</strong> 
                    Please check if the Google Maps URL is valid.
                </div>
            `;
            coordinatesDisplay.style.display = 'block';
        }
    }
    
    function hideCoordinates() {
        coordinatesDisplay.style.display = 'none';
    }
});
</script>