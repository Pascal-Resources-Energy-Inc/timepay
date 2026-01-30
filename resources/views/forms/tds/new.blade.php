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
            <div class="col-md-6">
              <div class="form-group">
                <label>Customer Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" 
                       name="customer_name" value="{{ old('customer_name') }}" 
                       placeholder="Full name of the customer" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Contact Number <span class="text-danger">*</span></label>
                <input type="text" class="form-control" 
                       name="contact_no" value="{{ old('contact_no') }}" 
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
                       name="business_name" value="{{ old('business_name') }}" 
                       placeholder="e.g., Justin's Store" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Business Type <span class="text-danger">*</span></label>
                <select class="form-control" name="business_type" required onclick="event.stopPropagation();">
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
                <select class="form-control" id="location_barangay" name="location_barangay" required onclick="event.stopPropagation();" disabled>
                  <option value="">-- Select City First --</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Postal Code <span class="text-danger">*</span></label>
                <input type="text" class="form-control" 
                       name="postal_code" id="postal_code" value="{{ old('postal_code') }}" 
                       placeholder="e.g., 1121" required>
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
                <label>Pin Exact Location <span class="text-danger">*</span></label>
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
document.addEventListener('DOMContentLoaded', function() {
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

  const locationData = {
    "Metro Manila": {
        "Metro Manila": {
            "Quezon City": ["Commonwealth", "Batasan Hills", "Fairview", "Novaliches", "Diliman", "Cubao", "Project 6", "Kamuning", "Libis", "Loyola Heights"],
            "Manila": ["Ermita", "Malate", "Intramuros", "Binondo", "Tondo", "Sampaloc", "Sta. Cruz", "Sta. Mesa", "Quiapo", "Pandacan"],
            "Makati": ["Poblacion", "Bel-Air", "San Lorenzo", "Urdaneta", "Guadalupe", "Rockwell", "Salcedo Village", "Forbes Park"],
            "Pasig": ["Kapitolyo", "Ugong", "Ortigas", "Rosario", "Santolan", "Malinao", "Maybunga", "Pineda"],
            "Taguig": ["Bonifacio Global City", "Western Bicutan", "Fort Bonifacio", "Pinagsama", "Signal Village", "Maharlika Village"],
            "Mandaluyong": ["Poblacion", "Addition Hills", "Plainview", "Highway Hills", "Vergara", "Wack-Wack"],
            "Parañaque": ["BF Homes", "Baclaran", "Moonwalk", "San Antonio", "Tambo", "Sun Valley"],
            "Las Piñas": ["BF International", "Talon", "Pamplona", "Almanza", "Pulang Lupa"],
            "Muntinlupa": ["Alabang", "Putatan", "Poblacion", "Ayala Alabang", "Cupang", "Buli"],
            "Pasay": ["Malibay", "Tramo", "San Rafael", "San Roque", "Maricaban"],
            "Caloocan": ["Bagong Barrio", "Bagong Silang", "Camarin", "Kaybiga", "Sangandaan"],
            "Malabon": ["Acacia", "Baritan", "Catmon", "Concepcion", "Flores"],
            "Navotas": ["Bagumbayan North", "Bagumbayan South", "Bangculasi", "Daanghari", "Navotas East"],
            "Valenzuela": ["Arkong Bato", "Bagbaguin", "Bignay", "Bisig", "Canumay"],
            "Pateros": ["Aguho", "Magtanggol", "Martires del '96", "Poblacion", "San Pedro"],
            "San Juan": ["Addition Hills", "Balong-Bato", "Batis", "Corazon de Jesus", "Greenhills"],
            "Marikina": ["Barangka", "Calumpang", "Concepcion Uno", "Industrial Valley", "Jesus dela Peña"]
        }
    },

    "CAR - Cordillera Administrative Region": {
        "Abra": {
            "Bangued": ["Poblacion", "Zone 1", "Zone 2", "Zone 3", "Calaba", "Cosili", "Dangdangla"],
            "Dolores": ["Poblacion", "Talogtog", "Isit", "Bayabas", "Cabaroan"],
            "Lagangilang": ["Poblacion", "Aguet", "Bacooc", "Balais", "Cayapa"],
            "Lacub": ["Poblacion", "Guinguinabang", "Lan-ag", "Pacac"],
            "La Paz": ["Poblacion", "Mudeng", "Pidigan", "San Gregorio"],
            "Langiden": ["Poblacion", "Baac", "Mabungtot", "Malekmag"],
            "Luba": ["Poblacion", "Cabcaborao", "Lusuac", "Pang-ot"],
            "Malibcong": ["Poblacion", "Buanao", "Duldulao", "Pacgued"],
            "Manabo": ["Poblacion", "Ayyeng", "Catacdegan Nuevo", "Luzong"],
            "Peñarrubia": ["Poblacion", "Canan", "Lusuac", "Patucannay"],
            "Pidigan": ["Poblacion", "Asom", "Immuli", "Monggoc"],
            "Pilar": ["Poblacion", "Borobor", "Lenneng", "Naguirian"],
            "Sallapadan": ["Poblacion", "Bilabila", "Naguilian", "Sallapadan"],
            "San Isidro": ["Poblacion", "Dalipey", "Manayday", "Sabangan"],
            "San Juan": ["Poblacion", "Lam-ag", "Naguilian", "Pinukpuk"],
            "San Quintin": ["Poblacion", "Labaan", "Palang", "Tangadan"],
            "Tayum": ["Poblacion", "Bagalay", "Caupasan", "Velasco"],
            "Tineg": ["Poblacion", "Agsimao", "Anayan", "Belaat"],
            "Tubo": ["Poblacion", "Kili", "Mayabo", "Supo"],
            "Villaviciosa": ["Poblacion", "Labaan", "Luzong", "Tambo"]
        },
        "Apayao": {
            "Calanasan": ["Poblacion", "Cadaclan", "Namaltugan", "Tanglagan"],
            "Conner": ["Poblacion", "Buluan", "Karikitan", "Manag"],
            "Flora": ["Poblacion", "Allig", "Anninipan", "Atok"],
            "Kabugao": ["Poblacion", "Malibang", "Dagara", "Lenneng"],
            "Luna": ["Poblacion", "Baket", "Lower Maton", "San Isidro"],
            "Pudtol": ["Poblacion", "Aga", "Emilia", "Linan"],
            "Santa Marcela": ["Poblacion", "Almacen", "Imelda", "Katablangan"]
        },
        "Benguet": {
            "Baguio City": ["Poblacion", "Session Road", "Burnham", "Camp Allen", "Lower Rock Quarry", "Upper Bonifacio", "Malcolm Square", "Cabinet Hill"],
            "La Trinidad": ["Poblacion", "Wangal", "Balili", "Pico", "Alno", "Ambiong"],
            "Itogon": ["Poblacion", "Tinongdan", "Ucab", "Ampucao", "Dalupirip"],
            "Tuba": ["Poblacion", "Camp One", "Twin Peaks", "Ansagan", "Camp 3"],
            "Sablan": ["Poblacion", "Balluay", "Banangan", "Bayabas", "Kamog"],
            "Tublay": ["Poblacion", "Ambassador", "Ambongdolan", "Ba-ayan", "Basil"],
            "Kapangan": ["Poblacion", "Balakbak", "Beleng-Belis", "Cayapes", "Gadang"],
            "Atok": ["Poblacion", "Abiang", "Cattubo", "Naguey", "Paoay"],
            "Bokod": ["Poblacion", "Bobok-Bisal", "Daclan", "Karao", "Pito"],
            "Buguias": ["Poblacion", "Abatan", "Amgaleyguey", "Baculongan", "Lengaoan"],
            "Kabayan": ["Poblacion", "Anchukey", "Ballay", "Bashoy", "Eddet"],
            "Kibungan": ["Poblacion", "Badeo", "Lubo", "Madaymen", "Palina"],
            "Mankayan": ["Poblacion", "Balili", "Bedbed", "Bulalacao", "Guinaoang"],
            "Bakun": ["Poblacion", "Ampusongan", "Dalipey", "Gambang", "Kayapa"]
        },
        "Ifugao": {
            "Lagawe": ["Poblacion", "Burnay", "Olilicon", "Bocobo", "Bolog"],
            "Aguinaldo": ["Poblacion", "Chalalo", "Galonogon", "Ibaba", "Nabinunan"],
            "Alfonso Lista": ["Poblacion", "Busilac", "Calao", "Namnama", "Namillangan"],
            "Asipulo": ["Poblacion", "Amduntog", "Antipolo", "Camandag", "Haliap"],
            "Banaue": ["Poblacion", "Viewpoint", "Tam-an", "Batad", "Bocos"],
            "Hingyon": ["Poblacion", "Anao", "Bangtinon", "Bitu", "Cababuyan"],
            "Hungduan": ["Poblacion", "Abatan", "Bangbang", "Hapao", "Nungulunan"],
            "Kiangan": ["Poblacion", "Ambabag", "Baguinge", "Hucab", "Julongan"],
            "Lamut": ["Poblacion", "Bangbang", "Hapao", "Mabatobato", "Nayon"],
            "Mayoyao": ["Poblacion", "Alimit", "Balangbang", "Bongan", "Cham-a"],
            "Tinoc": ["Poblacion", "Ahin", "Ap-apid", "Binablayan", "Danggo"]
        },
        "Kalinga": {
            "Tabuk City": ["Poblacion", "Bulanao", "Dagupan", "Appas", "Bado Dangwa"],
            "Balbalan": ["Poblacion", "Balbalasang", "Mabaca", "Pantikian", "Talalang"],
            "Lubuagan": ["Poblacion", "Lower Uma", "Tanglag", "Mabilong", "Madalag"],
            "Pasil": ["Poblacion", "Ableg", "Balatoc", "Cagaluan", "Dalupa"],
            "Pinukpuk": ["Poblacion", "Aciga", "Allaguia", "Ammacian", "Apatan"],
            "Rizal": ["Poblacion", "Calaocan", "Liwan East", "Liwan West", "Macutay"],
            "Tanudan": ["Poblacion", "Dacalan", "Gaang", "Lubo", "Mangali"],
            "Tinglayan": ["Poblacion", "Bangad", "Buscalan", "Butbut", "Loccong"]
        },
        "Mountain Province": {
            "Bontoc": ["Poblacion", "Caluttit", "Mainit", "Alab Oriente", "Alab Proper"],
            "Barlig": ["Poblacion", "Chupac", "Fiangtin", "Kaleo", "Latang"],
            "Bauko": ["Poblacion", "Abatan", "Bagnen Oriente", "Bagnen Proper", "Balintaugan"],
            "Besao": ["Poblacion", "Agawa", "Ambaguio", "Banguitan", "Gueday"],
            "Sabangan": ["Poblacion", "Banangan", "Capinitan", "Data", "Gayang"],
            "Sadanga": ["Poblacion", "Anabel", "Betwagan", "Demang", "Sacasacan"],
            "Sagada": ["Poblacion", "Aguid", "Ambasing", "Ankileng", "Antadao"],
            "Tadian": ["Poblacion", "Balaoa", "Batayan", "Bunga", "Cadad-anan"]
        }
    },

    "Region I - Ilocos Region": {
        "Ilocos Norte": {
            "Laoag City": ["Poblacion", "Barangay 1", "Barangay 2", "Araniw", "Balatong", "Balacad", "Bengcag"],
            "Batac City": ["Poblacion", "Barangay 1", "Acosta", "Baay", "Baligat", "Baoa East"],
            "Pagudpud": ["Poblacion 1", "Balaoi", "Burayoc", "Caunayan", "Ligaya", "Saud"],
            "Paoay": ["Poblacion", "Bacsil", "Cabagoan", "Cabangaran", "Callaguip"],
            "Currimao": ["Poblacion", "Bimmanga", "Cabuusan", "Comcomloong", "Gaang"],
            "Bacarra": ["Poblacion", "Barbar", "Buyon", "Caunayan", "Duripes"],
            "Burgos": ["Poblacion", "Aring", "Buduan", "Cabaroan", "Caunayan"],
            "Dingras": ["Poblacion", "Albano", "Bagut", "Baresbes", "Barong"],
            "Marcos": ["Poblacion", "Balaoi", "Cacafean", "Daquioag", "Fortuna"],
            "Nueva Era": ["Poblacion", "Acnam", "Barangobong", "Barikir", "Bugayong"],
            "Piddig": ["Poblacion", "Abucay", "Anao", "Arua-ay", "Barbar"],
            "Pinili": ["Poblacion Norte", "Aglipay", "Barangobong", "Buanga", "Bungro"],
            "San Nicolas": ["Poblacion", "Barbar", "Bingao", "Bulbulala", "Cabulalaan"],
            "Sarrat": ["Poblacion", "Barangay 1", "Barangay 2", "Barangay 3", "Barangay 4"]
        },
        "Ilocos Sur": {
            "Vigan City": ["Poblacion", "Ayusan Norte", "Barangay Beddeng Laud", "Barraca", "Bongtolan"],
            "Candon City": ["Poblacion", "Allangigan 1st", "Amguid", "Ayudante", "Bagani Campo"],
            "Santa Catalina": ["Poblacion", "Ambalayat", "Bitalag", "Cabaroan", "Cabittaogan"],
            "Bantay": ["Poblacion", "Aggay", "Banaoang", "Bulag-bulag", "Cabalanggan"],
            "Magsingal": ["Poblacion", "Alangan", "Bacar", "Barbarit", "Bungro"],
            "Tagudin": ["Poblacion", "Baracbac", "Bario-an", "Barraca", "Begang"],
            "Santa Maria": ["Poblacion Norte", "Apatut-Lubong", "Balidbid", "Baybayabas", "Bitalag"],
            "Narvacan": ["Poblacion 1", "Ayudante", "Banglayan", "Bulanos", "Burgos"],
            "Santa Lucia": ["Poblacion East", "Ayusan", "Banbanaba", "Bao-as", "Biday"],
            "Caoayan": ["Poblacion", "Anonang Mayor", "Callaguip", "Catagtaguen", "Danuman East"],
            "San Esteban": ["Poblacion", "Ansad", "Apatot", "Bateria", "Butol"],
            "San Vicente": ["Poblacion", "Bantaoay", "Bayubay Norte", "Bonifacio", "Bulala-Aruo"]
        },
        "La Union": {
            "San Fernando City": ["Poblacion", "Abut", "Apaleng", "Bacsil", "Bangbangolan"],
            "Agoo": ["Poblacion", "Ambitacay", "Balawarte", "Bangan-Oda", "Capas"],
            "Bauang": ["Poblacion", "Acao", "Ballay", "Bawanta", "Boy-utan"],
            "San Juan": ["Poblacion", "Aludaid", "Bacnar", "Barong", "Bato"],
            "Bacnotan": ["Poblacion", "Arosip", "Bagutot", "Bani", "Bitalag"],
            "Naguilian": ["Poblacion", "Aguioas", "Al-alinao Norte", "Amontoc", "Angin"],
            "Bagulin": ["Poblacion", "Alibangsay", "Baay", "Bagbaguin", "Cambaly"],
            "Balaoan": ["Poblacion", "Alfonso", "Antonino", "Azucena", "Buenos Aires"],
            "Bangar": ["Poblacion", "Bangaoilan East", "Barangobong", "Cadapli", "Consuegra"],
            "Caba": ["Poblacion", "Bautista", "Carcarmay", "La Paz Centro", "Las-ud"],
            "Luna": ["Poblacion", "Alcala", "Ayaoan", "Barangobong", "Barengeg"],
            "Rosario": ["Poblacion", "Ambuetel", "Ambuclao", "Bangar", "Bani"],
            "San Gabriel": ["Poblacion", "Ambalite", "Apayao", "Balbalayang", "Baracbac"],
            "Santol": ["Poblacion", "Lettac Norte", "Loslos", "Nagsabaran", "Napunan"]
        },
        "Pangasinan": {
            "Dagupan City": ["Poblacion", "Bacayao Norte", "Barangay I", "Bolosan", "Bonuan Binloc"],
            "Alaminos City": ["Poblacion", "Alos", "Amandiego", "Amangbangan", "Balangobong"],
            "San Carlos City": ["Poblacion", "Abanon", "Agdao", "Anando", "Ano"],
            "Urdaneta City": ["Poblacion", "Anonas", "Bactad East", "Bayaoas", "Bolaoen"],
            "Umingan": ["Poblacion", "Abot Molina", "Amaronan", "Annam", "Anuang"],
            "Binalonan": ["Poblacion", "Amistad", "Balangobong", "Bued", "Bugayong"],
            "Manaoag": ["Poblacion", "Babasit", "Baguinay", "Baritao", "Bisal"],
            "Binmaley": ["Poblacion", "Balagan", "Balogo", "Basing", "Buenlag"],
            "Calasiao": ["Poblacion", "Ambonao", "Ambuetel", "Banaoang", "Bued"],
            "San Fabian": ["Poblacion", "Alacan", "Ambalangan-Dalin", "Angio", "Anonang"],
            "Lingayen": ["Poblacion", "Aliwekwek", "Baay", "Balangobong", "Balococ"],
            "Tayug": ["Poblacion", "Agno", "Amagbagan", "Ayos", "Carriedo"],
            "Bayambang": ["Poblacion", "Alinggan", "Amangonan-Balangobong", "Ambabaay", "Ambayat I"],
            "Rosales": ["Poblacion", "Bakitbakit", "Balingcanaway", "Cabalaoangan Norte", "Calanutan"],
            "Villasis": ["Poblacion", "Amamperez", "Bacag", "Barangobong", "Barraca"],
            "Malasiqui": ["Poblacion", "Alacan", "Amacalan", "Andangin", "Apaya"],
            "Pozorrubio": ["Poblacion", "Alipangpang", "Amagbagan", "Balacag", "Banding"],
            "Alcala": ["Poblacion", "Alacan", "Anulid", "Atainan", "Bersamin"],
            "Bautista": ["Poblacion", "Artacho", "Balingueo", "Cabuaan", "Cacandongan"],
            "Bolinao": ["Poblacion", "Arnedo", "Balinmanalo", "Binabalian", "Cabarruyan"],
            "Bugallon": ["Poblacion", "Angarian", "Asinan", "Baleyadaan", "Bolaoen"],
            "Infanta": ["Poblacion", "Bamban", "Barlo", "Batang", "Bayambang"],
            "Dasol": ["Poblacion", "Amalbalan", "Aloneros", "Bobonot", "Eguia"],
            "Mabini": ["Poblacion", "Balogo", "Balungao", "Banaban", "Barang"]
        }
    },

    "Region II - Cagayan Valley": {
        "Batanes": {
            "Basco": ["Poblacion", "San Antonio", "San Joaquin", "Kaychanarianan"],
            "Itbayat": ["Poblacion", "Raele", "Santa Rosa", "Santa Lucia"],
            "Ivana": ["Poblacion", "Salagao", "Tuhel", "Radiwan"],
            "Mahatao": ["Poblacion", "Hanib", "Kaumbakan", "Panatayan"],
            "Sabtang": ["Poblacion", "Chavayan", "Malakdang", "Nakanmuan"],
            "Uyugan": ["Poblacion", "Imnajbu", "Itbud", "Kayvaluganan"]
        },
        "Cagayan": {
          "Tuguegarao City": ["Centro 1", "Centro 2", "Centro 3", "Annafunan East", "Buntun", "Caggay"],
          "Ilagan": ["Poblacion", "Centro I", "Alibagu", "Allinguigan", "Bagong Bayan"],
          "Aparri": ["Poblacion", "Backiling", "Bangag", "Binalan", "Bisagu"],
          "Sanchez-Mira": ["Poblacion", "Bagunot", "Bangan", "Callao", "Centro I"],
          "Santa Ana": ["Poblacion", "Centro I", "Diora", "Dungeg", "Palawig"],
          "Ballesteros": ["Poblacion", "Ammubuan", "Balza", "Callao", "Carusican"],
          "Camalaniugan": ["Poblacion", "Aguinaldo", "Bacayao Norte", "Bacayao Sur", "Balatoc"],
          "Claveria": ["Poblacion", "Baay", "Bangag", "Cabagan", "Callao"],
          "Enrile": ["Poblacion", "Bagay", "Balaca", "Burgos", "Caraycaray"],
          "Gattaran": ["Poblacion", "Agawid", "Alangigan", "Baligan", "Cagayan"],
          "Lasam": ["Poblacion", "Bagbag", "Bacag", "Callao", "Carasi"],
          "Peñablanca": ["Poblacion", "Agang", "Aparri", "Bagumbayan", "Callao"],
          "Piat": ["Poblacion", "Abag", "Balatoc", "Bangag", "Callao"],
          "Rizal": ["Poblacion", "Agano", "Bagumbayan", "Bantay", "Callao"],
          "Solana": ["Poblacion", "Aduas", "Balac", "Bungao", "Caraycaray"],
          "Tuao": ["Poblacion", "Banaoang", "Bangar", "Callao", "Cabuloan"],
          "Tumauini": ["Poblacion", "Abucay", "Bagumbayan", "Bangag", "Callao"]
      },
      "Isabela": {
          "Ilagan City": ["Poblacion", "San Vicente", "San Pedro", "San Juan", "San Roque"],
          "Cauayan City": ["Poblacion", "San Fermin", "San Agustin", "San Antonio", "San Isidro"],
          "Cabagan": ["Poblacion", "Bangag", "Callao", "San Pedro", "Santa Maria"],
          "Delfin Albano": ["Poblacion", "Agaba", "Balucuc", "Callang", "Caris"],
          "Divilacan": ["Poblacion", "Bangag", "Callao", "Catangpitan", "Cauayan"],
          "Maconacon": ["Poblacion", "Abuan", "Balawag", "Bacag", "Calamagui"],
          "Naguilian": ["Poblacion", "Bangar", "Callao", "San Roque", "Santa Cruz"],
          "Palanan": ["Poblacion", "Bubug", "Cabuluan", "Dibagat", "Malaueg"],
          "Ramon": ["Poblacion", "Bagabag", "Bangar", "Callao", "San Isidro"],
          "San Mateo": ["Poblacion", "Agata", "Bagulin", "Bangad", "Cayawan"],
          "Santo Tomas": ["Poblacion", "Bangag", "Callao", "San Pedro", "Santa Lucia"],
          "Tumauini": ["Poblacion", "Abucay", "Bagumbayan", "Bangag", "Callao"],
          "Cabatuan": ["Poblacion", "Bangad", "Callao", "San Pedro", "Santa Maria"],
          "Ilagan": ["Poblacion", "San Vicente", "San Pedro", "San Juan", "San Roque"]
      },
      "Nueva Vizcaya": {
          "Bayombong": ["Poblacion", "Bagabag", "Balete", "Caraballo", "Malabug"],
          "Solano": ["Poblacion", "Abong", "Balete", "Bayanan", "Callao"],
          "Aritao": ["Poblacion", "Bagbag", "Balete", "Cabangbang", "Callao"],
          "Bambang": ["Poblacion", "Agano", "Bagabag", "Balete", "Callao"],
          "Dupax del Norte": ["Poblacion", "Bagabag", "Balete", "Callao", "San Jose"],
          "Dupax del Sur": ["Poblacion", "Bagabag", "Balete", "Callao", "San Pedro"],
          "Kasibu": ["Poblacion", "Bagabag", "Balete", "Callao", "San Juan"],
          "Quezon": ["Poblacion", "Bagabag", "Balete", "Callao", "Santa Maria"],
          "Santa Fe": ["Poblacion", "Bagabag", "Balete", "Callao", "San Vicente"]
      },
      "Quirino": {
          "Cabarruyan": ["Poblacion", "Bangar", "Callao", "San Jose", "Santa Lucia"],
          "Diffun": ["Poblacion", "Bagabag", "Balete", "Callao", "San Isidro"],
          "Maddela": ["Poblacion", "Bagabag", "Balete", "Callao", "San Juan"],
          "Nagtipunan": ["Poblacion", "Bagabag", "Balete", "Callao", "San Pedro"],
          "Saguday": ["Poblacion", "Bagabag", "Balete", "Callao", "Santa Maria"]
      }
    },
    "Region III - Central Luzon": {
      "Aurora": {
          "Baler": ["Poblacion", "Catan", "Ditailin", "Maligaya", "San Luis"],
          "Casiguran": ["Poblacion", "Binonayan", "San Joaquin", "Santisimo", "San Isidro"],
          "Dilasag": ["Poblacion", "Abuay", "Cagayanin", "Calabuan", "Ditac"],
          "Dinalungan": ["Poblacion", "Cabuluan", "Dinas", "San Juan", "San Pedro"],
          "Dipaculao": ["Poblacion", "Calabaan", "Calacag", "San Isidro", "Bagumbayan"],
          "Maria Aurora": ["Poblacion", "Baler", "Balete", "San Juan", "San Roque"]
      },
      "Bataan": {
          "Balanga City": ["Poblacion", "San Jose", "San Rafael", "Bagong Silang", "Bagumbayan"],
          "Abucay": ["Poblacion", "Bagong Silang", "San Antonio", "San Juan", "Santa Lucia"],
          "Bagac": ["Poblacion", "San Agustin", "San Isidro", "San Jose", "Santa Cruz"],
          "Dinalupihan": ["Poblacion", "San Andres", "San Isidro", "San Roque", "Bagong Silang"],
          "Hermosa": ["Poblacion", "San Isidro", "San Jose", "San Rafael", "Santa Cruz"],
          "Morong": ["Poblacion", "Bagong Silang", "San Miguel", "San Roque", "Santa Rosa"],
          "Orani": ["Poblacion", "San Isidro", "San Jose", "San Roque", "Santa Lucia"],
          "Orion": ["Poblacion", "Bagong Silang", "San Isidro", "San Jose", "San Roque"],
          "Pilar": ["Poblacion", "Bagong Silang", "San Isidro", "San Roque", "Santa Cruz"],
          "Samal": ["Poblacion", "San Antonio", "San Isidro", "San Jose", "San Roque"]
      },
      "Bulacan": {
          "Malolos City": ["Poblacion", "Baras", "Bulihan", "Tabang", "Palimpe"],
          "Meycauayan City": ["Poblacion", "Bagbaguin", "Iba", "Lias", "Saluysoy"],
          "San Jose del Monte City": ["Poblacion", "Fatima", "Langgam", "Minuyan", "Poblacion"],
          "Angat": ["Poblacion", "Bagumbayan", "Capistahan", "San Juan", "San Miguel"],
          "Balagtas": ["Poblacion", "Longos", "Poblacion Norte", "Poblacion Sur", "San Jose"],
          "Baliuag": ["Poblacion", "Bagumbayan", "San Jose", "Santo Cristo", "San Isidro"],
          "Bocaue": ["Poblacion", "Bagumbayan", "San Jose", "San Roque", "Santa Maria"],
          "Bulacan": ["Poblacion", "Bagumbayan", "San Jose", "San Rafael", "Santa Rosa"],
          "Guiguinto": ["Poblacion", "Bagumbayan", "San Isidro", "San Jose", "San Roque"],
          "Hagonoy": ["Poblacion", "Bagumbayan", "San Isidro", "San Jose", "San Roque"],
          "Marilao": ["Poblacion", "Bagumbayan", "San Isidro", "San Jose", "San Roque"],
          "Norzagaray": ["Poblacion", "Bagumbayan", "San Isidro", "San Jose", "San Roque"],
          "Obando": ["Poblacion", "Bagumbayan", "San Isidro", "San Jose", "San Roque"],
          "Pandi": ["Poblacion", "Bagumbayan", "San Isidro", "San Jose", "San Roque"],
          "Plaridel": ["Poblacion", "Bagumbayan", "San Isidro", "San Jose", "San Roque"],
          "Pulilan": ["Poblacion", "Bagumbayan", "San Isidro", "San Jose", "San Roque"],
          "San Ildefonso": ["Poblacion", "Bagumbayan", "San Isidro", "San Jose", "San Roque"],
          "San Miguel": ["Poblacion", "Bagumbayan", "San Isidro", "San Jose", "San Roque"],
          "San Rafael": ["Poblacion", "Bagumbayan", "San Isidro", "San Jose", "San Roque"],
          "Santa Maria": ["Poblacion", "Bagumbayan", "San Isidro", "San Jose", "San Roque"]
      },
      "Nueva Ecija": {
        "Cabanatuan City": ["Poblacion", "Bagong Barrio", "Biga", "San Isidro", "Santo Cristo"],
        "Gapan City": ["Poblacion", "Bagong Barrio", "San Isidro", "San Roque", "Santo Rosario"],
        "Palayan City": ["Poblacion", "Bagong Barrio", "San Jose", "San Roque", "Santa Rita"],
        "San Jose City": ["Poblacion", "Bagong Barrio", "San Isidro", "San Roque", "Santa Maria"],
        "Aliaga": ["Poblacion", "Bagong Barrio", "San Vicente", "Santa Cruz", "San Miguel"],
        "Bongabon": ["Poblacion", "Bagong Barrio", "San Isidro", "San Roque", "Santa Lucia"],
        "Cuyapo": ["Poblacion", "Bagong Barrio", "San Isidro", "San Roque", "Santa Maria"],
        "Gabaldon": ["Poblacion", "Bagong Barrio", "San Isidro", "San Roque", "Santa Cruz"],
        "General Tinio": ["Poblacion", "Bagong Barrio", "San Isidro", "San Roque", "Santa Rita"],
        "Jaen": ["Poblacion", "Bagong Barrio", "San Isidro", "San Roque", "Santa Lucia"],
        "Laur": ["Poblacion", "Bagong Barrio", "San Isidro", "San Roque", "Santa Maria"],
        "Licab": ["Poblacion", "Bagong Barrio", "San Isidro", "San Roque", "Santa Cruz"],
        "Llanera": ["Poblacion", "Bagong Barrio", "San Isidro", "San Roque", "Santa Maria"],
        "Lupao": ["Poblacion", "Bagong Barrio", "San Isidro", "San Roque", "Santa Lucia"],
        "Muñoz City": ["Poblacion", "Bagong Barrio", "San Isidro", "San Roque", "Santa Maria"],
        "Nampicuan": ["Poblacion", "Bagong Barrio", "San Isidro", "San Roque", "Santa Cruz"],
        "Pantabangan": ["Poblacion", "Bagong Barrio", "San Isidro", "San Roque", "Santa Maria"],
        "Peñaranda": ["Poblacion", "Bagong Barrio", "San Isidro", "San Roque", "Santa Lucia"],
        "Rizal": ["Poblacion", "Bagong Barrio", "San Isidro", "San Roque", "Santa Maria"],
        "San Antonio": ["Poblacion", "Bagong Barrio", "San Isidro", "San Roque", "Santa Cruz"],
        "San Leonardo": ["Poblacion", "Bagong Barrio", "San Isidro", "San Roque", "Santa Maria"],
        "Santa Rosa": ["Poblacion", "Bagong Barrio", "San Isidro", "San Roque", "Santa Lucia"],
        "Santo Domingo": ["Poblacion", "Bagong Barrio", "San Isidro", "San Roque", "Santa Maria"],
        "Talavera": ["Poblacion", "Bagong Barrio", "San Isidro", "San Roque", "Santa Lucia"],
        "Talugtug": ["Poblacion", "Bagong Barrio", "San Isidro", "San Roque", "Santa Maria"],
        "Zaragoza": ["Poblacion", "Bagong Barrio", "San Isidro", "San Roque", "Santa Cruz"]
      },
      "Pampanga": {
        "Angeles City": ["Poblacion", "Balibago", "Cutcut", "Malabanias", "Salapungan"],
        "Mabalacat City": ["Poblacion", "Dolores", "Mabiga", "Sta. Ines", "Sto. Domingo"],
        "San Fernando City": ["Poblacion", "Del Pilar", "Del Rosario", "San Juan", "San Nicolas"],
        "Apalit": ["Poblacion", "San Vicente", "San Pedro", "San Juan", "Santa Lucia"],
        "Arayat": ["Poblacion", "Baliti", "Capalangan", "San Jose", "Santa Catalina"],
        "Bacolor": ["Poblacion", "San Agustin", "San Nicolas", "San Juan", "Santa Rita"],
        "Candaba": ["Poblacion", "San Isidro", "San Roque", "Santa Lucia", "Santa Maria"],
        "Floridablanca": ["Poblacion", "Balas", "San Agustin", "San Jose", "San Nicolas"],
        "Guagua": ["Poblacion", "San Jose", "San Nicolas", "Santa Rita", "San Roque"],
        "Lubao": ["Poblacion", "San Agustin", "San Jose", "San Nicolas", "Santa Rita"],
        "Macabebe": ["Poblacion", "San Isidro", "San Juan", "San Roque", "Santa Lucia"],
        "Magalang": ["Poblacion", "San Agustin", "San Jose", "San Nicolas", "Santa Rita"],
        "Masantol": ["Poblacion", "San Agustin", "San Jose", "San Nicolas", "Santa Rita"],
        "Mexico": ["Poblacion", "San Jose", "San Roque", "San Nicolas", "Santa Lucia"],
        "Porac": ["Poblacion", "San Isidro", "San Juan", "San Roque", "Santa Rita"],
        "San Luis": ["Poblacion", "San Agustin", "San Jose", "San Nicolas", "Santa Lucia"],
        "San Simon": ["Poblacion", "San Agustin", "San Jose", "San Nicolas", "Santa Rita"],
        "Santa Ana": ["Poblacion", "San Agustin", "San Jose", "San Nicolas", "Santa Rita"],
        "Santa Rita": ["Poblacion", "San Agustin", "San Jose", "San Nicolas", "Santa Lucia"],
        "Santo Tomas": ["Poblacion", "San Agustin", "San Jose", "San Nicolas", "Santa Rita"],
        "Sasmuan": ["Poblacion", "San Agustin", "San Jose", "San Nicolas", "Santa Rita"]
      },
      "Tarlac": {
        "Tarlac City": ["Poblacion", "San Isidro", "San Roque", "San Juan", "San Nicolas"],
        "Anao": ["Poblacion", "San Isidro", "San Roque", "San Juan", "Santa Lucia"],
        "Bamban": ["Poblacion", "San Isidro", "San Roque", "San Juan", "Santa Maria"],
        "Camiling": ["Poblacion", "San Isidro", "San Roque", "San Juan", "Santa Lucia"],
        "Capas": ["Poblacion", "San Isidro", "San Roque", "San Juan", "Santa Maria"],
        "Concepcion": ["Poblacion", "San Isidro", "San Roque", "San Juan", "Santa Rita"],
        "Gerona": ["Poblacion", "San Isidro", "San Roque", "San Juan", "Santa Lucia"],
        "La Paz": ["Poblacion", "San Isidro", "San Roque", "San Juan", "Santa Rita"],
        "Mayantoc": ["Poblacion", "San Isidro", "San Roque", "San Juan", "Santa Lucia"],
        "Moncada": ["Poblacion", "San Isidro", "San Roque", "San Juan", "Santa Rita"],
        "Paniqui": ["Poblacion", "San Isidro", "San Roque", "San Juan", "Santa Lucia"],
        "Pura": ["Poblacion", "San Isidro", "San Roque", "San Juan", "Santa Rita"],
        "Ramos": ["Poblacion", "San Isidro", "San Roque", "San Juan", "Santa Lucia"],
        "San Clemente": ["Poblacion", "San Isidro", "San Roque", "San Juan", "Santa Rita"],
        "San Manuel": ["Poblacion", "San Isidro", "San Roque", "San Juan", "Santa Lucia"],
        "Santa Ignacia": ["Poblacion", "San Isidro", "San Roque", "San Juan", "Santa Rita"]
      },
      "Zambales": {
        "Olongapo City": ["Poblacion", "San Antonio", "San Roque", "San Nicolas", "Santa Rita"],
        "Botolan": ["Poblacion", "San Agustin", "San Jose", "San Nicolas", "Santa Lucia"],
        "Cabangan": ["Poblacion", "San Agustin", "San Jose", "San Nicolas", "Santa Maria"],
        "Candelaria": ["Poblacion", "San Isidro", "San Roque", "San Juan", "Santa Lucia"],
        "Castillejos": ["Poblacion", "San Agustin", "San Jose", "San Nicolas", "Santa Rita"],
        "Iba": ["Poblacion", "San Agustin", "San Jose", "San Nicolas", "Santa Lucia"],
        "Masinloc": ["Poblacion", "San Isidro", "San Roque", "San Juan", "Santa Rita"],
        "Palauig": ["Poblacion", "San Agustin", "San Jose", "San Nicolas", "Santa Lucia"],
        "San Antonio": ["Poblacion", "San Agustin", "San Jose", "San Nicolas", "Santa Maria"],
        "San Felipe": ["Poblacion", "San Isidro", "San Roque", "San Juan", "Santa Lucia"],
        "San Marcelino": ["Poblacion", "San Agustin", "San Jose", "San Nicolas", "Santa Rita"],
        "San Narciso": ["Poblacion", "San Isidro", "San Roque", "San Juan", "Santa Lucia"],
        "Santa Cruz": ["Poblacion", "San Agustin", "San Jose", "San Nicolas", "Santa Maria"],
        "Subic": ["Poblacion", "San Isidro", "San Roque", "San Juan", "Santa Rita"]
      }
    },
    "Region IV-A - CALABARZON": {
      "Cavite": {
          "Cavite City": ["Poblacion", "San Jose", "San Roque", "San Juan", "Santa Cruz"],
          "Dasmariñas City": ["Poblacion", "Salitran", "Salawag", "Burol", "Sampaloc"],
          "Bacoor City": ["Poblacion", "Zapote", "Talaba", "Dulong Bayan", "Cavite West"],
          "Imus City": ["Poblacion", "Anabu", "Burgos", "Bayan Luma", "Burgos"],
          "Tagaytay City": ["Poblacion", "Mendez", "Maharlika", "San Jose", "Iruhin"],
          "Tanza": ["Poblacion", "Sapang", "Mabuhay", "Bagong Bayan", "Maharlika"],
          "Kawit": ["Poblacion", "Magdalo", "San Roque", "Cavite West", "Bagong Bayan"],
          "Indang": ["Poblacion", "Puting Kahoy", "San Jose", "Balite", "San Isidro"],
          "Trece Martires City": ["Poblacion", "Alas-asin", "San Miguel", "Conchu", "San Roque"],
          "General Trias": ["Poblacion", "Santo Niño", "Buenavista", "San Francisco", "Malabanan"]
      },
      "Laguna": {
          "Calamba City": ["Poblacion", "Halang", "Sampaguita Village", "Parian", "Punta"],
          "San Pablo City": ["Poblacion", "San Jose", "Concepcion", "Calle Uno", "San Isidro"],
          "Santa Rosa City": ["Poblacion", "Balibago", "Tagapo", "Kanluran", "Bayanan"],
          "Binan City": ["Poblacion", "San Antonio", "San Isidro", "Pandan", "Lakeside"],
          "Biñan": ["Poblacion", "San Francisco", "San Jose", "San Vicente", "Malabanan"],
          "Cabuyao City": ["Poblacion", "Marinig", "Mamatid", "Pulo", "Niugan"],
          "Calauan": ["Poblacion", "Longos", "Banay-Banay", "San Antonio", "San Juan"],
          "Los Baños": ["Poblacion", "Baybayin", "Bagong Silang", "Batong Malake", "Milagrosa"],
          "San Pedro": ["Poblacion", "San Vicente", "San Antonio", "San Roque", "Santa Rosa"],
          "Laguna": ["Poblacion", "Bagong Bayan", "San Isidro", "San Juan", "Santa Cruz"]
      },
      "Batangas": {
          "Batangas City": ["Poblacion", "Alangilan", "Calicanto", "Santa Clara", "Mataas Na Lupa"],
          "Lipa City": ["Poblacion", "Balintawak", "Marawoy", "Del Rosario", "Mataas Na Lupa"],
          "Tanauan City": ["Poblacion", "Bagbag", "Bariis", "San Isidro", "Santo Domingo"],
          "Tanauan": ["Poblacion", "Sampaloc", "San Jose", "San Roque", "Santa Clara"],
          "Nasugbu": ["Poblacion", "Balayong", "Matabungkay", "Kaylaway", "Anilao"],
          "Lemery": ["Poblacion", "San Juan", "San Roque", "Santa Rita", "Balibago"],
          "Lian": ["Poblacion", "Balayan", "San Juan", "San Roque", "Santa Rita"],
          "Calatagan": ["Poblacion", "San Andres", "San Juan", "San Roque", "Santa Maria"]
      },
      "Rizal": {
          "Antipolo City": ["Poblacion", "San Roque", "San Isidro", "San Juan", "Bagong Nayon"],
          "Taytay": ["Poblacion", "San Isidro", "San Juan", "Santa Ana", "Barangay 1"],
          "Cainta": ["Poblacion", "San Isidro", "San Juan", "Santa Lucia", "Barangay 2"],
          "Angono": ["Poblacion", "San Isidro", "San Juan", "Santa Clara", "San Roque"],
          "Binangonan": ["Poblacion", "San Isidro", "San Juan", "Santa Maria", "San Roque"],
          "Cardona": ["Poblacion", "San Isidro", "San Juan", "Santa Rosa", "Barangay 1"],
          "Morong": ["Poblacion", "San Isidro", "San Juan", "Santa Cruz", "San Roque"]
      },
      "Quezon": {
          "Lucena City": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"],
          "Tayabas City": ["Poblacion", "San Roque", "San Isidro", "San Juan", "Santa Cruz"],
          "Sariaya": ["Poblacion", "San Isidro", "San Roque", "San Juan", "Santa Maria"],
          "Candelaria": ["Poblacion", "San Jose", "San Roque", "San Isidro", "Santa Lucia"],
          "Tiaong": ["Poblacion", "San Isidro", "San Roque", "San Juan", "Santa Rosa"],
          "Gumaca": ["Poblacion", "San Roque", "San Isidro", "San Juan", "Santa Cruz"],
          "Mauban": ["Poblacion", "San Isidro", "San Roque", "San Juan", "Santa Maria"],
          "Lucban": ["Poblacion", "San Roque", "San Isidro", "San Juan", "Santa Clara"]
      }
    },
    "Region IV-B - MIMAROPA": {
      "Occidental Mindoro": {
          "Mamburao": ["Poblacion", "San Jose", "Santa Cruz", "San Isidro", "San Roque"],
          "Sablayan": ["Poblacion", "San Miguel", "Santa Rita", "San Antonio", "San Vicente"],
          "San Jose": ["Poblacion", "San Roque", "San Isidro", "Santa Cruz", "San Juan"],
          "Looc": ["Poblacion", "San Isidro", "San Jose", "Santa Maria", "San Roque"],
          "Lubang": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"],
          "Rizal": ["Poblacion", "San Isidro", "San Jose", "Santa Rita", "San Roque"],
          "Sablayan": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"]
      },
      "Oriental Mindoro": {
          "Calapan City": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Maria"],
          "Puerto Galera": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Lucia"],
          "Baco": ["Poblacion", "San Jose", "San Isidro", "San Roque", "Santa Cruz"],
          "Bansud": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Maria"],
          "Bulalacao": ["Poblacion", "San Roque", "San Jose", "Santa Rita", "San Isidro"],
          "Gloria": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Maria"],
          "Mansalay": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"],
          "Naujan": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Maria"],
          "Pinamalayan": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"],
          "Pola": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Maria"],
          "Roxas": ["Poblacion", "San Roque", "San Jose", "Santa Lucia", "San Isidro"],
          "Socorro": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Maria"],
          "Bongabong": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"]
      },
      "Marinduque": {
          "Boac": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Cruz"],
          "Buenavista": ["Poblacion", "San Roque", "San Jose", "Santa Maria", "San Isidro"],
          "Gasan": ["Poblacion", "San Jose", "San Roque", "San Isidro", "Santa Rita"],
          "Mogpog": ["Poblacion", "San Roque", "San Jose", "Santa Maria", "San Isidro"],
          "Santa Cruz": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Rita"],
          "Torrijos": ["Poblacion", "San Roque", "San Jose", "Santa Maria", "San Isidro"]
      },
      "Romblon": {
          "Romblon": ["Poblacion", "San Jose", "San Roque", "Santa Cruz", "San Isidro"],
          "Odiongan": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Maria"],
          "San Agustin": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"],
          "San Andres": ["Poblacion", "San Jose", "San Roque", "Santa Maria", "San Isidro"],
          "San Fernando": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"],
          "Santa Fe": ["Poblacion", "San Jose", "San Roque", "Santa Maria", "San Isidro"],
          "Cajidiocan": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"]
      },
      "Palawan": {
          "Puerto Princesa City": ["Poblacion", "San Jose", "San Roque", "Santa Cruz", "San Isidro"],
          "Aborlan": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Maria"],
          "Narra": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"],
          "Quezon": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Maria"],
          "Roxas": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"],
          "San Vicente": ["Poblacion", "San Jose", "San Roque", "Santa Maria", "San Isidro"],
          "El Nido": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"],
          "Coron": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Maria"],
          "Culion": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"],
          "Bataraza": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Maria"]
      }
    },
    "Region V - Bicol Region": {
        "Albay": {
            "Legazpi City": ["Poblacion", "San Roque", "San Jose", "Barangay 1", "Barangay 2"],
            "Tabaco City": ["Poblacion", "San Francisco", "San Isidro", "San Juan", "Santa Cruz"],
            "Ligao City": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Maria"],
            "Bacacay": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Rita"],
            "Camalig": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"],
            "Daraga": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Maria"],
            "Guinobatan": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"],
            "Jovellar": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Rita"]
        },
        "Camarines Norte": {
            "Daet": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Cruz"],
            "Basud": ["Poblacion", "San Roque", "San Jose", "Santa Maria", "San Isidro"],
            "Capalonga": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Rita"],
            "Jose Panganiban": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"],
            "Labo": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Maria"],
            "Mercedes": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"]
        },
        "Camarines Sur": {
            "Naga City": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Cruz"],
            "Iriga City": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"],
            "Baao": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Maria"],
            "Balatan": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"],
            "Bato": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Rita"],
            "Bombon": ["Poblacion", "San Roque", "San Jose", "Santa Maria", "San Isidro"],
            "Buhi": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Cruz"]
        },
        "Catanduanes": {
            "Virac": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"],
            "Bagamanoc": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Maria"],
            "Baras": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"],
            "Bato": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Maria"],
            "Caramoran": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"]
        },
        "Masbate": {
            "Masbate City": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"],
            "Aroroy": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Maria"],
            "Baleno": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"],
            "Balud": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Maria"],
            "Cataingan": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"]
        },
        "Sorsogon": {
            "Sorsogon City": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"],
            "Barcelona": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Maria"],
            "Bulusan": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"],
            "Gubat": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Maria"],
            "Irosin": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"],
            "Matnog": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Maria"]
        }
    },
    "Region VI - Western Visayas": {
        "Aklan": {
            "Kalibo": ["Poblacion", "Pangpang", "Balabag", "Tigayon", "Quinobcob"],
            "Nabas": ["Poblacion", "San Isidro", "San Jose", "San Roque", "Santa Cruz"],
            "Malay": ["Poblacion", "Barangay 1", "Barangay 2", "Caticlan", "Boracay"],
            "Banga": ["Poblacion", "San Isidro", "San Jose", "San Roque", "Santa Maria"],
            "Libacao": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"]
        },
        "Antique": {
            "San Jose de Buenavista": ["Poblacion", "San Roque", "San Isidro", "San Jose", "Santa Cruz"],
            "Patnongon": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Maria"],
            "Tibiao": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"],
            "Valderrama": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Maria"],
            "Sibalom": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"]
        },
        "Capiz": {
            "Roxas City": ["Poblacion", "Baybay", "Dumalag", "Cogon", "Lawa-an"],
            "Panay": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Cruz"],
            "Cuartero": ["Poblacion", "San Roque", "San Jose", "Santa Maria", "San Isidro"],
            "Mambusao": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Cruz"]
        },
        "Guimaras": {
            "Jordan": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Cruz"],
            "Nueva Valencia": ["Poblacion", "San Roque", "San Jose", "Santa Maria", "San Isidro"],
            "Sibunag": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Maria"]
        },
        "Iloilo": {
            "Iloilo City": ["Poblacion", "La Paz", "Jaro", "Molo", "Arevalo"],
            "Passi City": ["Poblacion", "San Roque", "San Jose", "Santa Maria", "San Isidro"],
            "Pavia": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Cruz"],
            "Miagao": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"],
            "Dumangas": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Maria"]
        },
        "Negros Occidental": {
            "Bacolod City": ["Poblacion", "Luzuriaga", "Alangilan", "Barangay 1", "Barangay 2"],
            "San Carlos City": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"],
            "Bago City": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Maria"],
            "Cadiz City": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"],
            "Himamaylan City": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Maria"],
            "Kabankalan City": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"]
        }
    },
    "Region VII - Central Visayas": {
        "Cebu": {
            "Cebu City": ["Poblacion", "Lahug", "Banilad", "Mabolo", "Talamban"],
            "Mandaue City": ["Poblacion", "Cebu Light", "Tipolo", "Subangdaku", "Looc"],
            "Lapu-Lapu City": ["Poblacion", "Poblacion Zone 1", "Poblacion Zone 2", "Gun-ob", "Mactan"],
            "Talisay City": ["Poblacion", "San Isidro", "Tabunok", "Cansojong", "Bulacao"],
            "Toledo City": ["Poblacion", "Bagacay", "Poblacion Zone 2", "Tubod", "Apas"]
        },
        "Bohol": {
            "Tagbilaran City": ["Poblacion", "Bool", "Cogon", "Tampisaw", "Barangay 1"],
            "Carmen": ["Poblacion", "Sikatuna", "Batuan", "Barangay 1", "Barangay 2"],
            "Dauis": ["Poblacion", "Barangay 1", "Barangay 2", "San Isidro", "San Roque"],
            "Loboc": ["Poblacion", "Barangay 1", "Barangay 2", "San Roque", "Santa Cruz"],
            "Tubigon": ["Poblacion", "San Isidro", "San Jose", "Barangay 1", "Barangay 2"]
        },
        "Negros Oriental": {
            "Dumaguete City": ["Poblacion", "Batinguel", "Banilad", "Barangay 1", "Barangay 2"],
            "Bayawan City": ["Poblacion", "Barangay 1", "San Isidro", "San Roque", "Santa Maria"],
            "Bais City": ["Poblacion", "Barangay 1", "San Isidro", "San Roque", "Santa Cruz"],
            "Sibulan": ["Poblacion", "Barangay 1", "San Jose", "San Roque", "Santa Maria"]
        },
        "Siquijor": {
            "Siquijor": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Cruz"],
            "Larena": ["Poblacion", "San Roque", "San Jose", "Santa Maria", "San Isidro"],
            "Lazi": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Santa Maria"],
            "Maria": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "San Isidro"]
        }
    },
    "Region VIII - Eastern Visayas": {
        "Leyte": {
            "Tacloban City": ["Poblacion", "Santa Elena", "Santa Fe", "Barangay 1", "Barangay 2"],
            "Ormoc City": ["Poblacion", "Barangay 1", "Barangay 2", "San Isidro", "San Roque"],
            "Baybay City": ["Poblacion", "San Isidro", "San Roque", "Barangay 1", "Barangay 2"],
            "Carigara": ["Poblacion", "Barangay 1", "Barangay 2", "San Jose", "San Roque"],
            "Tolosa": ["Poblacion", "San Isidro", "San Roque", "Barangay 1", "Barangay 2"]
        },
        "Southern Leyte": {
            "Maasin City": ["Poblacion", "San Roque", "San Isidro", "Barangay 1", "Barangay 2"],
            "Sogod": ["Poblacion", "San Isidro", "San Roque", "Barangay 1", "Barangay 2"],
            "Bontoc": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"],
            "Libagon": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"]
        },
        "Samar (Western Samar)": {
            "Catbalogan City": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"],
            "Basey": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"],
            "Calbayog City": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"],
            "Paranas": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"]
        },
        "Northern Samar": {
            "Catarman": ["Poblacion", "San Roque", "San Isidro", "San Jose", "Barangay 1"],
            "Allen": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"],
            "Laoang": ["Poblacion", "San Roque", "San Isidro", "San Jose", "Barangay 1"],
            "Victoria": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"]
        },
        "Eastern Samar": {
            "Borongan City": ["Poblacion", "San Roque", "San Isidro", "San Jose", "Barangay 1"],
            "Dolores": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"],
            "Guiuan": ["Poblacion", "San Roque", "San Isidro", "San Jose", "Barangay 1"],
            "Salcedo": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"]
        },
        "Biliran": {
            "Naval": ["Poblacion", "San Roque", "San Isidro", "San Jose", "Barangay 1"],
            "Biliran": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"],
            "Almeria": ["Poblacion", "San Roque", "San Isidro", "San Jose", "Barangay 1"]
        }
    },
    "Region IX - Zamboanga Peninsula": {
        "Zamboanga del Norte": {
            "Dipolog City": ["Poblacion", "Sta. Cruz", "Barangay 1", "Barangay 2", "Cogon"],
            "Dapitan City": ["Poblacion", "San Roque", "San Isidro", "San Jose", "Barangay 1"],
            "Sindangan": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"],
            "Tampilisan": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"]
        },
        "Zamboanga del Sur": {
            "Pagadian City": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"],
            "Dumalinao": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"],
            "Dinas": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"],
            "Molave": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"]
        },
        "Zamboanga Sibugay": {
            "Ipil": ["Poblacion", "San Roque", "San Isidro", "San Jose", "Barangay 1"],
            "Kabasalan": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"],
            "Buug": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"],
            "Siay": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"]
        },
        "Zamboanga City": {
            "Poblacion": ["Canelar", "Sta. Catalina", "Tetuan", "Putik", "Divisoria"],
            "Mariki": ["Culasian", "San Roque", "Santa Maria", "San Isidro", "Barangay 5"],
            "Tetuan": ["San Roque", "San Jose", "Barangay 1", "Barangay 2", "Poblacion"],
            "Sta. Barbara": ["San Isidro", "San Roque", "San Jose", "Barangay 1", "Barangay 2"]
        }
    },
    "Region X - Northern Mindanao": {
        "Bukidnon": {
            "Malaybalay City": ["Poblacion", "Sumpong", "Bagontaas", "Patpat", "Manalog"],
            "Valencia City": ["Poblacion", "Bagontaas", "Central", "Bagang", "San Miguel"],
            "Manolo Fortich": ["Poblacion", "Tignapoloan", "San Miguel", "Kahaponan", "Maluko"],
            "Baungon": ["Poblacion", "Kisolon", "Langonan", "Mantalongon", "San Vicente"]
        },
        "Camiguin": {
            "Mambajao": ["Poblacion", "Guinsiliban", "San Roque", "Binuangan", "Barangay 1"],
            "Catarman": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"],
            "Mahinog": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"]
        },
        "Misamis Occidental": {
            "Oroquieta City": ["Poblacion", "San Roque", "San Jose", "Santa Cruz", "Barangay 1"],
            "Ozamiz City": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"],
            "Tangub City": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"],
            "Don Victoriano": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"]
        },
        "Misamis Oriental": {
            "Cagayan de Oro City": ["Poblacion", "Carmen", "Barangay 1", "Barangay 2", "Macasandig"],
            "El Salvador City": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"],
            "Gingoog City": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"],
            "Opol": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"]
        }
    },
    "Region XI - Davao Region": {
        "Davao de Oro": {
            "Nabunturan": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"],
            "Maco": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"],
            "Monkayo": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"],
            "Compostela": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"]
        },
        "Davao del Norte": {
            "Tagum City": ["Poblacion", "Magugpo", "San Isidro", "San Roque", "San Jose"],
            "Panabo City": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"],
            "Kapalong": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"],
            "New Corella": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"]
        },
        "Davao del Sur": {
            "Digos City": ["Poblacion", "San Roque", "San Isidro", "San Jose", "Barangay 1"],
            "Bansalan": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"],
            "Hagonoy": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"],
            "Matanao": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"]
        },
        "Davao Occidental": {
            "Malita": ["Poblacion", "San Roque", "San Isidro", "San Jose", "Barangay 1"],
            "Santa Maria": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"],
            "Jose Abad Santos": ["Poblacion", "San Roque", "San Isidro", "San Jose", "Barangay 1"]
        },
        "Davao City": {
            "Poblacion District": ["Agdao", "Buhangin", "Tugbok", "Catalunan Grande", "Matina"],
            "Talomo District": ["Bago Oshiro", "Buhangin", "Calinan", "Marilog", "Talomo"],
            "Agdao District": ["Agdao", "Lizada", "Catalunan Grande", "Catalunan Pequeño", "Talandang"],
            "Buhangin District": ["Buhangin Proper", "Talandang", "Malinao", "Barangay 1", "Barangay 2"]
        }
    },
    "Region XII - SOCCSKSARGEN": {
        "South Cotabato": {
            "Koronadal City": ["Poblacion", "San Isidro", "San Roque", "Barangay 1", "Barangay 2"],
            "Polomolok": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"],
            "Santo Niño": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"],
            "Tupi": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"]
        },
        "Cotabato (North Cotabato)": {
            "Kidapawan City": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"],
            "M'lang": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"],
            "Tulunan": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"],
            "Makilala": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"]
        },
        "Sultan Kudarat": {
            "Tacurong City": ["Poblacion", "San Roque", "San Isidro", "San Jose", "Barangay 1"],
            "Isulan": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"],
            "Lambayong": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"],
            "President Quirino": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"]
        },
        "Sarangani": {
            "Alabel": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"],
            "Glan": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"],
            "Malungon": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"]
        },
        "General Santos City": {
            "Poblacion District": ["Dadiangas North", "Dadiangas South", "Lagao", "Bulan", "Sinawal"],
            "Banga District": ["Bula", "Fatima", "Barangay 1", "Barangay 2", "Barangay 3"],
            "Calumpang District": ["Calumpang", "Tambler", "Aguinaldo", "Barangay 1", "Barangay 2"]
        }
    },
    "Region XIII - Caraga": {
        "Agusan del Norte": {
            "Butuan City": ["Poblacion", "Buenavista", "Libertad", "Barangay 1", "Barangay 2"],
            "Cabadbaran City": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"],
            "Las Nieves": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"],
            "Buenavista": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"]
        },
        "Agusan del Sur": {
            "Bayugan City": ["Poblacion", "San Roque", "San Isidro", "San Jose", "Barangay 1"],
            "Esperanza": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"],
            "San Francisco": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"],
            "Trento": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"]
        },
        "Surigao del Norte": {
            "Surigao City": ["Poblacion", "Bgy. 1", "Bgy. 2", "San Roque", "San Isidro"],
            "Burgos": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"],
            "Claver": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"],
            "Dapa": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"]
        },
        "Surigao del Sur": {
            "Tandag City": ["Poblacion", "San Roque", "San Isidro", "San Jose", "Barangay 1"],
            "Bislig City": ["Poblacion", "San Roque", "San Isidro", "San Jose", "Barangay 1"],
            "Cantilan": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"],
            "Carmen": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"]
        },
        "Dinagat Islands": {
            "San Jose": ["Poblacion", "San Roque", "San Isidro", "San Jose", "Barangay 1"],
            "Dinagat": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"],
            "Tubajon": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"]
        }
    },
    "BARMM - Bangsamoro Autonomous Region": {
        "Basilan": {
            "Isabela City": ["Poblacion", "Santa Clara", "Barangay 1", "Barangay 2", "San Roque"],
            "Lamitan City": ["Poblacion", "San Jose", "San Isidro", "San Roque", "Barangay 1"],
            "Tipo-Tipo": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"],
            "Sumisip": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"]
        },
        "Lanao del Sur": {
            "Marawi City": ["Poblacion", "Sagonsongan", "Lilod Madaya", "Datu Saber", "Barangay 1"],
            "Bacolod-Kalawi": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"],
            "Balabagan": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"],
            "Wao": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"]
        },
        "Maguindanao": {
            "Cotabato City": ["Poblacion", "Arenas", "Bgy. 1", "Bgy. 2", "San Roque"],
            "Mamasapano": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"],
            "Shariff Aguak": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"],
            "Sultan Kudarat (Maguindanao)": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"]
        },
        "Sulu": {
            "Jolo": ["Poblacion", "Santa Cruz", "San Roque", "San Isidro", "Barangay 1"],
            "Indanan": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"],
            "Kalingalan Caluang": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"],
            "Patikul": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"]
        },
        "Tawi-Tawi": {
            "Bongao": ["Poblacion", "Tubig Basag", "Sanga-Sanga", "Buan", "Barangay 1"],
            "Mapun": ["Poblacion", "San Roque", "San Isidro", "San Jose", "Barangay 1"],
            "Panglima Sugala": ["Poblacion", "San Isidro", "San Roque", "San Jose", "Barangay 1"],
            "Sapa-Sapa": ["Poblacion", "San Roque", "San Jose", "San Isidro", "Barangay 1"]
        }
    }
  };

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
        const region = document.getElementById('location_region').value;
        const province = document.getElementById('location_province').value;
        const city = document.getElementById('location_city').value;
        const barangay = document.getElementById('location_barangay').value;
        const postal = document.getElementById('postal_code').value;
        const street = document.getElementById('street_address').value;

        if (street && barangay && city && province && region) {
            const fullAddress = `${street}, Barangay ${barangay}, ${city}, ${province}, ${region}${postal ? ' ' + postal : ''}`;
            document.getElementById('full_address_preview').value = fullAddress;
            document.getElementById('location_hidden').value = fullAddress;
        }
    }

    function populateRegions() {
        const regionSelect = document.getElementById('location_region');
        Object.keys(locationData).forEach(region => {
            const option = document.createElement('option');
            option.value = region;
            option.textContent = region;
            regionSelect.appendChild(option);
        });
    }

    document.getElementById('location_region').addEventListener('change', function() {
        const region = this.value;
        const provinceSelect = document.getElementById('location_province');
        const citySelect = document.getElementById('location_city');
        const barangaySelect = document.getElementById('location_barangay');

        provinceSelect.innerHTML = '<option value="">-- Select Province --</option>';
        citySelect.innerHTML = '<option value="">-- Select Province First --</option>';
        barangaySelect.innerHTML = '<option value="">-- Select City First --</option>';
        citySelect.disabled = true;
        barangaySelect.disabled = true;

        if (region) {
            provinceSelect.disabled = false;
            const provinces = locationData[region];
            Object.keys(provinces).forEach(province => {
                const option = document.createElement('option');
                option.value = province;
                option.textContent = province;
                provinceSelect.appendChild(option);
            });
        } else {
            provinceSelect.disabled = true;
        }
        updateFullAddress();
    });

    document.getElementById('location_province').addEventListener('change', function() {
        const region = document.getElementById('location_region').value;
        const province = this.value;
        const citySelect = document.getElementById('location_city');
        const barangaySelect = document.getElementById('location_barangay');

        citySelect.innerHTML = '<option value="">-- Select City --</option>';
        barangaySelect.innerHTML = '<option value="">-- Select City First --</option>';
        barangaySelect.disabled = true;

        if (province) {
            citySelect.disabled = false;
            const cities = locationData[region][province];
            Object.keys(cities).forEach(city => {
                const option = document.createElement('option');
                option.value = city;
                option.textContent = city;
                citySelect.appendChild(option);
            });
        } else {
            citySelect.disabled = true;
        }
        updateFullAddress();
    });

    document.getElementById('location_city').addEventListener('change', function() {
        const region = document.getElementById('location_region').value;
        const province = document.getElementById('location_province').value;
        const city = this.value;
        const barangaySelect = document.getElementById('location_barangay');

        barangaySelect.innerHTML = '<option value="">-- Select Barangay --</option>';

        if (city) {
            barangaySelect.disabled = false;
            const barangays = locationData[region][province][city];
            barangays.forEach(barangay => {
                const option = document.createElement('option');
                option.value = barangay;
                option.textContent = barangay;
                barangaySelect.appendChild(option);
            });

            updateMapForCity(city);
        } else {
            barangaySelect.disabled = true;
        }
        updateFullAddress();
    });

    document.getElementById('location_barangay').addEventListener('change', updateFullAddress);
    document.getElementById('postal_code').addEventListener('input', updateFullAddress);
    document.getElementById('street_address').addEventListener('input', updateFullAddress);

    function updateMapForCity(city) {
        const cityCoordinates = {
            "Quezon City": [14.6507, 121.0494],
            "Manila": [14.5995, 120.9842],
            "Makati": [14.5547, 121.0244],
            "Pasig": [14.5764, 121.0851],
            "Taguig": [14.5176, 121.0509],
            "Mandaluyong": [14.5794, 121.0359],
            "Parañaque": [14.4793, 121.0198],
            "Las Piñas": [14.4449, 120.9834],
            "Muntinlupa": [14.4083, 121.0399],
            "Santa Rosa": [14.3123, 121.1114],
            "Calamba": [14.2117, 121.1653],
            "Biñan": [14.3382, 121.0852],
            "San Pedro": [14.3585, 121.0167],
            "Bacoor": [14.4593, 120.9441],
            "Imus": [14.4297, 120.9367],
            "Dasmariñas": [14.3294, 120.9366],
            "General Trias": [14.3856, 120.8817],
            "Antipolo": [14.5864, 121.1755],
            "Cainta": [14.5781, 121.1222],
            "Taytay": [14.5574, 121.1324],
            "Batangas City": [13.7565, 121.0583],
            "Lipa": [13.9411, 121.1622],
            "Angeles City": [15.1450, 120.5887],
            "San Fernando": [15.0285, 120.6897],
            "Malolos": [14.8433, 120.8114],
            "Meycauayan": [14.7345, 120.9634],
            "Cebu City": [10.3157, 123.8854],
            "Mandaue": [10.3237, 123.9227],
            "Lapu-Lapu": [10.3103, 123.9494],
            "Davao City": [7.1907, 125.4553],
            "Laoag City": [18.1987, 120.5937],
            "Vigan City": [17.5747, 120.3869],
            "Dagupan": [16.0433, 120.3334],
            "San Fernando": [16.6169, 120.3169],
            "Tuguegarao": [17.6132, 121.7270],
            "Ilagan": [17.1453, 121.8854],
            "Bayombong": [16.4833, 121.1500],
            "Cabanatuan": [15.4860, 120.9670],
            "Gapan": [15.3075, 120.9467],
            "Tarlac City": [15.4754, 120.5963],
            "Olongapo": [14.8294, 120.2824],
            "Balanga": [14.6767, 120.5364],
            "Baler": [15.7592, 121.5611],
            "Kawit": [14.4471, 120.9039],
            "Rosario": [14.4175, 120.8567],
            "Silang": [14.2309, 120.9771],
            "Tagaytay": [14.1053, 120.9621],
            "Trece Martires": [14.2824, 120.8674],
            "Cabuyao": [14.2784, 121.1244],
            "Los Baños": [14.1655, 121.2404],
            "Bay": [14.1833, 121.2833],
            "San Pablo": [14.0683, 121.3256],
            "Sta. Cruz": [14.2793, 121.4161],
            "Angono": [14.5271, 121.1533],
            "Binangonan": [14.4644, 121.1925],
            "Rodriguez": [14.7167, 121.1167],
            "Teresa": [14.5600, 121.2094],
            "Morong": [14.5167, 121.2333],
            "Tanauan": [14.0853, 121.1500],
            "Santo Tomas": [14.1078, 121.1414],
            "Lemery": [13.9172, 120.8928],
            "Talisay": [14.1039, 120.9267],
            "Nasugbu": [14.0686, 120.6331],
            "Lucena": [13.9372, 121.6175],
            "Tayabas": [14.0268, 121.5926],
            "Sariaya": [13.9619, 121.5267],
            "Candelaria": [13.9319, 121.4233],
            "Tiaong": [13.9567, 121.3267],
            "Boac": [13.4500, 121.8333],
            "Sta. Cruz": [13.4714, 121.9114],
            "Mamburao": [13.2242, 120.5917],
            "Sablayan": [12.8372, 120.7708],
            "Calapan": [13.4119, 121.1803],
            "Puerto Galera": [13.5067, 120.9550],
            "Puerto Princesa": [9.7392, 118.7353],
            "Coron": [12.0072, 120.2092],
            "El Nido": [11.1947, 119.4014],
            "Romblon": [12.5778, 122.2692],
            "Odiongan": [12.4019, 122.0081],
            "Legazpi": [13.1391, 123.7436],
            "Tabaco": [13.3594, 123.7322],
            "Ligao": [13.2219, 123.5303],
            "Daet": [14.1114, 122.9550],
            "Labo": [14.1300, 122.8500],
            "Naga": [13.6192, 123.1814],
            "Iriga": [13.4281, 123.4169],
            "Pili": [13.5825, 123.2914],
            "Virac": [13.5833, 124.2333],
            "Masbate City": [12.3681, 123.6178],
            "Sorsogon City": [12.9742, 124.0078],
            "Bulan": [12.6703, 123.8764],
            "Kalibo": [11.7044, 122.3678],
            "San Jose": [12.3508, 121.9336],
            "Roxas City": [11.5850, 122.7508],
            "Jordan": [10.5917, 122.5711],
            "Nueva Valencia": [10.4389, 122.6156],
            "Iloilo City": [10.7202, 122.5621],
            "Oton": [10.6972, 122.4769],
            "Passi": [11.1089, 122.6408],
            "Pototan": [10.9492, 122.6303],
            "Bacolod": [10.6770, 122.9506],
            "Silay": [10.8006, 123.0003],
            "Victorias": [10.9006, 123.0772],
            "Bago": [10.5378, 122.8358],
            "Tagbilaran": [9.6479, 123.8542],
            "Panglao": [9.5806, 123.7544],
            "Tubigon": [10.0497, 124.0689],
            "Talisay": [10.2444, 123.8494],
            "Toledo": [10.3778, 123.6394],
            "Danao": [10.5197, 124.0267],
            "Naga": [10.2081, 123.7575],
            "Dumaguete": [9.3068, 123.3054],
            "Bais": [9.5897, 123.1217],
            "Tanjay": [9.5172, 123.1572],
            "Siquijor": [9.2167, 123.5167],
            "Naval": [11.5603, 124.3953],
            "Borongan": [11.6053, 125.4336],
            "Guiuan": [11.0353, 125.7258],
            "Tacloban": [11.2444, 125.0039],
            "Ormoc": [11.0064, 124.6075],
            "Baybay": [10.6794, 124.8011],
            "Catarman": [12.4992, 124.6378],
            "Allen": [12.5078, 124.2781],
            "Calbayog": [12.0664, 124.6028],
            "Catbalogan": [11.7750, 124.8881],
            "Maasin": [10.1306, 124.8406],
            "Sogod": [10.3844, 125.0089],
            "Dipolog": [8.5833, 123.3417],
            "Dapitan": [8.6500, 123.4167],
            "Pagadian": [7.8275, 123.4353],
            "Zamboanga City": [6.9214, 122.0790],
            "Ipil": [7.7833, 122.5833],
            "Malaybalay": [8.1533, 125.1278],
            "Valencia": [7.9069, 125.0942],
            "Mambajao": [9.2500, 124.7167],
            "Iligan": [8.2280, 124.2452],
            "Tubod": [8.0500, 123.7833],
            "Oroquieta": [8.4858, 123.8025],
            "Ozamiz": [8.1500, 123.8417],
            "Cagayan de Oro": [8.4542, 124.6319],
            "Gingoog": [8.8267, 125.1017],
            "El Salvador": [8.5333, 124.5167],
            "Nabunturan": [7.6028, 125.9639],
            "Montevista": [7.7000, 125.9833],
            "Tagum": [7.4478, 125.8078],
            "Panabo": [7.3069, 125.6836],
            "Island Garden City of Samal": [7.0742, 125.7089],
            "Digos": [6.7497, 125.3572],
            "Malita": [6.4000, 125.6167],
            "Don Marcelino": [6.2333, 125.6333],
            "Mati": [6.9553, 126.2178],
            "Baganga": [7.5597, 126.5606],
            "Kidapawan": [7.0089, 125.0892],
            "Midsayap": [7.1914, 124.5319],
            "Alabel": [6.1000, 125.2833],
            "Glan": [5.8167, 125.2000],
            "General Santos": [6.1164, 125.1717],
            "Koronadal": [6.5008, 124.8467],
            "Polomolok": [6.2167, 125.0667],
            "Isulan": [6.6333, 124.6000],
            "Tacurong": [6.6908, 124.6778],
            "Butuan": [8.9475, 125.5406],
            "Cabadbaran": [9.1228, 125.5344],
            "Bayugan": [8.7139, 125.7431],
            "Prosperidad": [8.6000, 125.9167],
            "Surigao City": [9.7869, 125.4906],
            "Tandag": [9.0781, 126.2003],
            "Bislig": [8.2158, 126.3214],
            "San Jose": [10.0667, 125.6167],
            "Isabela City": [6.7011, 121.9711],
            "Lamitan": [6.6500, 122.1333],
            "Marawi": [8.0000, 124.2833],
            "Malabang": [7.5833, 124.0667],
            "Cotabato City": [7.2231, 124.2453],
            "Jolo": [6.0544, 121.0033],
            "Parang": [6.0833, 121.0167],
            "Bongao": [5.0297, 119.7731],
            "Languyan": [5.0500, 119.8500],
            "Bangued": [17.5967, 120.6203],
            "Dolores": [17.6667, 120.7667],
            "Kabugao": [18.0333, 121.1667],
            "Luna": [18.4500, 121.4000],
            "Baguio": [16.4023, 120.5960],
            "La Trinidad": [16.4592, 120.5853],
            "Itogon": [16.3667, 120.6833],
            "Tuba": [16.3167, 120.5667],
            "Lagawe": [16.8167, 121.1667],
            "Banaue": [16.9167, 121.0583],
            "Tabuk": [17.4167, 121.4500],
            "Lubuagan": [17.3333, 121.1833],
            "Bontoc": [17.0833, 120.9667],
            "Sagada": [17.0833, 120.9000]
        };

        if (cityCoordinates[city]) {
            const coords = cityCoordinates[city];
            map.setView(coords, 14);
            marker.setLatLng(coords);
            updateCoordinates(coords[0], coords[1]);
        }
    }

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