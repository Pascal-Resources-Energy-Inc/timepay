<style>
.select2-container--open {
    z-index: 9999 !important;
}
.select2-dropdown {
    z-index: 9999 !important;
}
.select2-container--default .select2-selection--single {
    height: calc(1.5em + .75rem + 2px);
    padding: .375rem .75rem;
    font-size: 1rem;
    border: 1px solid #ced4da;
    border-radius: .25rem;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 1.5;
    color: #495057;
    padding-left: 0;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: calc(1.5em + .75rem + 2px);
}
</style>

<div class="modal fade" id="registerDealer" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <form action="{{ route('tds.store') }}" method="POST" id="dealerForm" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="latitude" id="hidden_latitude">
        <input type="hidden" name="longitude" id="hidden_longitude">
        
        <div class="modal-header text-black">
          <h5 class="modal-title">Register New Dealer</h5>
          <button type="button" class="close text-white" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h5 class="mb-3 text-primary">General Details</h5>
          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Date of Registration <span class="text-danger">*</span></label>
                <input type="date" class="form-control" 
                       name="date_registered" 
                       value="{{ old('date_registered', date('Y-m-d')) }}" 
                       max="{{ date('Y-m-d') }}" 
                       required>
                <small class="form-text text-muted">When the dealer signed-up</small>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Employee Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" 
                       name="employee_name" value="{{ old('employee_name', Auth::user()->name) }}" 
                       placeholder="Who acquired the dealer?" readonly>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Area <span class="text-danger">*</span></label>
                <select class="form-control" name="area" required onclick="event.stopPropagation();">
                  <option value="">-- Select Area --</option>
                  @foreach($regions as $region)
                    <option value="{{ $region->id }}" {{ old('area') == $region->id ? 'selected' : '' }}>
                      {{ $region->region }} - {{ $region->province }}{{ $region->district ? ' - ' . $region->district : '' }}
                    </option>
                  @endforeach
                </select>
                <small class="form-text text-muted">Select region and province</small>
              </div>
            </div>
          </div>

          <hr class="my-4">
          <h5 class="mb-3 text-primary">Customer Information</h5>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Customer Type <span class="text-danger">*</span></label>
                <select class="form-control" name="customer_type" id="customer_type" required>
                  <option value="">-- Select Customer Type --</option>
                  <option value="new">New Customer</option>
                  <option value="existing">Existing Customer</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row" id="existing_customer_section" style="display: none;">
              <div class="col-md-12">
                  <div class="form-group">
                      <label>Select Existing Customer <span class="text-danger">*</span></label>
                      <select id="existing_customer_select"
                              name="existing_customer_select"
                              style="width:100%;"></select>
                      <small class="form-text text-muted">Search by customer name, business name, or contact number</small>
                  </div>
              </div>
          </div>
          <div id="customer_details_fields">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Customer Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" 
                        name="customer_name" id="customer_name" value="{{ old('customer_name') }}" 
                        placeholder="Full name of the customer" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Contact Number <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" 
                        name="contact_no" id="contact_no" value="{{ old('contact_no') }}" 
                        placeholder="09453658795" required>
                  <small class="form-text text-muted">Mobile number</small>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Business Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" 
                        name="business_name" id="business_name" value="{{ old('business_name') }}" 
                        placeholder="e.g., Justin's Store" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Business Type <span class="text-danger">*</span></label>
                  <select class="form-control" name="business_type" id="business_type" required onclick="event.stopPropagation();">
                    <option value="">-- Select Business Type --</option>
                    <option value="Sari-Sari Store" {{ old('business_type') == 'Sari-Sari Store' ? 'selected' : '' }}>Sari-Sari Store</option>
                    <option value="Mini Mart" {{ old('business_type') == 'Mini Mart' ? 'selected' : '' }}>Mini Mart</option>
                    <option value="Retail Shop" {{ old('business_type') == 'Retail Shop' ? 'selected' : '' }}>Retail Shop</option>
                    <option value="Wholesale" {{ old('business_type') == 'Wholesale' ? 'selected' : '' }}>Wholesale</option>
                    <option value="Grocery" {{ old('business_type') == 'Grocery' ? 'selected' : '' }}>Grocery</option>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <input type="hidden" name="customer_name_hidden" id="customer_name_hidden">
          <input type="hidden" name="contact_no_hidden" id="contact_no_hidden">
          <input type="hidden" name="business_name_hidden" id="business_name_hidden">
          <input type="hidden" name="business_type_hidden" id="business_type_hidden">

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Business Location <span class="text-danger">*</span></label>
                <small class="form-text text-muted mb-2">Select region, province, city and barangay in sequence</small>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Region <span class="text-danger">*</span></label>
                <select class="form-control" id="location_region" name="location_region" required onclick="event.stopPropagation();">
                  <option value="">-- Select Region --</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Province <span class="text-danger">*</span></label>
                <select class="form-control" id="location_province" name="location_province" required onclick="event.stopPropagation();" disabled>
                  <option value="">-- Select Region First --</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>City/Municipality <span class="text-danger">*</span></label>
                <select class="form-control" id="location_city" name="location_city" required onclick="event.stopPropagation();" disabled>
                  <option value="">-- Select Province First --</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Barangay <span class="text-danger">*</span></label>
                <input type="text" class="form-control" 
                       name="location_barangay" id="location_barangay" value="{{ old('location_barangay') }}" 
                       placeholder="e.g., Barangay 1" required>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Postal Code</span></label>
                <input type="text" class="form-control" 
                       name="postal_code" id="postal_code" value="{{ old('postal_code') }}" 
                       placeholder="e.g., 1121">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Street Name, Building, House No. <span class="text-danger">*</span></label>
                <input type="text" class="form-control" 
                       name="street_address" id="street_address" value="{{ old('street_address') }}" 
                       placeholder="e.g., 1868 Kapalaran St" required>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Pin Exact Location</span></label>
                <div class="alert alert-warning d-flex align-items-start" role="alert">
                  <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16" style="min-width: 24px; margin-right: 10px;">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                  </svg>
                  <div>
                    <strong>Place an accurate pin</strong><br>
                    <small>We will deliver to your map location. Please check if it is correct, else click the map to adjust the pin location.</small>
                  </div>
                </div>
                <div id="location_map" style="height: 400px; border-radius: 8px; border: 2px solid #dee2e6;"></div>
                <div class="mt-2 p-2 bg-light rounded">
                  <strong>Current Pin Location:</strong><br>
                  Latitude: <span id="display_lat">--</span>, Longitude: <span id="display_lng">--</span>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Complete Address Preview</label>
                <textarea class="form-control bg-light" id="full_address_preview" rows="2" readonly></textarea>
                <input type="hidden" name="location" id="location_hidden">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Business Image <span class="text-danger">*</span></label>
                <input type="file" class="form-control-file" 
                      name="business_image" 
                      accept="image/jpeg,image/jpg,image/png"
                      required>
                <small class="form-text text-muted">Upload business photo (JPG, JPEG, PNG - Max 5MB)</small>
                <div id="imagePreview" class="mt-2" style="display: none;">
                  <img src="" alt="Preview" style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px; padding: 5px;">
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Awarded Area</label>
                <input type="text" class="form-control" 
                       name="awarded_area" value="{{ old('awarded_area') }}" 
                       placeholder="For Area Distributors">
                <small class="form-text text-muted">Only applicable for Area Distributors</small>
              </div>
            </div>
          </div>

          <hr class="my-4">
          <h5 class="mb-3 text-primary">Package & Program Details</h5>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Package Type <span class="text-danger">*</span></label>
                <select class="form-control" name="package_type" required onclick="event.stopPropagation();">
                  <option value="">-- Select Package --</option>
                  <option value="EU" {{ old('package_type') == 'EU' ? 'selected' : '' }}>EU - End User</option>
                  <option value="D" {{ old('package_type') == 'D' ? 'selected' : '' }}>D - Dealer</option>
                  <option value="MD" {{ old('package_type') == 'MD' ? 'selected' : '' }}>MD - Mega Dealer</option>
                  <option value="AD" {{ old('package_type') == 'AD' ? 'selected' : '' }}>AD - Area Distributor</option>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Purchase Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control" 
                       name="purchase_amount" value="{{ old('purchase_amount') }}" 
                       placeholder="25000" min="0" step="0.01" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Lead Generator <span class="text-danger">*</span></label>
                <select class="form-control" name="lead_generator" id="lead_generator" required onclick="event.stopPropagation();">
                  <option value="">-- Select Lead Generator --</option>
                  <option value="FB" {{ old('lead_generator') == 'FB' ? 'selected' : '' }}>FB</option>
                  <option value="Shopee" {{ old('lead_generator') == 'Shopee' ? 'selected' : '' }}>Shopee</option>
                  <option value="Gaz Lite Website" {{ old('lead_generator') == 'Gaz Lite Website' ? 'selected' : '' }}>Gaz Lite Website</option>
                  <option value="Events" {{ old('lead_generator') == 'Events' ? 'selected' : '' }}>Events</option>
                  <option value="Kaagapay" {{ old('lead_generator') == 'Kaagapay' ? 'selected' : '' }}>Kaagapay</option>
                  <option value="Referral" {{ old('lead_generator') == 'Referral' ? 'selected' : '' }}>Referral</option>
                  <option value="MFI" {{ old('lead_generator') == 'MFI' ? 'selected' : '' }}>MFI</option>
                  <option value="MD" {{ old('lead_generator') == 'MD' ? 'selected' : '' }}>MD</option>
                  <option value="PD" {{ old('lead_generator') == 'PD' ? 'selected' : '' }}>PD</option>
                  <option value="AD" {{ old('lead_generator') == 'AD' ? 'selected' : '' }}>AD</option>
                  <option value="Own Accounts" {{ old('lead_generator') == 'Own Accounts' ? 'selected' : '' }}>Own Accounts</option>
                </select>
                <small class="form-text text-muted">Source of the lead</small>
              </div>
            </div>

            <div class="col-md-4" id="reference_field" style="display: none;">
              <div class="form-group">
                <label>Reference Number <span class="text-danger reference-required">*</span></label>
                <input type="text" class="form-control" 
                      name="lead_reference" id="lead_reference" value="{{ old('lead_reference') }}" 
                      placeholder="Enter reference number or link">
                <small class="form-text text-muted">Required for FB, Shopee, and Gaz Lite Website</small>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Program Type <span class="text-danger">*</span></label>
                <select class="form-control" name="program_type" id="program_type" onclick="event.stopPropagation();" required>
                  <option value="">-- Select Program Type --</option>
                  <option value="Roadshow" {{ old('program_type') == 'Roadshow' ? 'selected' : '' }}>Roadshow</option>
                  <option value="Mini-Roadshow" {{ old('program_type') == 'Mini-Roadshow' ? 'selected' : '' }}>Mini-Roadshow</option>
                  <option value="Non-Roadshow" {{ old('program_type') == 'Non-Roadshow' ? 'selected' : '' }}>Non-Roadshow</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Program Area <span class="text-danger program-area-required" style="display: none;">*</span></label>
                <input type="text" class="form-control" 
                       name="program_area" id="program_area" value="{{ old('program_area') }}" 
                       placeholder="Specify area for Roadshow/Mini-Roadshow">
                <small class="form-text text-muted">Required for Roadshow and Mini-Roadshow</small>
              </div>
            </div>
          </div>

          <hr class="my-4">
          <h5 class="mb-3 text-primary">Delivery Details</h5>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Supplier Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" 
                       name="supplier_name" value="{{ old('supplier_name') }}" 
                       placeholder="e.g., MD Monicarl, AD Gorospe, PDI" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Status <span class="text-danger">*</span></label>
                <select class="form-control" name="status" required onclick="event.stopPropagation();">
                  <option value="">-- Select Status --</option>
                  <option value="Decline" {{ old('status') == 'Decline' ? 'selected' : '' }}>Decline</option>
                  <option value="Interested" {{ old('status') == 'Interested' ? 'selected' : '' }}>Interested</option>
                  <option value="For Delivery" {{ old('status') == 'For Delivery' ? 'selected' : '' }}>For Delivery</option>
                  <option value="Delivered" {{ old('status') == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Target Timeline</label>
                <input type="date" class="form-control" 
                       name="timeline" value="{{ old('timeline') }}">
                <small class="form-text text-muted">Expected timeline</small>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Delivery Date</label>
                <input type="date" class="form-control" 
                       name="delivery_date" value="{{ old('delivery_date') }}">
                <small class="form-text text-muted">Actual delivery date</small>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Document Attachment</label>
                <input type="file" class="form-control-file" 
                       name="document_attachment" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                <small class="form-text text-muted">Upload supporting documents (PDF, DOC, DOCX, JPG, PNG - Max 5MB)</small>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Additional Notes</label>
                <textarea class="form-control" 
                          name="additional_notes" rows="3" 
                          placeholder="Any additional information...">{{ old('additional_notes') }}</textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Register Dealer</button>
        </div>
      </form>
    </div>
  </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const PSGC_API = {
        BASE_URL: 'https://psgc.rootscratch.com',
        
        async getRegions() {
            try {
                const response = await fetch(`${this.BASE_URL}/region`);
                return await response.json();
            } catch (error) {
                console.error('Error fetching regions:', error);
                return [];
            }
        },
        
        async getProvinces(regionPsgcId) {
            try {
                const response = await fetch(`${this.BASE_URL}/province?id=${regionPsgcId}`);
                return await response.json();
            } catch (error) {
                console.error('Error fetching provinces:', error);
                return [];
            }
        },
        
        async getCitiesMunicipalities(provincePsgcId) {
            try {
                const response = await fetch(`${this.BASE_URL}/municipal-city?id=${provincePsgcId}`);
                return await response.json();
            } catch (error) {
                console.error('Error fetching cities/municipalities:', error);
                return [];
            }
        },
        
        async getBarangays(cityPsgcId) {
            try {
                const response = await fetch(`${this.BASE_URL}/barangay?id=${cityPsgcId}`);
                return await response.json();
            } catch (error) {
                console.error('Error fetching barangays:', error);
                return [];
            }
        }
    };

    const imageInput = document.querySelector('input[name="business_image"]');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = imagePreview.querySelector('img');
    
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 5 * 1024 * 1024) {
                    alert('Image size must not exceed 5MB');
                    this.value = '';
                    imagePreview.style.display = 'none';
                    return;
                }
                
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!validTypes.includes(file.type)) {
                    alert('Please upload a valid image (JPG, JPEG, or PNG)');
                    this.value = '';
                    imagePreview.style.display = 'none';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.style.display = 'none';
            }
        });
    }

    let map, marker;
    let currentLat = 14.6507, currentLng = 121.0494;

    function initMap() {
        map = L.map('location_map').setView([currentLat, currentLng], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        marker = L.marker([currentLat, currentLng], {
            draggable: true
        }).addTo(map);

        updateCoordinates(currentLat, currentLng);

        marker.on('dragend', function(e) {
            const position = marker.getLatLng();
            updateCoordinates(position.lat, position.lng);
        });

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateCoordinates(e.latlng.lat, e.latlng.lng);
        });
    }

    function updateCoordinates(lat, lng) {
        currentLat = lat;
        currentLng = lng;
        document.getElementById('display_lat').textContent = lat.toFixed(6);
        document.getElementById('display_lng').textContent = lng.toFixed(6);
        document.getElementById('hidden_latitude').value = lat.toFixed(6);
        document.getElementById('hidden_longitude').value = lng.toFixed(6);
        updateFullAddress();
    }

    function updateFullAddress() {
        const region = document.getElementById('location_region').options[document.getElementById('location_region').selectedIndex]?.text || '';
        const province = document.getElementById('location_province').options[document.getElementById('location_province').selectedIndex]?.text || '';
        const city = document.getElementById('location_city').options[document.getElementById('location_city').selectedIndex]?.text || '';
        const barangay = document.getElementById('location_barangay').value;
        const postal = document.getElementById('postal_code').value;
        const street = document.getElementById('street_address').value;

        if (street && barangay && city && province && region) {
            const fullAddress = `${street}, Barangay ${barangay}, ${city}, ${province}, ${region}${postal ? ' ' + postal : ''}`;
            document.getElementById('full_address_preview').value = fullAddress;
            document.getElementById('location_hidden').value = fullAddress;
        }
    }

    function getCoordinatesForCity(cityName) {
        const cityCoordinates = {
            'Manila': [14.5995, 120.9842],
            'Quezon City': [14.6760, 121.0437],
            'Makati City': [14.5547, 121.0244],
            'Pasig City': [14.5764, 121.0851],
            'Taguig City': [14.5176, 121.0509],
            'Muntinlupa City': [14.3829, 121.0422],
            'Parañaque City': [14.4793, 121.0198],
            'Las Piñas City': [14.4453, 120.9842],
            'Pasay City': [14.5378, 120.9896],
            'Caloocan City': [14.6488, 120.9830],
            'Valenzuela City': [14.7000, 120.9830],
            'Malabon City': [14.6625, 120.9567],
            'Navotas City': [14.6681, 120.9406],
            'Marikina City': [14.6507, 121.1029],
            'San Juan City': [14.5995, 121.0354],
            'Mandaluyong City': [14.5794, 121.0359],
            
            'Baguio City': [16.4023, 120.5960],
            'Dagupan City': [16.0433, 120.3333],
            'Angeles City': [15.1450, 120.5887],
            'Olongapo City': [14.8294, 120.2825],
            'Batangas City': [13.7565, 121.0583],
            'Lipa City': [13.9411, 121.1624],
            'Lucena City': [13.9372, 121.6169],
            'Naga City': [13.6218, 123.1948],
            'Legazpi City': [13.1391, 123.7436],
            'Calapan City': [13.4119, 121.1803],
            
            'Iloilo City': [10.7202, 122.5621],
            'Bacolod City': [10.6770, 122.9506],
            'Cebu City': [10.3157, 123.8854],
            'Mandaue City': [10.3237, 123.9227],
            'Lapu-Lapu City': [10.3103, 123.9494],
            'Tacloban City': [11.2438, 125.0038],
            'Ormoc City': [11.0059, 124.6074],
            'Dumaguete City': [9.3068, 123.3054],
            
            'Davao City': [7.0731, 125.6128],
            'Zamboanga City': [6.9214, 122.0790],
            'Cagayan de Oro City': [8.4542, 124.6319],
            'Iligan City': [8.2280, 124.2452],
            'Butuan City': [8.9475, 125.5406],
            'General Santos City': [6.1164, 125.1716],
            'Cotabato City': [7.2231, 124.2452]
        };
        
        return cityCoordinates[cityName] || null;
    }

    function updateMapForCity(cityName) {
        const coords = getCoordinatesForCity(cityName);
        if (coords) {
            map.setView(coords, 14);
            marker.setLatLng(coords);
            updateCoordinates(coords[0], coords[1]);
        }
    }
    
    async function populateRegions() {
        const regionSelect = document.getElementById('location_region');
        regionSelect.innerHTML = '<option value="">-- Select Region --</option>';
        
        const regions = await PSGC_API.getRegions();
        
        regions.forEach(region => {
            const option = document.createElement('option');
            option.value = region.psgc_id;
            option.textContent = region.name;
            option.dataset.psgcId = region.psgc_id;
            regionSelect.appendChild(option);
        });
    }

    document.getElementById('location_region').addEventListener('change', async function() {
        const regionPsgcId = this.value;
        const provinceSelect = document.getElementById('location_province');
        const citySelect = document.getElementById('location_city');
        const barangayInput = document.getElementById('location_barangay');

        provinceSelect.innerHTML = '<option value="">-- Select Province --</option>';
        citySelect.innerHTML = '<option value="">-- Select Province First --</option>';
        barangayInput.value = '';
        citySelect.disabled = true;

        if (regionPsgcId) {
            provinceSelect.disabled = false;
            provinceSelect.innerHTML = '<option value="">Loading provinces...</option>';
            
            const provinces = await PSGC_API.getProvinces(regionPsgcId);
            
            provinceSelect.innerHTML = '<option value="">-- Select Province --</option>';
            provinces.forEach(province => {
                const option = document.createElement('option');
                option.value = province.psgc_id;
                option.textContent = province.name;
                option.dataset.psgcId = province.psgc_id;
                provinceSelect.appendChild(option);
            });
        } else {
            provinceSelect.disabled = true;
        }
        updateFullAddress();
    });

    document.getElementById('location_province').addEventListener('change', async function() {
        const provincePsgcId = this.value;
        const citySelect = document.getElementById('location_city');
        const barangayInput = document.getElementById('location_barangay');

        citySelect.innerHTML = '<option value="">-- Select City/Municipality --</option>';
        barangayInput.value = '';

        if (provincePsgcId) {
            citySelect.disabled = false;
            citySelect.innerHTML = '<option value="">Loading cities/municipalities...</option>';
            
            const cities = await PSGC_API.getCitiesMunicipalities(provincePsgcId);
            
            citySelect.innerHTML = '<option value="">-- Select City/Municipality --</option>';
            cities.forEach(city => {
                const option = document.createElement('option');
                option.value = city.psgc_id;
                option.textContent = city.name;
                option.dataset.psgcId = city.psgc_id;
                citySelect.appendChild(option);
            });
        } else {
            citySelect.disabled = true;
        }
        updateFullAddress();
    });

    document.getElementById('location_city').addEventListener('change', async function() {
        const cityName = this.options[this.selectedIndex]?.text;
        
        if (this.value) {
            updateMapForCity(cityName);
        }
        updateFullAddress();
    });

    document.getElementById('location_barangay').addEventListener('input', updateFullAddress);
    document.getElementById('postal_code').addEventListener('input', updateFullAddress);
    document.getElementById('street_address').addEventListener('input', updateFullAddress);

    $('#registerDealer').on('shown.bs.modal', function () {
        populateRegions();
        if (!map) {
            initMap();
        } else {
            map.invalidateSize();
        }
    });
});
</script>

<script>
document.getElementById('lead_generator').addEventListener('change', function() {
    const referenceField = document.getElementById('reference_field');
    const referenceInput = document.getElementById('lead_reference');
    const referenceRequired = document.querySelector('.reference-required');
    
    const requiresReference = ['FB', 'Shopee', 'Gaz Lite Website'].includes(this.value);
    
    if (requiresReference) {
        referenceField.style.display = 'block';
        referenceInput.setAttribute('required', 'required');
        referenceRequired.style.display = 'inline';
    } else {
        referenceField.style.display = 'none';
        referenceInput.removeAttribute('required');
        referenceInput.value = '';
        referenceRequired.style.display = 'none';
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const leadGenerator = document.getElementById('lead_generator');
    if (leadGenerator.value) {
        leadGenerator.dispatchEvent(new Event('change'));
    }
});
</script>

<script>
// Program Type Handler
document.addEventListener('DOMContentLoaded', function() {
    const programTypeSelect = document.getElementById('program_type');
    const programAreaInput = document.getElementById('program_area');
    const programAreaRequired = document.querySelector('.program-area-required');
    
    if (programTypeSelect) {
        programTypeSelect.addEventListener('change', function() {
            const requiresArea = ['Roadshow', 'Mini-Roadshow'].includes(this.value);
            
            if (requiresArea) {
                programAreaInput.setAttribute('required', 'required');
                programAreaRequired.style.display = 'inline';
            } else {
                programAreaInput.removeAttribute('required');
                programAreaInput.value = '';
                programAreaRequired.style.display = 'none';
            }
        });
        
        if (programTypeSelect.value) {
            programTypeSelect.dispatchEvent(new Event('change'));
        }
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const customerTypeSelect = document.getElementById('customer_type');
    const existingCustomerSection = document.getElementById('existing_customer_section');
    let initialized = false;

    customerTypeSelect.addEventListener('change', function () {
        if (this.value === 'existing') {
            existingCustomerSection.style.display = 'block';
            setTimeout(initSelect2, 100);
        } else {
            existingCustomerSection.style.display = 'none';
            clearFields();
        }
    });

    function initSelect2() {
        if (initialized) {
            $('#existing_customer_select').select2('open');
            return;
        }

        $('#existing_customer_select').select2({
            dropdownParent: $('body'),
            width: '100%',
            placeholder: '-- Search for existing customer --',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{{ route("tds.existing-customers") }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { search: params.term || '' };
                },
                processResults: function (res) {
                    return { results: res.results || [] };
                },
                cache: true
            },
            language: {
                noResults: function () { return 'No existing customers found.'; },
                searching: function () { return 'Searching…'; }
            }
        });

        $('#existing_customer_select').on('select2:select', function (e) {
            const d = e.params.data;
            setVal('customer_name', d.customer_name);
            setVal('contact_no', d.contact_no);
            setVal('business_name', d.business_name);
            setVal('business_type', d.business_type);
        });

        $('#existing_customer_select').on('select2:clear', clearFields);

        initialized = true;

        setTimeout(function () {
            $('#existing_customer_select').select2('open');
        }, 50);
    }

    function setVal(id, val) {
        const el = document.getElementById(id);
        if (el) el.value = val || '';
    }

    function clearFields() {
        ['customer_name', 'contact_no', 'business_name', 'business_type']
            .forEach(function (id) {
                setVal(id, '');
            });
    }

    $('#registerDealer').on('shown.bs.modal', function () {
        if (customerTypeSelect.value === 'existing') {
            existingCustomerSection.style.display = 'block';
            setTimeout(initSelect2, 150);
        }
    });

    $('#registerDealer').on('hidden.bs.modal', function () {
        customerTypeSelect.value = '';
        existingCustomerSection.style.display = 'none';
        clearFields();
        if (initialized) {
            $('#existing_customer_select').val(null).trigger('change');
        }
    });
});
</script>