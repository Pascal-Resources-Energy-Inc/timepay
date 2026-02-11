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
    border-radius: .25rem;a
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
          "Metro Manila": [
              "Quezon City",
              "Manila",
              "Makati",
              "Pasig",
              "Taguig",
              "Mandaluyong",
              "Parañaque",
              "Las Piñas",
              "Muntinlupa",
              "Pasay",
              "Caloocan",
              "Malabon",
              "Navotas",
              "Valenzuela",
              "Pateros",
              "San Juan",
              "Marikina"
          ]
      },

      "CAR - Cordillera Administrative Region": {
          "Abra": [
              "Bangued",
              "Boliney",
              "Bucay",
              "Bucloc",
              "Daguioman",
              "Danglas",
              "Dolores",
              "La Paz",
              "Lacub",
              "Lagangilang",
              "Lagayan",
              "Langiden",
              "Licuan-Baay",
              "Luba",
              "Malibcong",
              "Manabo",
              "Peñarrubia",
              "Pidigan",
              "Pilar",
              "Sallapadan",
              "San Isidro",
              "San Juan",
              "San Quintin",
              "Tayum",
              "Tineg",
              "Tubo",
              "Villaviciosa"
          ],
          "Apayao": [
              "Calanasan",
              "Conner",
              "Flora",
              "Kabugao",
              "Luna",
              "Pudtol",
              "Santa Marcela"
          ],
          "Benguet": [
              "Baguio City",
              "Atok",
              "Bakun",
              "Bokod",
              "Buguias",
              "Itogon",
              "Kabayan",
              "Kapangan",
              "Kibungan",
              "La Trinidad",
              "Mankayan",
              "Sablan",
              "Tuba",
              "Tublay"
          ],
          "Ifugao": [
              "Aguinaldo",
              "Alfonso Lista",
              "Asipulo",
              "Banaue",
              "Hingyon",
              "Hungduan",
              "Kiangan",
              "Lagawe",
              "Lamut",
              "Mayoyao",
              "Tinoc"
          ],
          "Kalinga": [
              "Tabuk City",
              "Balbalan",
              "Lubuagan",
              "Pasil",
              "Pinukpuk",
              "Rizal",
              "Tanudan",
              "Tinglayan"
          ],
          "Mountain Province": [
              "Bontoc",
              "Barlig",
              "Bauko",
              "Besao",
              "Natonin",
              "Paracelis",
              "Sabangan",
              "Sadanga",
              "Sagada",
              "Tadian"
          ]
      },

      "Region I - Ilocos Region": {
          "Ilocos Norte": [
              "Laoag City",
              "Batac City",
              "Adams",
              "Bacarra",
              "Badoc",
              "Bangui",
              "Banna",
              "Burgos",
              "Carasi",
              "Currimao",
              "Dingras",
              "Dumalneg",
              "Marcos",
              "Nueva Era",
              "Pagudpud",
              "Paoay",
              "Pasuquin",
              "Piddig",
              "Pinili",
              "San Nicolas",
              "Sarrat",
              "Solsona",
              "Vintar"
          ],
          "Ilocos Sur": [
              "Vigan City",
              "Candon City",
              "Alilem",
              "Banayoyo",
              "Bantay",
              "Burgos",
              "Cabugao",
              "Caoayan",
              "Cervantes",
              "Galimuyod",
              "Gregorio del Pilar",
              "Lidlidda",
              "Magsingal",
              "Nagbukel",
              "Narvacan",
              "Quirino",
              "Salcedo",
              "San Emilio",
              "San Esteban",
              "San Ildefonso",
              "San Juan",
              "San Vicente",
              "Santa",
              "Santa Catalina",
              "Santa Cruz",
              "Santa Lucia",
              "Santa Maria",
              "Santiago",
              "Santo Domingo",
              "Sigay",
              "Sinait",
              "Sugpon",
              "Suyo",
              "Tagudin"
          ],
          "La Union": [
              "San Fernando City",
              "Agoo",
              "Aringay",
              "Bacnotan",
              "Bagulin",
              "Balaoan",
              "Bangar",
              "Bauang",
              "Burgos",
              "Caba",
              "Luna",
              "Naguilian",
              "Pugo",
              "Rosario",
              "San Gabriel",
              "San Juan",
              "Santo Tomas",
              "Santol",
              "Sudipen",
              "Tubao"
          ],
          "Pangasinan": [
              "Dagupan City",
              "Alaminos City",
              "San Carlos City",
              "Urdaneta City",
              "Agno",
              "Aguilar",
              "Alcala",
              "Anda",
              "Asingan",
              "Balungao",
              "Bani",
              "Basista",
              "Bautista",
              "Bayambang",
              "Binalonan",
              "Binmaley",
              "Bolinao",
              "Bugallon",
              "Burgos",
              "Calasiao",
              "Dasol",
              "Infanta",
              "Labrador",
              "Laoac",
              "Lingayen",
              "Mabini",
              "Malasiqui",
              "Manaoag",
              "Mangaldan",
              "Mangatarem",
              "Mapandan",
              "Natividad",
              "Pozorrubio",
              "Rosales",
              "San Fabian",
              "San Jacinto",
              "San Manuel",
              "San Nicolas",
              "San Quintin",
              "Santa Barbara",
              "Santa Maria",
              "Santo Tomas",
              "Sison",
              "Sual",
              "Tayug",
              "Umingan",
              "Urbiztondo",
              "Villasis"
          ]
      },

      "Region II - Cagayan Valley": {
          "Batanes": [
              "Basco",
              "Itbayat",
              "Ivana",
              "Mahatao",
              "Sabtang",
              "Uyugan"
          ],
          "Cagayan": [
              "Tuguegarao City",
              "Abulug",
              "Alcala",
              "Allacapan",
              "Amulung",
              "Aparri",
              "Baggao",
              "Ballesteros",
              "Buguey",
              "Calayan",
              "Camalaniugan",
              "Claveria",
              "Enrile",
              "Gattaran",
              "Gonzaga",
              "Iguig",
              "Lal-lo",
              "Lasam",
              "Pamplona",
              "Peñablanca",
              "Piat",
              "Rizal",
              "Sanchez-Mira",
              "Santa Ana",
              "Santa Praxedes",
              "Santa Teresita",
              "Santo Niño",
              "Solana",
              "Tuao",
              "Tumauini"
          ],
          "Isabela": [
              "Ilagan City",
              "Cauayan City",
              "Santiago City",
              "Alicia",
              "Angadanan",
              "Aurora",
              "Benito Soliven",
              "Burgos",
              "Cabagan",
              "Cabatuan",
              "Cordon",
              "Delfin Albano",
              "Dinapigue",
              "Divilacan",
              "Echague",
              "Gamu",
              "Jones",
              "Luna",
              "Maconacon",
              "Mallig",
              "Naguilian",
              "Palanan",
              "Quezon",
              "Quirino",
              "Ramon",
              "Reina Mercedes",
              "Roxas",
              "San Agustin",
              "San Guillermo",
              "San Isidro",
              "San Manuel",
              "San Mariano",
              "San Mateo",
              "San Pablo",
              "Santa Maria",
              "Santo Tomas",
              "Tumauini"
          ],
          "Nueva Vizcaya": [
              "Bayombong",
              "Aritao",
              "Bagabag",
              "Bambang",
              "Diadi",
              "Dupax del Norte",
              "Dupax del Sur",
              "Kasibu",
              "Kayapa",
              "Quezon",
              "Santa Fe",
              "Solano",
              "Villaverde"
          ],
          "Quirino": [
              "Cabarroguis",
              "Aglipay",
              "Diffun",
              "Maddela",
              "Nagtipunan",
              "Saguday"
          ]
      },

      "Region III - Central Luzon": {
          "Aurora": [
              "Baler",
              "Casiguran",
              "Dilasag",
              "Dinalungan",
              "Dipaculao",
              "Dingalan",
              "Maria Aurora",
              "San Luis"
          ],
          "Bataan": [
              "Balanga City",
              "Abucay",
              "Bagac",
              "Dinalupihan",
              "Hermosa",
              "Limay",
              "Mariveles",
              "Morong",
              "Orani",
              "Orion",
              "Pilar",
              "Samal"
          ],
          "Bulacan": [
              "Malolos City",
              "Meycauayan City",
              "San Jose del Monte City",
              "Angat",
              "Balagtas",
              "Baliuag",
              "Bocaue",
              "Bulakan",
              "Bustos",
              "Calumpit",
              "Doña Remedios Trinidad",
              "Guiguinto",
              "Hagonoy",
              "Marilao",
              "Norzagaray",
              "Obando",
              "Pandi",
              "Paombong",
              "Plaridel",
              "Pulilan",
              "San Ildefonso",
              "San Miguel",
              "San Rafael",
              "Santa Maria"
          ],
          "Nueva Ecija": [
              "Cabanatuan City",
              "Gapan City",
              "Palayan City",
              "San Jose City",
              "Science City of Muñoz",
              "Aliaga",
              "Bongabon",
              "Cabiao",
              "Carranglan",
              "Cuyapo",
              "Gabaldon",
              "General Mamerto Natividad",
              "General Tinio",
              "Guimba",
              "Jaen",
              "Laur",
              "Licab",
              "Llanera",
              "Lupao",
              "Nampicuan",
              "Pantabangan",
              "Peñaranda",
              "Quezon",
              "Rizal",
              "San Antonio",
              "San Isidro",
              "San Leonardo",
              "Santa Rosa",
              "Santo Domingo",
              "Talavera",
              "Talugtug",
              "Zaragoza"
          ],
          "Pampanga": [
              "Angeles City",
              "Mabalacat City",
              "San Fernando City",
              "Apalit",
              "Arayat",
              "Bacolor",
              "Candaba",
              "Floridablanca",
              "Guagua",
              "Lubao",
              "Macabebe",
              "Magalang",
              "Masantol",
              "Mexico",
              "Minalin",
              "Porac",
              "San Luis",
              "San Simon",
              "Santa Ana",
              "Santa Rita",
              "Santo Tomas",
              "Sasmuan"
          ],
          "Tarlac": [
              "Tarlac City",
              "Anao",
              "Bamban",
              "Camiling",
              "Capas",
              "Concepcion",
              "Gerona",
              "La Paz",
              "Mayantoc",
              "Moncada",
              "Paniqui",
              "Pura",
              "Ramos",
              "San Clemente",
              "San Jose",
              "San Manuel",
              "Santa Ignacia",
              "Victoria"
          ],
          "Zambales": [
              "Olongapo City",
              "Botolan",
              "Cabangan",
              "Candelaria",
              "Castillejos",
              "Iba",
              "Masinloc",
              "Palauig",
              "San Antonio",
              "San Felipe",
              "San Marcelino",
              "San Narciso",
              "Santa Cruz",
              "Subic"
          ]
      },

      "Region IV-A - CALABARZON": {
          "Cavite": [
              "Cavite City",
              "Bacoor City",
              "Dasmariñas City",
              "General Trias City",
              "Imus City",
              "Tagaytay City",
              "Trece Martires City",
              "Alfonso",
              "Amadeo",
              "Carmona",
              "Gen. Mariano Alvarez",
              "Indang",
              "Kawit",
              "Magallanes",
              "Maragondon",
              "Mendez",
              "Naic",
              "Noveleta",
              "Rosario",
              "Silang",
              "Tanza",
              "Ternate"
          ],
          "Laguna": [
              "Calamba City",
              "San Pedro City",
              "Biñan City",
              "Santa Rosa City",
              "Cabuyao City",
              "San Pablo City",
              "Alaminos",
              "Bay",
              "Calauan",
              "Cavinti",
              "Famy",
              "Kalayaan",
              "Liliw",
              "Los Baños",
              "Luisiana",
              "Lumban",
              "Mabitac",
              "Magdalena",
              "Majayjay",
              "Nagcarlan",
              "Paete",
              "Pagsanjan",
              "Pakil",
              "Pangil",
              "Pila",
              "Rizal",
              "San Pablo City",
              "Santa Cruz",
              "Santa Maria",
              "Siniloan",
              "Victoria"
          ],
          "Batangas": [
              "Batangas City",
              "Lipa City",
              "Tanauan City",
              "Agoncillo",
              "Alitagtag",
              "Balayan",
              "Balete",
              "Bauan",
              "Calaca",
              "Calatagan",
              "Cuenca",
              "Ibaan",
              "Laurel",
              "Lemery",
              "Lian",
              "Lobo",
              "Mabini",
              "Malvar",
              "Mataas na Kahoy",
              "Nasugbu",
              "Padre Garcia",
              "Rosario",
              "San Jose",
              "San Juan",
              "San Luis",
              "San Nicolas",
              "San Pascual",
              "Santa Teresita",
              "Santo Tomas",
              "Taal",
              "Talisay",
              "Taysan",
              "Tingloy",
              "Tuy"
          ],
          "Rizal": [
              "Antipolo City",
              "Angono",
              "Baras",
              "Binangonan",
              "Cainta",
              "Cardona",
              "Jalajala",
              "Morong",
              "Pililla",
              "Rodriguez",
              "San Mateo",
              "Tanay",
              "Taytay",
              "Teresa"
          ],
          "Quezon": [
              "Lucena City",
              "Tayabas City",
              "Agdangan",
              "Alabat",
              "Atimonan",
              "Buenavista",
              "Burdeos",
              "Calauag",
              "Candelaria",
              "Catanauan",
              "Dolores",
              "General Luna",
              "General Nakar",
              "Guinayangan",
              "Gumaca",
              "Infanta",
              "Jomalig",
              "Lopez",
              "Lucban",
              "Macalelon",
              "Mauban",
              "Mulanay",
              "Padre Burgos",
              "Pagbilao",
              "Panukulan",
              "Patnanungan",
              "Perez",
              "Pitogo",
              "Plaridel",
              "Polillo",
              "Quezon",
              "Real",
              "Sampaloc",
              "San Andres",
              "San Antonio",
              "San Francisco",
              "San Narciso",
              "Sariaya",
              "Tagkawayan",
              "Tiaong",
              "Unisan"
          ]
      },

      "Region IV-B - MIMAROPA": {
          "Occidental Mindoro": [
              "Abra de Ilog",
              "Calintaan",
              "Looc",
              "Lubang",
              "Magsaysay",
              "Mamburao",
              "Paluan",
              "Rizal",
              "Sablayan",
              "San Jose",
              "Santa Cruz"
          ],
          "Oriental Mindoro": [
              "Calapan City",
              "Baco",
              "Bansud",
              "Bongabong",
              "Bulalacao",
              "Gloria",
              "Mansalay",
              "Naujan",
              "Pinamalayan",
              "Pola",
              "Puerto Galera",
              "Roxas",
              "San Teodoro",
              "Socorro",
              "Victoria"
          ],
          "Marinduque": [
              "Boac",
              "Buenavista",
              "Gasan",
              "Mogpog",
              "Santa Cruz",
              "Torrijos"
          ],
          "Romblon": [
              "Alcantara",
              "Banton",
              "Cajidiocan",
              "Calatrava",
              "Concepcion",
              "Corcuera",
              "Ferrol",
              "Looc",
              "Magdiwang",
              "Odiongan",
              "Romblon",
              "San Agustin",
              "San Andres",
              "San Fernando",
              "San Jose",
              "Santa Fe",
              "Santa Maria"
          ],
          "Palawan": [
              "Puerto Princesa City",
              "Aborlan",
              "Agutaya",
              "Araceli",
              "Balabac",
              "Bataraza",
              "Brooke's Point",
              "Busuanga",
              "Cagayancillo",
              "Coron",
              "Culion",
              "Cuyo",
              "Dumaran",
              "El Nido",
              "Linapacan",
              "Magsaysay",
              "Narra",
              "Quezon",
              "Rizal",
              "Roxas",
              "San Vicente",
              "Sofronio Española",
              "Taytay"
          ]
      },

      "Region V - Bicol Region": {
          "Albay": [
              "Legazpi City",
              "Ligao City",
              "Tabaco City",
              "Bacacay",
              "Camalig",
              "Daraga",
              "Guinobatan",
              "Jovellar",
              "Libon",
              "Malilipot",
              "Malinao",
              "Manito",
              "Oas",
              "Pio Duran",
              "Polangui",
              "Rapu-Rapu",
              "Santo Domingo",
              "Tiwi"
          ],
          "Camarines Norte": [
              "Basud",
              "Capalonga",
              "Daet",
              "Jose Panganiban",
              "Labo",
              "Mercedes",
              "Paracale",
              "San Lorenzo Ruiz",
              "San Vicente",
              "Santa Elena",
              "Talisay",
              "Vinzons"
          ],
          "Camarines Sur": [
              "Iriga City",
              "Naga City",
              "Baao",
              "Balatan",
              "Bato",
              "Bombon",
              "Buhi",
              "Bula",
              "Cabusao",
              "Calabanga",
              "Camaligan",
              "Canaman",
              "Caramoan",
              "Del Gallego",
              "Gainza",
              "Garchitorena",
              "Goa",
              "Lagonoy",
              "Libmanan",
              "Lupi",
              "Magarao",
              "Milaor",
              "Minalabac",
              "Nabua",
              "Ocampo",
              "Pamplona",
              "Pasacao",
              "Pili",
              "Presentacion",
              "Ragay",
              "Sagñay",
              "San Fernando",
              "San Jose",
              "Sipocot",
              "Siruma",
              "Tigaon",
              "Tinambac"
          ],
          "Catanduanes": [
              "Bagamanoc",
              "Baras",
              "Bato",
              "Caramoran",
              "Gigmoto",
              "Pandan",
              "Panganiban",
              "San Andres",
              "San Miguel",
              "Viga",
              "Virac"
          ],
          "Masbate": [
              "Masbate City",
              "Aroroy",
              "Baleno",
              "Balud",
              "Batuan",
              "Cataingan",
              "Cawayan",
              "Claveria",
              "Dimasalang",
              "Esperanza",
              "Mandaon",
              "Milagros",
              "Mobo",
              "Monreal",
              "Palanas",
              "Pio V. Corpuz",
              "Placer",
              "San Fernando",
              "San Jacinto",
              "San Pascual",
              "Uson"
          ],
          "Sorsogon": [
              "Sorsogon City",
              "Barcelona",
              "Bulan",
              "Bulusan",
              "Casiguran",
              "Castilla",
              "Donsol",
              "Gubat",
              "Irosin",
              "Juban",
              "Magallanes",
              "Matnog",
              "Pilar",
              "Prieto Diaz",
              "Santa Magdalena"
          ]
      },

      "Region VI - Western Visayas": {
          "Aklan": [
              "Altavas",
              "Balete",
              "Banga",
              "Batan",
              "Buruanga",
              "Ibajay",
              "Kalibo",
              "Lezo",
              "Libacao",
              "Madalag",
              "Makato",
              "Malay",
              "Malinao",
              "Nabas",
              "New Washington",
              "Numancia",
              "Tangalan"
          ],
          "Antique": [
              "Anini-y",
              "Barbaza",
              "Belison",
              "Bugasong",
              "Caluya",
              "Culasi",
              "Hamtic",
              "Laua-an",
              "Libertad",
              "Pandan",
              "Patnongon",
              "San Jose de Buenavista",
              "San Remigio",
              "Sebaste",
              "Sibalom",
              "Tibiao",
              "Tobias Fornier",
              "Valderrama"
          ],
          "Capiz": [
              "Roxas City",
              "Cuartero",
              "Dao",
              "Dumalag",
              "Dumarao",
              "Ivisan",
              "Jamindan",
              "Ma-ayon",
              "Mambusao",
              "Panay",
              "Panitan",
              "Pilar",
              "Pontevedra",
              "President Roxas",
              "Sapi-an",
              "Sigma",
              "Tapaz"
          ],
          "Guimaras": [
              "Buenavista",
              "Jordan",
              "Nueva Valencia",
              "San Lorenzo",
              "Sibunag"
          ],
          "Iloilo": [
              "Iloilo City",
              "Passi City",
              "Ajuy",
              "Alimodian",
              "Anilao",
              "Badiangan",
              "Balasan",
              "Banate",
              "Barotac Nuevo",
              "Barotac Viejo",
              "Batad",
              "Bingawan",
              "Cabatuan",
              "Calinog",
              "Carles",
              "Concepcion",
              "Dingle",
              "Dueñas",
              "Dumangas",
              "Estancia",
              "Guimbal",
              "Igbaras",
              "Janiuay",
              "Lambunao",
              "Leganes",
              "Lemery",
              "Leon",
              "Maasin",
              "Miagao",
              "Mina",
              "New Lucena",
              "Oton",
              "Pavia",
              "Pototan",
              "San Dionisio",
              "San Enrique",
              "San Joaquin",
              "San Miguel",
              "San Rafael",
              "Santa Barbara",
              "Sara",
              "Tigbauan",
              "Tubungan",
              "Zarraga"
          ],
          "Negros Occidental": [
              "Bacolod City",
              "Bago City",
              "Cadiz City",
              "Escalante City",
              "Himamaylan City",
              "Kabankalan City",
              "La Carlota City",
              "Sagay City",
              "San Carlos City",
              "Silay City",
              "Sipalay City",
              "Talisay City",
              "Victorias City",
              "Binalbagan",
              "Calatrava",
              "Candoni",
              "Cauayan",
              "Enrique B. Magalona",
              "Hinigaran",
              "Hinoba-an",
              "Ilog",
              "Isabela",
              "La Castellana",
              "Manapla",
              "Moises Padilla",
              "Murcia",
              "Pontevedra",
              "Pulupandan",
              "Salvador Benedicto",
              "San Enrique",
              "Toboso",
              "Valladolid"
          ]
      },

      "Region VII - Central Visayas": {
          "Bohol": [
              "Tagbilaran City",
              "Alburquerque",
              "Alicia",
              "Anda",
              "Antequera",
              "Baclayon",
              "Balilihan",
              "Batuan",
              "Bien Unido",
              "Bilar",
              "Buenavista",
              "Calape",
              "Candijay",
              "Carmen",
              "Catigbian",
              "Clarin",
              "Corella",
              "Cortes",
              "Dagohoy",
              "Danao",
              "Dauis",
              "Dimiao",
              "Duero",
              "Garcia Hernandez",
              "Getafe",
              "Guindulman",
              "Inabanga",
              "Jagna",
              "Lila",
              "Loay",
              "Loboc",
              "Loon",
              "Mabini",
              "Maribojoc",
              "Panglao",
              "Pilar",
              "President Carlos P. Garcia",
              "Sagbayan",
              "San Isidro",
              "San Miguel",
              "Sevilla",
              "Sierra Bullones",
              "Sikatuna",
              "Talibon",
              "Trinidad",
              "Tubigon",
              "Ubay",
              "Valencia"
          ],
          "Cebu": [
              "Cebu City",
              "Bogo City",
              "Carcar City",
              "Danao City",
              "Lapu-Lapu City",
              "Mandaue City",
              "Naga City",
              "Talisay City",
              "Toledo City",
              "Alcantara",
              "Alcoy",
              "Alegria",
              "Aloguinsan",
              "Argao",
              "Asturias",
              "Badian",
              "Balamban",
              "Bantayan",
              "Barili",
              "Boljoon",
              "Borbon",
              "Carmen",
              "Catmon",
              "Compostela",
              "Consolacion",
              "Cordova",
              "Daanbantayan",
              "Dalaguete",
              "Dumanjug",
              "Ginatilan",
              "Liloan",
              "Madridejos",
              "Malabuyoc",
              "Medellin",
              "Minglanilla",
              "Moalboal",
              "Oslob",
              "Pilar",
              "Pinamungajan",
              "Poro",
              "Ronda",
              "Samboan",
              "San Fernando",
              "San Francisco",
              "San Remigio",
              "Santa Fe",
              "Santander",
              "Sibonga",
              "Sogod",
              "Tabogon",
              "Tabuelan",
              "Tuburan",
              "Tudela"
          ],
          "Negros Oriental": [
              "Dumaguete City",
              "Bais City",
              "Bayawan City",
              "Canlaon City",
              "Guihulngan City",
              "Tanjay City",
              "Amlan",
              "Ayungon",
              "Bacong",
              "Basay",
              "Bindoy",
              "Dauin",
              "Jimalalud",
              "La Libertad",
              "Mabinay",
              "Manjuyod",
              "Pamplona",
              "San Jose",
              "Santa Catalina",
              "Siaton",
              "Sibulan",
              "Tayasan",
              "Valencia",
              "Vallehermoso",
              "Zamboanguita"
          ],
          "Siquijor": [
              "Enrique Villanueva",
              "Larena",
              "Lazi",
              "Maria",
              "San Juan",
              "Siquijor"
          ]
      },

      "Region VIII - Eastern Visayas": {
          "Biliran": [
              "Almeria",
              "Biliran",
              "Cabucgayan",
              "Caibiran",
              "Culaba",
              "Kawayan",
              "Maripipi",
              "Naval"
          ],
          "Eastern Samar": [
              "Borongan City",
              "Arteche",
              "Balangiga",
              "Balangkayan",
              "Can-avid",
              "Dolores",
              "General MacArthur",
              "Giporlos",
              "Guiuan",
              "Hernani",
              "Jipapad",
              "Lawaan",
              "Llorente",
              "Maslog",
              "Maydolong",
              "Mercedes",
              "Oras",
              "Quinapondan",
              "Salcedo",
              "San Julian",
              "San Policarpo",
              "Sulat",
              "Taft"
          ],
          "Leyte": [
              "Tacloban City",
              "Ormoc City",
              "Baybay City",
              "Abuyog",
              "Alangalang",
              "Albuera",
              "Babatngon",
              "Barugo",
              "Bato",
              "Burauen",
              "Calubian",
              "Capoocan",
              "Carigara",
              "Dagami",
              "Dulag",
              "Hilongos",
              "Hindang",
              "Inopacan",
              "Isabel",
              "Jaro",
              "Javier",
              "Julita",
              "Kananga",
              "La Paz",
              "Leyte",
              "MacArthur",
              "Mahaplag",
              "Matag-ob",
              "Matalom",
              "Mayorga",
              "Merida",
              "Palo",
              "Palompon",
              "Pastrana",
              "San Isidro",
              "San Miguel",
              "Santa Fe",
              "Tabango",
              "Tabontabon",
              "Tanauan",
              "Tolosa",
              "Tunga",
              "Villaba"
          ],
          "Northern Samar": [
              "Allen",
              "Biri",
              "Bobon",
              "Capul",
              "Catarman",
              "Catubig",
              "Gamay",
              "Laoang",
              "Lapinig",
              "Las Navas",
              "Lavezares",
              "Lope de Vega",
              "Mapanas",
              "Mondragon",
              "Palapag",
              "Pambujan",
              "Rosario",
              "San Antonio",
              "San Isidro",
              "San Jose",
              "San Roque",
              "San Vicente",
              "Silvino Lobos",
              "Victoria"
          ],
          "Samar (Western Samar)": [
              "Catbalogan City",
              "Calbayog City",
              "Almagro",
              "Basey",
              "Calbiga",
              "Daram",
              "Gandara",
              "Hinabangan",
              "Jiabong",
              "Marabut",
              "Matuguinao",
              "Motiong",
              "Pagsanghan",
              "Paranas",
              "Pinabacdao",
              "San Jorge",
              "San Jose de Buan",
              "San Sebastian",
              "Santa Margarita",
              "Santa Rita",
              "Santo Niño",
              "Tagapul-an",
              "Talalora",
              "Tarangnan",
              "Villareal",
              "Zumarraga"
          ],
          "Southern Leyte": [
              "Maasin City",
              "Anahawan",
              "Bontoc",
              "Hinunangan",
              "Hinundayan",
              "Libagon",
              "Liloan",
              "Limasawa",
              "Macrohon",
              "Malitbog",
              "Padre Burgos",
              "Pintuyan",
              "Saint Bernard",
              "San Francisco",
              "San Juan",
              "San Ricardo",
              "Silago",
              "Sogod",
              "Tomas Oppus"
          ]
      },

      "Region IX - Zamboanga Peninsula": {
          "Zamboanga del Norte": [
              "Dapitan City",
              "Dipolog City",
              "Baliguian",
              "Bacungan",
              "Godod",
              "Gutalac",
              "Jose Dalman",
              "Kalawit",
              "Katipunan",
              "La Libertad",
              "Labason",
              "Liloy",
              "Manukan",
              "Mutia",
              "Piñan",
              "Polanco",
              "Rizal",
              "Salug",
              "Sergio Osmeña Sr.",
              "Siayan",
              "Sibuco",
              "Sibutad",
              "Sindangan",
              "Siocon",
              "Sirawai",
              "Tampilisan"
          ],
          "Zamboanga del Sur": [
              "Pagadian City",
              "Zamboanga City",
              "Aurora",
              "Bayog",
              "Dimataling",
              "Dinas",
              "Dumalinao",
              "Dumingag",
              "Guipos",
              "Josefina",
              "Kumalarang",
              "Labangan",
              "Lakewood",
              "Lapuyan",
              "Mahayag",
              "Margosatubig",
              "Midsalip",
              "Molave",
              "Pitogo",
              "Ramon Magsaysay",
              "San Miguel",
              "San Pablo",
              "Sominot",
              "Tabina",
              "Tambulig",
              "Tigbao",
              "Tukuran",
              "Vincenzo A. Sagun"
          ],
          "Zamboanga Sibugay": [
              "Alicia",
              "Buug",
              "Diplahan",
              "Imelda",
              "Ipil",
              "Kabasalan",
              "Mabuhay",
              "Malangas",
              "Naga",
              "Olutanga",
              "Payao",
              "Roseller Lim",
              "Siay",
              "Talusan",
              "Titay",
              "Tungawan"
          ],
          "Zamboanga City": [
              "Zamboanga City"
          ]
      },

      "Region X - Northern Mindanao": {
          "Bukidnon": [
              "Malaybalay City",
              "Valencia City",
              "Baungon",
              "Cabanglasan",
              "Damulog",
              "Dangcagan",
              "Don Carlos",
              "Impasugong",
              "Kadingilan",
              "Kalilangan",
              "Kibawe",
              "Kitaotao",
              "Lantapan",
              "Libona",
              "Malitbog",
              "Manolo Fortich",
              "Maramag",
              "Pangantucan",
              "Quezon",
              "San Fernando",
              "Sumilao",
              "Talakag"
          ],
          "Camiguin": [
              "Catarman",
              "Guinsiliban",
              "Mahinog",
              "Mambajao",
              "Sagay"
          ],
          "Lanao del Norte": [
              "Iligan City",
              "Bacolod",
              "Baloi",
              "Baroy",
              "Kapatagan",
              "Kauswagan",
              "Kolambugan",
              "Lala",
              "Linamon",
              "Magsaysay",
              "Maigo",
              "Matungao",
              "Munai",
              "Nunungan",
              "Pantao Ragat",
              "Pantar",
              "Poona Piagapo",
              "Salvador",
              "Sapad",
              "Sultan Naga Dimaporo",
              "Tagoloan",
              "Tangcal",
              "Tubod"
          ],
          "Misamis Occidental": [
              "Oroquieta City",
              "Ozamiz City",
              "Tangub City",
              "Aloran",
              "Baliangao",
              "Bonifacio",
              "Calamba",
              "Clarin",
              "Concepcion",
              "Don Victoriano Chiongbian",
              "Jimenez",
              "Lopez Jaena",
              "Panaon",
              "Plaridel",
              "Sapang Dalaga",
              "Sinacaban",
              "Tudela"
          ],
          "Misamis Oriental": [
              "Cagayan de Oro City",
              "Gingoog City",
              "Alubijid",
              "Balingasag",
              "Balingoan",
              "Binuangan",
              "Claveria",
              "El Salvador",
              "Gitagum",
              "Initao",
              "Jasaan",
              "Kinoguitan",
              "Lagonglong",
              "Laguindingan",
              "Libertad",
              "Lugait",
              "Magsaysay",
              "Manticao",
              "Medina",
              "Naawan",
              "Opol",
              "Salay",
              "Sugbongcogon",
              "Tagoloan",
              "Talisayan",
              "Villanueva"
          ]
      },

      "Region XI - Davao Region": {
          "Davao de Oro": [
              "Compostela",
              "Laak",
              "Mabini",
              "Maco",
              "Maragusan",
              "Mawab",
              "Monkayo",
              "Montevista",
              "Nabunturan",
              "New Bataan",
              "Pantukan"
          ],
          "Davao del Norte": [
              "Tagum City",
              "Panabo City",
              "Island Garden City of Samal",
              "Asuncion",
              "Braulio E. Dujali",
              "Carmen",
              "Kapalong",
              "New Corella",
              "San Isidro",
              "Santo Tomas",
              "Talaingod"
          ],
          "Davao del Sur": [
              "Digos City",
              "Bansalan",
              "Hagonoy",
              "Kiblawan",
              "Magsaysay",
              "Malalag",
              "Matanao",
              "Padada",
              "Santa Cruz",
              "Sulop"
          ],
          "Davao Occidental": [
              "Don Marcelino",
              "Jose Abad Santos",
              "Malita",
              "Santa Maria",
              "Sarangani"
          ],
          "Davao Oriental": [
              "Mati City",
              "Baganga",
              "Banaybanay",
              "Boston",
              "Caraga",
              "Cateel",
              "Governor Generoso",
              "Lupon",
              "Manay",
              "San Isidro",
              "Tarragona"
          ],
          "Davao City": [
              "Davao City"
          ]
      },

      "Region XII - SOCCSKSARGEN": {
          "Cotabato (North Cotabato)": [
              "Kidapawan City",
              "Alamada",
              "Aleosan",
              "Antipas",
              "Arakan",
              "Banisilan",
              "Carmen",
              "Kabacan",
              "Libungan",
              "M'lang",
              "Magpet",
              "Makilala",
              "Matalam",
              "Midsayap",
              "Pigcawayan",
              "Pikit",
              "President Roxas",
              "Tulunan"
          ],
          "Sarangani": [
              "Alabel",
              "Glan",
              "Kiamba",
              "Maasim",
              "Maitum",
              "Malapatan",
              "Malungon"
          ],
          "South Cotabato": [
              "Koronadal City",
              "General Santos City",
              "Banga",
              "Lake Sebu",
              "Norala",
              "Polomolok",
              "Santo Niño",
              "Surallah",
              "T'boli",
              "Tampakan",
              "Tantangan",
              "Tupi"
          ],
          "Sultan Kudarat": [
              "Tacurong City",
              "Bagumbayan",
              "Columbio",
              "Esperanza",
              "Isulan",
              "Kalamansig",
              "Lambayong",
              "Lebak",
              "Lutayan",
              "Palimbang",
              "President Quirino",
              "Senator Ninoy Aquino"
          ]
      },

      "Region XIII - Caraga": {
          "Agusan del Norte": [
              "Butuan City",
              "Cabadbaran City",
              "Buenavista",
              "Carmen",
              "Jabonga",
              "Kitcharao",
              "Las Nieves",
              "Magallanes",
              "Nasipit",
              "Remedios T. Romualdez",
              "Santiago",
              "Tubay"
          ],
          "Agusan del Sur": [
              "Bayugan City",
              "Bunawan",
              "Esperanza",
              "La Paz",
              "Loreto",
              "Prosperidad",
              "Rosario",
              "San Francisco",
              "San Luis",
              "Santa Josefa",
              "Sibagat",
              "Talacogon",
              "Trento",
              "Veruela"
          ],
          "Dinagat Islands": [
              "Basilisa",
              "Cagdianao",
              "Dinagat",
              "Libjo",
              "Loreto",
              "San Jose",
              "Tubajon"
          ],
          "Surigao del Norte": [
              "Surigao City",
              "Alegria",
              "Bacuag",
              "Burgos",
              "Claver",
              "Dapa",
              "Del Carmen",
              "General Luna",
              "Gigaquit",
              "Mainit",
              "Malimono",
              "Pilar",
              "Placer",
              "San Benito",
              "San Francisco",
              "San Isidro",
              "Santa Monica",
              "Sison",
              "Socorro",
              "Tagana-an",
              "Tubod"
          ],
          "Surigao del Sur": [
              "Tandag City",
              "Bislig City",
              "Barobo",
              "Bayabas",
              "Cagwait",
              "Cantilan",
              "Carmen",
              "Carrascal",
              "Cortes",
              "Hinatuan",
              "Lanuza",
              "Lianga",
              "Lingig",
              "Madrid",
              "Marihatag",
              "San Agustin",
              "San Miguel",
              "Tagbina",
              "Tago"
          ]
      },

      "BARMM - Bangsamoro Autonomous Region": {
          "Basilan": [
              "Isabela City",
              "Lamitan City",
              "Akbar",
              "Al-Barka",
              "Hadji Mohammad Ajul",
              "Hadji Muhtamad",
              "Lantawan",
              "Maluso",
              "Sumisip",
              "Tabuan-Lasa",
              "Tipo-Tipo",
              "Tuburan",
              "Ungkaya Pukan"
          ],
          "Lanao del Sur": [
              "Marawi City",
              "Bacolod-Kalawi",
              "Balabagan",
              "Balindong",
              "Bayang",
              "Binidayan",
              "Buadiposo-Buntong",
              "Bubong",
              "Butig",
              "Calanogas",
              "Ditsaan-Ramain",
              "Ganassi",
              "Kapai",
              "Kapatagan",
              "Lumba-Bayabao",
              "Lumbaca-Unayan",
              "Lumbatan",
              "Lumbayanague",
              "Madalum",
              "Madamba",
              "Malabang",
              "Marantao",
              "Marawi City",
              "Marogong",
              "Masiu",
              "Mulondo",
              "Pagayawan",
              "Piagapo",
              "Picong",
              "Poona Bayabao",
              "Pualas",
              "Saguiaran",
              "Sultan Dumalondong",
              "Tagoloan II",
              "Tamparan",
              "Taraka",
              "Tubaran",
              "Tugaya",
              "Wao"
          ],
          "Maguindanao del Norte": [
              "Barira",
              "Buldon",
              "Datu Blah T. Sinsuat",
              "Datu Odin Sinsuat",
              "Kabuntalan",
              "Matanog",
              "Northern Kabuntalan",
              "Parang",
              "Sultan Kudarat",
              "Sultan Mastura",
              "Talitay",
              "Upi"
          ],
          "Maguindanao del Sur": [
              "Ampatuan",
              "Datu Abdullah Sangki",
              "Datu Anggal Midtimbang",
              "Datu Hoffer Ampatuan",
              "Datu Montawal",
              "Datu Paglas",
              "Datu Piang",
              "Datu Salibo",
              "Datu Saudi-Ampatuan",
              "Datu Unsay",
              "General Salipada K. Pendatun",
              "Guindulungan",
              "Mamasapano",
              "Mangudadatu",
              "Pagalungan",
              "Paglat",
              "Pandag",
              "Rajah Buayan",
              "Shariff Aguak",
              "Shariff Saydona Mustapha",
              "South Upi",
              "Sultan sa Barongis",
              "Talayan"
          ],
          "Sulu": [
              "Hadji Panglima Tahil",
              "Indanan",
              "Jolo",
              "Kalingalan Caluang",
              "Lugus",
              "Luuk",
              "Maimbung",
              "Old Panamao",
              "Omar",
              "Pandami",
              "Panglima Estino",
              "Pangutaran",
              "Parang",
              "Pata",
              "Patikul",
              "Siasi",
              "Talipao",
              "Tapul"
          ],
          "Tawi-Tawi": [
              "Bongao",
              "Languyan",
              "Mapun",
              "Panglima Sugala",
              "Sapa-Sapa",
              "Sibutu",
              "Simunul",
              "Sitangkai",
              "South Ubian",
              "Tandubas",
              "Turtle Islands"
          ]
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
            cities.forEach(city => {
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
      const city = this.value;
      
      if (city) {
          document.getElementById('location_barangay').disabled = false;
          updateMapForCity(city);
      } else {
          document.getElementById('location_barangay').disabled = true;
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
              "Pasay": [14.5378, 121.0014],
              "Caloocan": [14.6507, 120.9677],
              "Malabon": [14.6628, 120.9569],
              "Navotas": [14.6692, 120.9400],
              "Valenzuela": [14.7000, 120.9833],
              "Pateros": [14.5431, 121.0686],
              "San Juan": [14.6019, 121.0355],
              "Marikina": [14.6507, 121.1029],
              
              "Bangued": [17.5967, 120.6203],
              "Boliney": [17.4167, 121.0833],
              "Bucay": [17.5333, 120.7167],
              "Bucloc": [17.3167, 120.8333],
              "Daguioman": [17.5667, 120.8167],
              "Danglas": [17.6167, 120.9167],
              "Dolores": [17.6667, 120.7667],
              "La Paz": [17.5333, 120.5833],
              "Lacub": [17.7500, 121.0167],
              "Lagangilang": [17.6500, 120.7333],
              "Lagayan": [17.6167, 120.8667],
              "Langiden": [17.6500, 120.7833],
              "Licuan-Baay": [17.5167, 120.9500],
              "Luba": [17.4000, 120.5667],
              "Malibcong": [17.5833, 121.0000],
              "Manabo": [17.5500, 120.6667],
              "Peñarrubia": [17.6167, 120.6500],
              "Pidigan": [17.5667, 120.5500],
              "Pilar": [17.6000, 120.6000],
              "Sallapadan": [17.4500, 120.9167],
              "San Isidro": [17.4833, 120.7167],
              "San Juan": [17.7167, 120.9667],
              "San Quintin": [17.5167, 120.5167],
              "Tayum": [17.6333, 120.6167],
              "Tineg": [17.6333, 120.9833],
              "Tubo": [17.2333, 120.8167],
              "Villaviciosa": [17.2833, 120.8667],
              
              "Calanasan": [18.2500, 121.0167],
              "Conner": [18.3333, 121.1833],
              "Flora": [18.1833, 121.4000],
              "Kabugao": [18.0333, 121.1667],
              "Luna": [18.4500, 121.4000],
              "Pudtol": [18.1667, 121.2000],
              "Santa Marcela": [18.4000, 121.2167],
              
              "Baguio City": [16.4023, 120.5960],
              "Atok": [16.5667, 120.7167],
              "Bakun": [16.7833, 120.6667],
              "Bokod": [16.4833, 120.8167],
              "Buguias": [16.7333, 120.8333],
              "Itogon": [16.3667, 120.6833],
              "Kabayan": [16.6167, 120.8667],
              "Kapangan": [16.5833, 120.6000],
              "Kibungan": [16.7167, 120.6333],
              "La Trinidad": [16.4592, 120.5853],
              "Mankayan": [16.8667, 120.7833],
              "Sablan": [16.4333, 120.5167],
              "Tuba": [16.3167, 120.5667],
              "Tublay": [16.5167, 120.6333],
              
              "Aguinaldo": [16.3333, 121.2000],
              "Alfonso Lista": [16.5833, 121.3000],
              "Asipulo": [16.5167, 121.0167],
              "Banaue": [16.9167, 121.0583],
              "Hingyon": [16.5667, 121.2500],
              "Hungduan": [16.8500, 121.0500],
              "Kiangan": [16.7667, 121.0833],
              "Lagawe": [16.8167, 121.1667],
              "Lamut": [16.6500, 121.2333],
              "Mayoyao": [16.9833, 121.1500],
              "Tinoc": [16.5333, 120.9333],
              
              "Tabuk City": [17.4167, 121.4500],
              "Balbalan": [17.4333, 121.1667],
              "Lubuagan": [17.3333, 121.1833],
              "Pasil": [17.2500, 121.2833],
              "Pinukpuk": [17.5833, 121.3667],
              "Rizal": [17.5667, 121.5833],
              "Tanudan": [17.3000, 121.2000],
              "Tinglayan": [17.2833, 121.1167],
              
              "Bontoc": [17.0833, 120.9667],
              "Barlig": [17.0333, 121.1167],
              "Bauko": [16.9833, 120.8667],
              "Besao": [17.0833, 120.8667],
              "Natonin": [17.1833, 121.1333],
              "Paracelis": [17.2333, 121.3000],
              "Sabangan": [17.1167, 120.9000],
              "Sadanga": [17.0167, 120.9000],
              "Sagada": [17.0833, 120.9000],
              "Tadian": [16.9833, 120.9500],
              
              "Laoag City": [18.1987, 120.5937],
              "Batac City": [18.0556, 120.5667],
              "Adams": [18.4833, 120.8167],
              "Bacarra": [18.2500, 120.6167],
              "Badoc": [17.9333, 120.4833],
              "Bangui": [18.5333, 120.7500],
              "Banna": [18.3667, 120.8833],
              "Burgos": [18.5167, 120.6500],
              "Carasi": [18.2167, 120.8500],
              "Currimao": [18.0167, 120.4833],
              "Dingras": [18.1167, 120.6833],
              "Dumalneg": [18.3833, 120.8000],
              "Marcos": [18.1667, 120.7833],
              "Nueva Era": [18.0333, 120.8500],
              "Pagudpud": [18.5667, 120.7833],
              "Paoay": [18.0500, 120.5167],
              "Pasuquin": [18.3333, 120.6167],
              "Piddig": [18.1500, 120.7333],
              "Pinili": [18.1167, 120.5833],
              "San Nicolas": [18.1667, 120.6000],
              "Sarrat": [18.1667, 120.6333],
              "Solsona": [18.0167, 120.7000],
              "Vintar": [18.2333, 120.6667],
              
              "Vigan City": [17.5747, 120.3869],
              "Candon City": [17.1833, 120.4500],
              "Alilem": [17.0333, 120.7167],
              "Banayoyo": [17.3167, 120.4667],
              "Bantay": [17.5833, 120.4000],
              "Burgos": [17.0500, 120.4833],
              "Cabugao": [17.8167, 120.4333],
              "Caoayan": [17.5167, 120.3500],
              "Cervantes": [16.9833, 120.7833],
              "Galimuyod": [17.4000, 120.5000],
              "Gregorio del Pilar": [16.9500, 120.6167],
              "Lidlidda": [17.4667, 120.5667],
              "Magsingal": [17.6667, 120.4167],
              "Nagbukel": [17.6000, 120.6333],
              "Narvacan": [17.4167, 120.4667],
              "Quirino": [16.9667, 120.6500],
              "Salcedo": [17.2500, 120.5333],
              "San Emilio": [17.7000, 120.6833],
              "San Esteban": [17.5333, 120.7000],
              "San Ildefonso": [17.8667, 120.5333],
              "San Juan": [17.7333, 120.4667],
              "San Vicente": [17.3000, 120.5000],
              "Santa": [17.7167, 120.4333],
              "Santa Catalina": [17.6333, 120.5500],
              "Santa Cruz": [17.8500, 120.4667],
              "Santa Lucia": [17.6000, 120.4667],
              "Santa Maria": [17.8833, 120.4167],
              "Santiago": [17.1333, 120.4000],
              "Santo Domingo": [17.6333, 120.4167],
              "Sigay": [17.5667, 120.6833],
              "Sinait": [17.9833, 120.4500],
              "Sugpon": [16.9833, 120.6500],
              "Suyo": [17.0000, 120.5167],
              "Tagudin": [17.2667, 120.4333],
              
              "San Fernando City": [16.6169, 120.3169],
              "Agoo": [16.3167, 120.3667],
              "Aringay": [16.4000, 120.3500],
              "Bacnotan": [16.7167, 120.3667],
              "Bagulin": [16.6167, 120.4500],
              "Balaoan": [16.8167, 120.4000],
              "Bangar": [16.9000, 120.4333],
              "Bauang": [16.5333, 120.3333],
              "Burgos": [16.5333, 120.4667],
              "Caba": [16.4333, 120.3500],
              "Luna": [16.8500, 120.3833],
              "Naguilian": [16.5333, 120.4000],
              "Pugo": [16.2833, 120.4833],
              "Rosario": [16.2167, 120.4833],
              "San Gabriel": [16.6833, 120.4500],
              "San Juan": [16.6500, 120.3500],
              "Santo Tomas": [16.2833, 120.3833],
              "Santol": [16.8333, 120.4667],
              "Sudipen": [16.9000, 120.4667],
              "Tubao": [16.3500, 120.4167],
              
              "Dagupan City": [16.0433, 120.3334],
              "Alaminos City": [16.1556, 119.9822],
              "San Carlos City": [15.9319, 120.3431],
              "Urdaneta City": [15.9761, 120.5711],
              "Agno": [16.1167, 119.8000],
              "Aguilar": [15.8833, 120.2000],
              "Alcala": [15.8333, 120.5333],
              "Anda": [16.2833, 119.9667],
              "Asingan": [16.0000, 120.6667],
              "Balungao": [15.9000, 120.6833],
              "Bani": [16.1833, 119.8667],
              "Basista": [15.8667, 120.4500],
              "Bautista": [15.7833, 120.5333],
              "Bayambang": [15.8167, 120.4500],
              "Binalonan": [16.0500, 120.6000],
              "Binmaley": [16.0333, 120.2667],
              "Bolinao": [16.3833, 119.8833],
              "Bugallon": [15.9500, 120.1833],
              "Burgos": [16.0500, 119.9833],
              "Calasiao": [16.0167, 120.3667],
              "Dasol": [16.0167, 119.8667],
              "Infanta": [15.7500, 119.8833],
              "Labrador": [15.9000, 120.1167],
              "Laoac": [15.9667, 120.5667],
              "Lingayen": [16.0167, 120.2333],
              "Mabini": [15.9167, 119.9667],
              "Malasiqui": [15.9167, 120.4167],
              "Manaoag": [16.0500, 120.4833],
              "Mangaldan": [16.0667, 120.4000],
              "Mangatarem": [15.7833, 120.3000],
              "Mapandan": [16.0333, 120.4667],
              "Natividad": [15.9500, 120.7833],
              "Pozorrubio": [16.1167, 120.5500],
              "Rosales": [15.9000, 120.6333],
              "San Fabian": [16.1167, 120.4000],
              "San Jacinto": [15.9000, 120.3833],
              "San Manuel": [16.0667, 120.6833],
              "San Nicolas": [16.0833, 120.3333],
              "San Quintin": [15.7000, 120.5167],
              "Santa Barbara": [15.8833, 120.4000],
              "Santa Maria": [15.7667, 120.4667],
              "Santo Tomas": [16.1000, 120.3833],
              "Sison": [16.1667, 120.5667],
              "Sual": [16.0667, 120.1000],
              "Tayug": [16.0333, 120.7500],
              "Umingan": [15.9000, 120.8167],
              "Urbiztondo": [16.0333, 120.3167],
              "Villasis": [15.9000, 120.5833],

              "Basco": [20.4500, 121.9667],
              "Itbayat": [20.7333, 121.8333],
              "Ivana": [20.3833, 121.9667],
              "Mahatao": [20.4167, 121.9500],
              "Sabtang": [20.3333, 121.8667],
              "Uyugan": [20.3500, 121.9333],
              
              "Tuguegarao City": [17.6132, 121.7270],
              "Abulug": [18.4500, 121.4500],
              "Alcala": [17.9000, 121.6667],
              "Allacapan": [18.2000, 121.5833],
              "Amulung": [17.8333, 121.7333],
              "Aparri": [18.3667, 121.6333],
              "Baggao": [17.9667, 121.9333],
              "Ballesteros": [18.4000, 121.5167],
              "Buguey": [18.2833, 121.8500],
              "Calayan": [19.2667, 121.4667],
              "Camalaniugan": [18.2667, 121.6667],
              "Claveria": [18.6000, 121.1167],
              "Enrile": [17.5667, 121.7000],
              "Gattaran": [18.0667, 121.6500],
              "Gonzaga": [18.2667, 122.0000],
              "Iguig": [17.7500, 121.7333],
              "Lal-lo": [18.2000, 121.6667],
              "Lasam": [18.0667, 121.5833],
              "Pamplona": [18.4667, 121.2833],
              "Peñablanca": [17.6333, 121.7833],
              "Piat": [17.8167, 121.4833],
              "Rizal": [18.3667, 121.3167],
              "Sanchez-Mira": [18.5167, 121.3500],
              "Santa Ana": [18.4667, 122.1333],
              "Santa Praxedes": [18.5333, 121.0833],
              "Santa Teresita": [18.2833, 121.7167],
              "Santo Niño": [17.9333, 121.8333],
              "Solana": [17.6500, 121.6833],
              "Tuao": [17.7333, 121.4500],
              "Tumauini": [17.2667, 121.8000],
              
              "Ilagan City": [17.1453, 121.8854],
              "Cauayan City": [16.9272, 121.7706],
              "Santiago City": [16.6878, 121.5467],
              "Alicia": [16.7833, 121.6833],
              "Angadanan": [16.7667, 121.7500],
              "Aurora": [16.9833, 121.6333],
              "Benito Soliven": [16.9333, 121.9500],
              "Burgos": [16.7833, 122.0667],
              "Cabagan": [17.4333, 121.7667],
              "Cabatuan": [16.9000, 121.6667],
              "Cordon": [16.6667, 121.4500],
              "Delfin Albano": [17.2000, 121.6000],
              "Dinapigue": [16.5500, 122.3833],
              "Divilacan": [17.3500, 122.3000],
              "Echague": [16.7000, 121.6667],
              "Gamu": [17.0500, 121.8333],
              "Jones": [16.5667, 121.7000],
              "Luna": [16.8667, 121.7667],
              "Maconacon": [17.6667, 122.2333],
              "Mallig": [17.1833, 121.6167],
              "Naguilian": [16.9167, 121.8500],
              "Palanan": [17.0667, 122.4333],
              "Quezon": [16.6167, 121.5667],
              "Quirino": [16.6667, 121.5333],
              "Ramon": [16.7833, 121.5333],
              "Reina Mercedes": [17.0333, 121.8167],
              "Roxas": [16.5500, 121.6167],
              "San Agustin": [16.2833, 121.8667],
              "San Guillermo": [16.4500, 121.9000],
              "San Isidro": [16.7500, 121.7833],
              "San Manuel": [16.9667, 121.6500],
              "San Mariano": [16.9833, 122.0167],
              "San Mateo": [16.8833, 121.6000],
              "San Pablo": [17.4167, 121.9333],
              "Santa Maria": [16.7333, 121.9167],
              "Santo Tomas": [17.2333, 121.8333],
              "Tumauini": [17.2667, 121.8000],
              
              "Bayombong": [16.4833, 121.1500],
              "Aritao": [16.3500, 121.0500],
              "Bagabag": [16.6000, 121.2500],
              "Bambang": [16.3833, 121.1000],
              "Diadi": [16.7167, 121.3667],
              "Dupax del Norte": [16.3167, 121.1167],
              "Dupax del Sur": [16.2667, 121.0833],
              "Kasibu": [16.3167, 121.2833],
              "Kayapa": [16.4667, 120.9833],
              "Quezon": [16.6333, 121.1000],
              "Santa Fe": [16.1667, 121.2167],
              "Solano": [16.5167, 121.1833],
              "Villaverde": [16.5000, 121.0833],
              
              "Cabarroguis": [16.3167, 121.4833],
              "Aglipay": [16.4667, 121.6333],
              "Diffun": [16.6333, 121.4833],
              "Maddela": [16.3667, 121.6833],
              "Nagtipunan": [16.2167, 121.6333],
              "Saguday": [16.5167, 121.5333],
              
              "Baler": [15.7592, 121.5611],
              "Casiguran": [16.2667, 122.1167],
              "Dilasag": [16.3833, 122.2167],
              "Dinalungan": [15.4667, 121.5833],
              "Dipaculao": [15.7333, 121.5500],
              "Dingalan": [15.3667, 121.3833],
              "Maria Aurora": [15.7833, 121.4667],
              "San Luis": [15.7333, 121.5167],
              
              "Balanga City": [14.6767, 120.5364],
              "Abucay": [14.7167, 120.5333],
              "Bagac": [14.6000, 120.4000],
              "Dinalupihan": [14.8833, 120.4667],
              "Hermosa": [14.8333, 120.5167],
              "Limay": [14.5667, 120.6000],
              "Mariveles": [14.4333, 120.4833],
              "Morong": [14.7000, 120.2667],
              "Orani": [14.8000, 120.5333],
              "Orion": [14.6167, 120.5833],
              "Pilar": [14.6667, 120.5667],
              "Samal": [14.7667, 120.5333],
              
              "Malolos City": [14.8433, 120.8114],
              "Meycauayan City": [14.7345, 120.9634],
              "San Jose del Monte City": [14.8139, 121.0453],
              "Angat": [14.9333, 121.0333],
              "Balagtas": [14.8167, 120.8667],
              "Baliuag": [14.9500, 120.8833],
              "Bocaue": [14.8000, 120.9333],
              "Bulakan": [14.7667, 120.8667],
              "Bustos": [14.9500, 120.9167],
              "Calumpit": [14.9167, 120.7667],
              "Doña Remedios Trinidad": [14.9167, 121.0833],
              "Guiguinto": [14.8333, 120.8833],
              "Hagonoy": [14.8333, 120.7333],
              "Marilao": [14.7667, 120.9500],
              "Norzagaray": [14.9167, 121.0500],
              "Obando": [14.7167, 120.9167],
              "Pandi": [14.8667, 120.9500],
              "Paombong": [14.8333, 120.7833],
              "Plaridel": [14.8833, 120.8500],
              "Pulilan": [14.9000, 120.8500],
              "San Ildefonso": [15.0833, 120.9333],
              "San Miguel": [15.1500, 120.9833],
              "San Rafael": [14.9500, 121.0000],
              "Santa Maria": [14.8167, 120.9500],
              
              "Cabanatuan City": [15.4860, 120.9670],
              "Gapan City": [15.3075, 120.9467],
              "Palayan City": [15.5394, 121.0886],
              "San Jose City": [15.7914, 120.9953],
              "Science City of Muñoz": [15.7117, 120.9036],
              "Aliaga": [15.5000, 120.8500],
              "Bongabon": [15.6333, 121.1500],
              "Cabiao": [15.2500, 120.8500],
              "Carranglan": [15.9667, 121.0833],
              "Cuyapo": [15.7833, 120.6667],
              "Gabaldon": [15.3333, 121.3333],
              "General Mamerto Natividad": [15.6000, 121.0500],
              "General Tinio": [15.3500, 121.0500],
              "Guimba": [15.6667, 120.7667],
              "Jaen": [15.3333, 120.9000],
              "Laur": [15.5833, 121.1833],
              "Licab": [15.5667, 120.7333],
              "Llanera": [15.6833, 120.9333],
              "Lupao": [15.8833, 120.9000],
              "Nampicuan": [15.7000, 120.7833],
              "Pantabangan": [15.8167, 121.1333],
              "Peñaranda": [15.3500, 121.0167],
              "Quezon": [15.5667, 120.8167],
              "Rizal": [15.5833, 121.2000],
              "San Antonio": [15.3000, 120.8667],
              "San Isidro": [15.4667, 121.0000],
              "San Leonardo": [15.3667, 120.9500],
              "Santa Rosa": [15.3833, 121.1167],
              "Santo Domingo": [15.5167, 120.8000],
              "Talavera": [15.5833, 120.9167],
              "Talugtug": [15.7667, 120.7333],
              "Zaragoza": [15.4500, 120.8000],
              
              "Angeles City": [15.1450, 120.5887],
              "Mabalacat City": [15.2247, 120.5708],
              "San Fernando City": [15.0285, 120.6897],
              "Apalit": [14.9500, 120.7667],
              "Arayat": [15.1500, 120.7667],
              "Bacolor": [14.9833, 120.6667],
              "Candaba": [15.0833, 120.8333],
              "Floridablanca": [14.9500, 120.5000],
              "Guagua": [14.9667, 120.6333],
              "Lubao": [14.9333, 120.5833],
              "Macabebe": [14.8833, 120.7000],
              "Magalang": [15.2167, 120.6667],
              "Masantol": [14.9000, 120.7167],
              "Mexico": [15.0667, 120.7167],
              "Minalin": [14.9833, 120.6833],
              "Porac": [15.0667, 120.5333],
              "San Luis": [15.0333, 120.7833],
              "San Simon": [14.9833, 120.7833],
              "Santa Ana": [15.0833, 120.7667],
              "Santa Rita": [14.9667, 120.6000],
              "Santo Tomas": [15.0667, 120.6833],
              "Sasmuan": [14.9500, 120.6333],
              
              "Tarlac City": [15.4754, 120.5963],
              "Anao": [15.5833, 120.6000],
              "Bamban": [15.4333, 120.5000],
              "Camiling": [15.6833, 120.4167],
              "Capas": [15.3333, 120.5833],
              "Concepcion": [15.3333, 120.6500],
              "Gerona": [15.5833, 120.6000],
              "La Paz": [15.4500, 120.5333],
              "Mayantoc": [15.6167, 120.3833],
              "Moncada": [15.7333, 120.5667],
              "Paniqui": [15.6667, 120.5833],
              "Pura": [15.6000, 120.5333],
              "Ramos": [15.6500, 120.6167],
              "San Clemente": [15.4333, 120.4500],
              "San Jose": [15.7833, 120.3833],
              "San Manuel": [15.7167, 120.6667],
              "Santa Ignacia": [15.6167, 120.4333],
              "Victoria": [15.5667, 120.6667],
              
              "Olongapo City": [14.8294, 120.2824],
              "Botolan": [15.2833, 120.0167],
              "Cabangan": [15.1667, 120.0333],
              "Candelaria": [15.5333, 119.9167],
              "Castillejos": [14.9167, 120.2000],
              "Iba": [15.3333, 119.9833],
              "Masinloc": [15.5333, 119.9500],
              "Palauig": [15.4333, 119.9000],
              "San Antonio": [14.9333, 120.0833],
              "San Felipe": [15.0333, 120.0667],
              "San Marcelino": [14.8167, 120.1833],
              "San Narciso": [15.0000, 120.0833],
              "Santa Cruz": [15.7667, 119.9167],
              "Subic": [14.8833, 120.2333],
              
              "Cavite City": [14.4791, 120.8964],
              "Bacoor City": [14.4593, 120.9441],
              "Dasmariñas City": [14.3294, 120.9366],
              "General Trias City": [14.3856, 120.8817],
              "Imus City": [14.4297, 120.9367],
              "Tagaytay City": [14.1053, 120.9621],
              "Trece Martires City": [14.2824, 120.8674],
              "Alfonso": [14.1333, 120.8500],
              "Amadeo": [14.1667, 120.9167],
              "Carmona": [14.3167, 121.0500],
              "Gen. Mariano Alvarez": [14.3000, 121.0000],
              "Indang": [14.1833, 120.8833],
              "Kawit": [14.4471, 120.9039],
              "Magallanes": [14.1833, 120.7333],
              "Maragondon": [14.2667, 120.7333],
              "Mendez": [14.1167, 120.9000],
              "Naic": [14.3167, 120.7667],
              "Noveleta": [14.4333, 120.8833],
              "Rosario": [14.4175, 120.8567],
              "Silang": [14.2309, 120.9771],
              "Tanza": [14.4000, 120.8833],
              "Ternate": [14.2833, 120.7167],
              
              "Calamba City": [14.2117, 121.1653],
              "San Pedro City": [14.3585, 121.0167],
              "Biñan City": [14.3382, 121.0852],
              "Santa Rosa City": [14.3123, 121.1114],
              "Cabuyao City": [14.2784, 121.1244],
              "San Pablo City": [14.0683, 121.3256],
              "Alaminos": [14.0667, 121.2500],
              "Bay": [14.1833, 121.2833],
              "Calauan": [14.1500, 121.3167],
              "Cavinti": [14.2500, 121.5000],
              "Famy": [14.4333, 121.4500],
              "Kalayaan": [14.3500, 121.4833],
              "Liliw": [14.1333, 121.4333],
              "Los Baños": [14.1655, 121.2404],
              "Luisiana": [14.1833, 121.5167],
              "Lumban": [14.3000, 121.4667],
              "Mabitac": [14.4333, 121.4167],
              "Magdalena": [14.2000, 121.4333],
              "Majayjay": [14.1500, 121.4667],
              "Nagcarlan": [14.1333, 121.4167],
              "Paete": [14.3667, 121.4833],
              "Pagsanjan": [14.2667, 121.4500],
              "Pakil": [14.3833, 121.4833],
              "Pangil": [14.4000, 121.4667],
              "Pila": [14.2333, 121.3667],
              "Rizal": [14.1167, 121.3833],
              "Santa Cruz": [14.2793, 121.4161],
              "Santa Maria": [14.4667, 121.4333],
              "Siniloan": [14.4167, 121.4500],
              "Victoria": [14.2333, 121.3333],
              
              "Batangas City": [13.7565, 121.0583],
              "Lipa City": [13.9411, 121.1622],
              "Tanauan City": [14.0853, 121.1500],
              "Agoncillo": [13.9333, 120.9333],
              "Alitagtag": [13.8667, 121.0000],
              "Balayan": [13.9333, 120.7333],
              "Balete": [13.8833, 121.0833],
              "Bauan": [13.7833, 121.0083],
              "Calaca": [13.9333, 120.8000],
              "Calatagan": [13.8333, 120.6333],
              "Cuenca": [13.9000, 121.0500],
              "Ibaan": [13.8167, 121.1333],
              "Laurel": [14.0500, 120.9000],
              "Lemery": [13.9172, 120.8928],
              "Lian": [14.0333, 120.6500],
              "Lobo": [13.6667, 121.2500],
              "Mabini": [13.7167, 120.9000],
              "Malvar": [14.0500, 121.1667],
              "Mataas na Kahoy": [13.9667, 121.0667],
              "Nasugbu": [14.0686, 120.6331],
              "Padre Garcia": [13.8833, 121.2167],
              "Rosario": [13.8500, 120.8500],
              "San Jose": [13.8833, 121.0833],
              "San Juan": [13.8167, 121.4000],
              "San Luis": [13.7833, 120.9500],
              "San Nicolas": [13.9167, 121.0333],
              "San Pascual": [13.8000, 121.0333],
              "Santa Teresita": [13.8333, 121.1833],
              "Santo Tomas": [14.1078, 121.1414],
              "Taal": [13.8833, 120.9333],
              "Talisay": [14.1039, 120.9267],
              "Taysan": [13.7667, 121.2167],
              "Tingloy": [13.6667, 120.8833],
              "Tuy": [14.0167, 120.7333],
              
              "Antipolo City": [14.5864, 121.1755],
              "Angono": [14.5271, 121.1533],
              "Baras": [14.5167, 121.2667],
              "Binangonan": [14.4644, 121.1925],
              "Cainta": [14.5781, 121.1222],
              "Cardona": [14.4833, 121.2333],
              "Jalajala": [14.3500, 121.3167],
              "Morong": [14.5167, 121.2333],
              "Pililla": [14.4833, 121.3000],
              "Rodriguez": [14.7167, 121.1167],
              "San Mateo": [14.6972, 121.1222],
              "Tanay": [14.5000, 121.2833],
              "Taytay": [14.5574, 121.1324],
              "Teresa": [14.5600, 121.2094],
              
              "Lucena City": [13.9372, 121.6175],
              "Tayabas City": [14.0268, 121.5926],
              "Agdangan": [13.8833, 121.9167],
              "Alabat": [14.1000, 122.0167],
              "Atimonan": [14.0000, 121.9167],
              "Buenavista": [13.2667, 122.4167],
              "Burdeos": [14.8333, 121.9667],
              "Calauag": [13.9500, 122.2833],
              "Candelaria": [13.9319, 121.4233],
              "Catanauan": [13.5833, 122.3167],
              "Dolores": [14.0167, 121.4167],
              "General Luna": [13.7833, 121.6500],
              "General Nakar": [14.7333, 121.6333],
              "Guinayangan": [13.9000, 122.4500],
              "Gumaca": [13.9167, 122.1000],
              "Infanta": [14.7500, 121.6500],
              "Jomalig": [14.7500, 122.3833],
              "Lopez": [13.8833, 122.2667],
              "Lucban": [14.1167, 121.5667],
              "Macalelon": [13.7500, 121.6667],
              "Mauban": [14.1833, 121.7333],
              "Mulanay": [13.5167, 122.4000],
              "Padre Burgos": [13.9167, 121.8667],
              "Pagbilao": [13.9667, 121.6833],
              "Panukulan": [14.8167, 122.0000],
              "Patnanungan": [14.7167, 122.1833],
              "Perez": [14.1500, 121.9167],
              "Pitogo": [13.7833, 122.0500],
              "Plaridel": [13.6833, 122.3667],
              "Polillo": [14.7167, 121.9500],
              "Quezon": [13.9333, 122.0500],
              "Real": [14.6667, 121.6000],
              "Sampaloc": [14.1167, 121.7667],
              "San Andres": [13.3333, 122.6833],
              "San Antonio": [14.3500, 121.4667],
              "San Francisco": [13.4167, 122.1167],
              "San Narciso": [13.8000, 122.5833],
              "Sariaya": [13.9619, 121.5267],
              "Tagkawayan": [13.9833, 122.5167],
              "Tiaong": [13.9567, 121.3267],
              "Unisan": [13.8167, 122.0500],
              
              "Abra de Ilog": [13.4500, 120.7333],
              "Calintaan": [12.5667, 120.9500],
              "Looc": [13.8167, 120.2333],
              "Lubang": [13.8667, 120.1333],
              "Magsaysay": [12.4000, 121.1667],
              "Mamburao": [13.2242, 120.5917],
              "Paluan": [13.4167, 120.4667],
              "Rizal": [12.5000, 121.3833],
              "Sablayan": [12.8372, 120.7708],
              "San Jose": [12.3500, 121.0667],
              "Santa Cruz": [13.0000, 120.6167],
              
              "Calapan City": [13.4119, 121.1803],
              "Baco": [13.3500, 121.1000],
              "Bansud": [12.8333, 121.4167],
              "Bongabong": [12.7167, 121.3833],
              "Bulalacao": [12.3333, 121.3500],
              "Gloria": [12.9833, 121.4667],
              "Mansalay": [12.5167, 121.4333],
              "Naujan": [13.3167, 121.3000],
              "Pinamalayan": [13.0333, 121.4833],
              "Pola": [13.1500, 121.4500],
              "Puerto Galera": [13.5067, 120.9550],
              "Roxas": [12.6167, 121.5167],
              "San Teodoro": [13.4500, 121.0167],
              "Socorro": [13.0500, 121.4167],
              "Victoria": [13.1667, 121.3500],
              
              "Boac": [13.4500, 121.8333],
              "Buenavista": [13.2667, 121.9667],
              "Gasan": [13.3167, 121.8500],
              "Mogpog": [13.4667, 121.8667],
              "Santa Cruz": [13.4714, 121.9114],
              "Torrijos": [13.3167, 121.9667],
              
              "Alcantara": [12.4667, 122.0333],
              "Banton": [12.9500, 122.0667],
              "Cajidiocan": [12.3667, 122.7000],
              "Calatrava": [12.2833, 122.0667],
              "Concepcion": [12.4167, 122.1167],
              "Corcuera": [12.8167, 122.0833],
              "Ferrol": [12.4833, 122.1167],
              "Looc": [12.2500, 122.4167],
              "Magdiwang": [12.4333, 122.5167],
              "Odiongan": [12.4019, 122.0081],
              "Romblon": [12.5778, 122.2692],
              "San Agustin": [12.3500, 122.4833],
              "San Andres": [12.5500, 122.6833],
              "San Fernando": [12.4000, 122.3667],
              "San Jose": [12.3500, 121.9500],
              "Santa Fe": [12.1833, 122.0167],
              "Santa Maria": [12.5167, 122.0333],
              
              "Puerto Princesa City": [9.7392, 118.7353],
              "Aborlan": [9.4333, 118.5500],
              "Agutaya": [11.1500, 120.9167],
              "Araceli": [10.6667, 119.9667],
              "Balabac": [7.9833, 117.0500],
              "Bataraza": [8.6333, 117.6167],
              "Brooke's Point": [8.7833, 117.8333],
              "Busuanga": [12.1667, 120.0000],
              "Cagayancillo": [9.5833, 121.1833],
              "Coron": [12.0072, 120.2092],
              "Culion": [11.8833, 120.0167],
              "Cuyo": [10.8500, 121.0167],
              "Dumaran": [10.5333, 119.7667],
              "El Nido": [11.1947, 119.4014],
              "Linapacan": [11.4833, 119.9333],
              "Magsaysay": [10.5667, 121.1167],
              "Narra": [9.2833, 118.4167],
              "Quezon": [9.2333, 118.0333],
              "Rizal": [8.7833, 117.5167],
              "Roxas": [10.3333, 119.3333],
              "San Vicente": [10.5167, 119.2667],
              "Sofronio Española": [8.9333, 117.8667],
              "Taytay": [10.8167, 119.5167],
              
              "Legazpi City": [13.1391, 123.7436],
              "Ligao City": [13.2219, 123.5303],
              "Tabaco City": [13.3594, 123.7322],
              "Bacacay": [13.2833, 123.7833],
              "Camalig": [13.1833, 123.6000],
              "Daraga": [13.1667, 123.6833],
              "Guinobatan": [13.1833, 123.5833],
              "Jovellar": [13.3667, 123.6167],
              "Libon": [13.3000, 123.4333],
              "Malilipot": [13.2333, 123.7333],
              "Malinao": [13.2167, 123.6167],
              "Manito": [12.9667, 123.8667],
              "Oas": [13.3167, 123.4833],
              "Pio Duran": [13.0333, 123.4667],
              "Polangui": [13.2833, 123.4833],
              "Rapu-Rapu": [13.1667, 124.1167],
              "Santo Domingo": [13.2833, 123.6500],
              "Tiwi": [13.4500, 123.6833],
              
              "Basud": [14.0667, 122.9833],
              "Capalonga": [14.3167, 122.5000],
              "Daet": [14.1114, 122.9550],
              "Jose Panganiban": [14.3000, 122.6833],
              "Labo": [14.1300, 122.8500],
              "Mercedes": [14.1167, 122.9667],
              "Paracale": [14.2667, 122.7833],
              "San Lorenzo Ruiz": [13.9333, 122.8667],
              "San Vicente": [13.9667, 122.8167],
              "Santa Elena": [14.0000, 122.9000],
              "Talisay": [14.1000, 122.9333],
              "Vinzons": [14.1833, 122.9167],
              
              "Iriga City": [13.4281, 123.4169],
              "Naga City": [13.6192, 123.1814],
              "Baao": [13.4500, 123.3667],
              "Balatan": [13.5667, 123.2667],
              "Bato": [13.3500, 123.3667],
              "Bombon": [13.5000, 123.2333],
              "Buhi": [13.4333, 123.5167],
              "Bula": [13.4667, 123.2833],
              "Cabusao": [13.6833, 123.1167],
              "Calabanga": [13.7167, 123.2333],
              "Camaligan": [13.6333, 123.1667],
              "Canaman": [13.6500, 123.1833],
              "Caramoan": [13.7667, 123.8667],
              "Del Gallego": [13.9167, 122.6000],
              "Gainza": [13.6167, 123.1500],
              "Garchitorena": [13.8667, 123.7000],
              "Goa": [13.6833, 123.4833],
              "Lagonoy": [13.7333, 123.5333],
              "Libmanan": [13.7000, 123.0667],
              "Lupi": [13.8667, 122.8833],
              "Magarao": [13.6500, 123.1667],
              "Milaor": [13.6000, 123.1833],
              "Minalabac": [13.5667, 123.1833],
              "Nabua": [13.4000, 123.3667],
              "Ocampo": [13.5333, 123.3833],
              "Pamplona": [13.5833, 123.0833],
              "Pasacao": [13.5000, 123.0500],
              "Pili": [13.5825, 123.2914],
              "Presentacion": [13.7167, 123.8167],
              "Ragay": [13.8167, 122.7833],
              "Sagñay": [13.6167, 123.5167],
              "San Fernando": [13.5500, 123.1833],
              "San Jose": [13.5833, 123.7000],
              "Sipocot": [13.7667, 122.9667],
              "Siruma": [13.8833, 123.2667],
              "Tigaon": [13.6333, 123.5000],
              "Tinambac": [13.8167, 123.3167],
              
              "Bagamanoc": [13.9167, 124.2833],
              "Baras": [13.7667, 124.2667],
              "Bato": [13.8500, 124.3333],
              "Caramoran": [14.0167, 124.1167],
              "Gigmoto": [13.7833, 124.3833],
              "Pandan": [13.6333, 124.1667],
              "Panganiban": [14.0833, 124.2667],
              "San Andres": [13.6000, 124.0333],
              "San Miguel": [13.6667, 124.0833],
              "Viga": [13.8833, 124.3000],
              "Virac": [13.5833, 124.2333],
              
              "Masbate City": [12.3681, 123.6178],
              "Aroroy": [12.5167, 123.4000],
              "Baleno": [12.3833, 123.9667],
              "Balud": [12.1333, 123.1333],
              "Batuan": [11.8500, 123.6500],
              "Cataingan": [11.9833, 123.9667],
              "Cawayan": [11.9167, 123.7000],
              "Claveria": [12.8833, 123.2500],
              "Dimasalang": [12.1667, 123.4667],
              "Esperanza": [11.6000, 124.0833],
              "Mandaon": [12.2333, 123.2833],
              "Milagros": [12.2667, 123.5167],
              "Mobo": [12.3333, 123.6500],
              "Monreal": [12.5333, 123.5500],
              "Palanas": [12.1500, 123.6667],
              "Pio V. Corpuz": [12.7667, 123.4000],
              "Placer": [11.8333, 123.8333],
              "San Fernando": [12.5000, 123.7333],
              "San Jacinto": [12.9500, 123.2667],
              "San Pascual": [12.4000, 123.4667],
              "Uson": [11.9833, 123.5167],
              
              "Sorsogon City": [12.9742, 124.0078],
              "Barcelona": [13.0333, 123.9333],
              "Bulan": [12.6703, 123.8764],
              "Bulusan": [12.7500, 124.1333],
              "Casiguran": [12.8667, 124.0167],
              "Castilla": [12.9500, 123.9000],
              "Donsol": [12.9167, 123.6000],
              "Gubat": [12.9167, 124.1167],
              "Irosin": [12.7000, 124.0333],
              "Juban": [12.8500, 123.9833],
              "Magallanes": [12.8333, 123.8333],
              "Matnog": [12.5833, 124.0833],
              "Pilar": [12.9167, 123.6667],
              "Prieto Diaz": [13.0333, 124.1833],
              "Santa Magdalena": [12.6333, 124.1000],
              
              "Altavas": [11.5667, 122.5167],
              "Balete": [11.5667, 122.2833],
              "Banga": [11.7500, 122.2667],
              "Batan": [11.6833, 122.0667],
              "Buruanga": [11.8667, 121.9500],
              "Ibajay": [11.8167, 122.1667],
              "Kalibo": [11.7044, 122.3678],
              "Lezo": [11.6167, 122.4667],
              "Libacao": [11.5500, 122.2500],
              "Madalag": [11.5833, 122.2167],
              "Makato": [11.7333, 122.4167],
              "Malay": [11.9333, 121.9333],
              "Malinao": [11.6167, 122.2333],
              "Nabas": [12.0833, 122.0667],
              "New Washington": [11.6167, 122.4500],
              "Numancia": [11.6667, 122.3167],
              "Tangalan": [11.7500, 122.2167],
              
              "Anini-y": [10.4167, 121.9667],
              "Barbaza": [10.7833, 121.9667],
              "Belison": [10.6167, 121.9333],
              "Bugasong": [10.7333, 122.0667],
              "Caluya": [11.9167, 121.4833],
              "Culasi": [11.4333, 122.0333],
              "Hamtic": [10.6667, 121.9833],
              "Laua-an": [10.6167, 122.0167],
              "Libertad": [11.1833, 122.0167],
              "Pandan": [11.7167, 121.9833],
              "Patnongon": [10.9000, 121.9333],
              "San Jose de Buenavista": [10.7667, 121.9500],
              "San Remigio": [11.0333, 121.9333],
              "Sebaste": [10.9667, 122.0833],
              "Sibalom": [10.7833, 122.0000],
              "Tibiao": [11.2833, 122.0333],
              "Tobias Fornier": [10.4500, 122.0333],
              "Valderrama": [10.9667, 122.1500],
              
              "Roxas City": [11.5850, 122.7508],
              "Cuartero": [11.3333, 122.6667],
              "Dao": [11.4000, 122.6833],
              "Dumalag": [11.3000, 122.6167],
              "Dumarao": [11.2667, 122.6833],
              "Ivisan": [11.5167, 122.6833],
              "Jamindan": [11.4000, 122.4667],
              "Ma-ayon": [11.3833, 122.5667],
              "Mambusao": [11.4333, 122.6000],
              "Panay": [11.5500, 122.7833],
              "Panitan": [11.4500, 122.7667],
              "Pilar": [11.6333, 122.4500],
              "Pontevedra": [11.5167, 122.8333],
              "President Roxas": [11.6000, 122.8167],
              "Sapi-an": [11.5333, 122.5500],
              "Sigma": [11.4667, 122.7167],
              "Tapaz": [11.2667, 122.5333],
              
              "Buenavista": [10.5917, 122.5711],
              "Jordan": [10.5917, 122.5711],
              "Nueva Valencia": [10.4389, 122.6156],
              "San Lorenzo": [10.5167, 122.5833],
              "Sibunag": [10.3167, 122.6333],
              
              "Iloilo City": [10.7202, 122.5621],
              "Passi City": [11.1089, 122.6408],
              "Ajuy": [11.1833, 123.0000],
              "Alimodian": [10.8167, 122.4167],
              "Anilao": [10.7667, 122.5500],
              "Badiangan": [11.1667, 122.8167],
              "Balasan": [11.4833, 123.0833],
              "Banate": [11.0333, 122.8500],
              "Barotac Nuevo": [10.9000, 122.7000],
              "Barotac Viejo": [10.8333, 122.8833],
              "Batad": [11.0500, 122.9833],
              "Bingawan": [11.2167, 122.6000],
              "Cabatuan": [10.8667, 122.4833],
              "Calinog": [11.1333, 122.5000],
              "Carles": [11.5667, 123.1500],
              "Concepcion": [11.3000, 123.0833],
              "Dingle": [10.9333, 122.6667],
              "Dueñas": [11.0500, 122.6167],
              "Dumangas": [10.8167, 122.7167],
              "Estancia": [11.4500, 123.1500],
              "Guimbal": [10.6667, 122.5000],
              "Igbaras": [10.7167, 122.3667],
              "Janiuay": [10.9500, 122.5000],
              "Lambunao": [11.0167, 122.4667],
              "Leganes": [10.7833, 122.5833],
              "Lemery": [10.9500, 122.8500],
              "Leon": [10.7833, 122.3833],
              "Maasin": [11.0167, 122.6667],
              "Miagao": [10.6500, 122.2333],
              "Mina": [10.9500, 122.5667],
              "New Lucena": [10.8167, 122.5167],
              "Oton": [10.6972, 122.4769],
              "Pavia": [10.7833, 122.5333],
              "Pototan": [10.9492, 122.6303],
              "San Dionisio": [11.2667, 122.8667],
              "San Enrique": [10.8333, 122.6000],
              "San Joaquin": [10.6000, 122.1167],
              "San Miguel": [10.7667, 122.4667],
              "San Rafael": [11.1667, 122.8333],
              "Santa Barbara": [10.8333, 122.5333],
              "Sara": [11.2500, 123.0167],
              "Tigbauan": [10.7333, 122.3667],
              "Tubungan": [10.7833, 122.2667],
              "Zarraga": [10.8167, 122.6167],
              
              "Bacolod City": [10.6770, 122.9506],
              "Bago City": [10.5378, 122.8358],
              "Cadiz City": [10.9528, 123.2889],
              "Escalante City": [10.8397, 123.5028],
              "Himamaylan City": [10.1000, 122.8667],
              "Kabankalan City": [9.9889, 122.8217],
              "La Carlota City": [10.4214, 122.9214],
              "Sagay City": [10.8961, 123.4206],
              "San Carlos City": [10.4781, 123.4189],
              "Silay City": [10.8006, 123.0003],
              "Sipalay City": [9.7500, 122.4000],
              "Talisay City": [10.7431, 122.9681],
              "Victorias City": [10.9006, 123.0772],
              "Binalbagan": [10.1833, 122.8667],
              "Calatrava": [10.5833, 123.4667],
              "Candoni": [9.8000, 122.6167],
              "Cauayan": [9.9333, 122.7333],
              "Enrique B. Magalona": [10.8500, 123.0167],
              "Hinigaran": [10.2667, 122.8500],
              "Hinoba-an": [9.9833, 122.5667],
              "Ilog": [10.0333, 122.7333],
              "Isabela": [10.2167, 122.9667],
              "La Castellana": [10.3333, 123.0000],
              "Manapla": [10.9500, 123.1167],
              "Moises Padilla": [10.2833, 123.0833],
              "Murcia": [10.6167, 123.1833],
              "Pontevedra": [10.4000, 122.8333],
              "Pulupandan": [10.5167, 122.8000],
              "Salvador Benedicto": [10.7167, 123.1000],
              "San Enrique": [10.3667, 122.8000],
              "Toboso": [10.7167, 123.5333],
              "Valladolid": [10.5000, 123.4167],
              
              "Tagbilaran City": [9.6479, 123.8542],
              "Alburquerque": [9.6167, 123.9667],
              "Alicia": [9.9167, 124.4167],
              "Anda": [9.7333, 124.5667],
              "Antequera": [9.7833, 123.9833],
              "Baclayon": [9.6167, 123.9167],
              "Balilihan": [9.7500, 123.9667],
              "Batuan": [9.8167, 124.1167],
              "Bien Unido": [10.1333, 124.3833],
              "Bilar": [9.7000, 124.0833],
              "Buenavista": [10.0667, 124.2667],
              "Calape": [9.8833, 123.8833],
              "Candijay": [9.8167, 124.5167],
              "Carmen": [9.8667, 124.1833],
              "Catigbian": [9.8000, 124.0167],
              "Clarin": [9.9667, 124.0167],
              "Corella": [9.7333, 123.9333],
              "Cortes": [9.6833, 123.8833],
              "Dagohoy": [9.8500, 124.3500],
              "Danao": [9.9667, 124.2833],
              "Dauis": [9.6167, 123.8500],
              "Dimiao": [9.6167, 124.3000],
              "Duero": [9.6833, 124.4167],
              "Garcia Hernandez": [9.6167, 124.2833],
              "Getafe": [10.1500, 124.1500],
              "Guindulman": [9.7667, 124.4833],
              "Inabanga": [10.0333, 124.0667],
              "Jagna": [9.6500, 124.3667],
              "Lila": [9.5500, 124.0667],
              "Loay": [9.5833, 123.9833],
              "Loboc": [9.6333, 124.0333],
              "Loon": [9.8000, 123.8000],
              "Mabini": [9.8500, 124.5167],
              "Maribojoc": [9.7500, 123.8333],
              "Panglao": [9.5806, 123.7544],
              "Pilar": [9.8333, 123.9833],
              "President Carlos P. Garcia": [9.8500, 124.6833],
              "Sagbayan": [9.9167, 124.1000],
              "San Isidro": [9.9667, 124.4667],
              "San Miguel": [9.6833, 124.1667],
              "Sevilla": [9.7000, 123.9000],
              "Sierra Bullones": [9.7667, 124.2667],
              "Sikatuna": [9.7167, 123.9500],
              "Talibon": [10.1167, 124.3000],
              "Trinidad": [9.9167, 124.3667],
              "Tubigon": [10.0497, 124.0689],
              "Ubay": [10.0667, 124.4667],
              "Valencia": [9.6000, 124.1833],
              
              "Cebu City": [10.3157, 123.8854],
              "Bogo City": [11.0333, 124.0167],
              "Carcar City": [10.1089, 123.6386],
              "Danao City": [10.5197, 124.0267],
              "Lapu-Lapu City": [10.3103, 123.9494],
              "Mandaue City": [10.3237, 123.9227],
              "Naga City": [10.2081, 123.7575],
              "Talisay City": [10.2444, 123.8494],
              "Toledo City": [10.3778, 123.6394],
              "Alcantara": [9.9667, 123.3833],
              "Alcoy": [9.6833, 123.5000],
              "Alegria": [9.7500, 123.3500],
              "Aloguinsan": [10.2167, 123.5500],
              "Argao": [9.8833, 123.6000],
              "Asturias": [10.5667, 123.7167],
              "Badian": [9.8667, 123.4000],
              "Balamban": [10.5000, 123.7167],
              "Bantayan": [11.1667, 123.7167],
              "Barili": [10.1167, 123.5167],
              "Boljoon": [9.6333, 123.4667],
              "Borbon": [10.8333, 124.0333],
              "Carmen": [10.5833, 124.0167],
              "Catmon": [10.7167, 123.9500],
              "Compostela": [10.4500, 124.0167],
              "Consolacion": [10.3667, 123.9500],
              "Cordova": [10.2500, 123.9500],
              "Daanbantayan": [11.2500, 124.0000],
              "Dalaguete": [9.7667, 123.5333],
              "Dumanjug": [10.0500, 123.4500],
              "Ginatilan": [9.6000, 123.3333],
              "Liloan": [10.3833, 123.9667],
              "Madridejos": [11.2667, 123.7333],
              "Malabuyoc": [9.6667, 123.3667],
              "Medellin": [11.1167, 123.9667],
              "Minglanilla": [10.2500, 123.7833],
              "Moalboal": [9.9500, 123.3833],
              "Oslob": [9.5167, 123.4000],
              "Pilar": [10.6167, 124.3333],
              "Pinamungajan": [10.2667, 123.5833],
              "Poro": [10.6167, 124.4167],
              "Ronda": [10.0000, 123.4167],
              "Samboan": [9.5333, 123.3167],
              "San Fernando": [10.1667, 123.7000],
              "San Francisco": [10.6333, 124.5000],
              "San Remigio": [11.0500, 123.9333],
              "Santa Fe": [11.1500, 123.8000],
              "Santander": [9.4833, 123.3833],
              "Sibonga": [10.0000, 123.6167],
              "Sogod": [10.7500, 124.0000],
              "Tabogon": [10.9333, 124.0333],
              "Tabuelan": [10.8167, 123.8167],
              "Tuburan": [10.7333, 123.8333],
              "Tudela": [10.5667, 124.4333],
              
              "Dumaguete City": [9.3068, 123.3054],
              "Bais City": [9.5897, 123.1217],
              "Bayawan City": [9.3653, 122.8064],
              "Canlaon City": [10.3833, 123.2000],
              "Guihulngan City": [10.1167, 123.2667],
              "Tanjay City": [9.5172, 123.1572],
              "Amlan": [9.4333, 123.1833],
              "Ayungon": [9.8500, 123.1333],
              "Bacong": [9.2500, 123.2667],
              "Basay": [9.4167, 122.6333],
              "Bindoy": [9.7000, 123.0833],
              "Dauin": [9.2000, 123.2667],
              "Jimalalud": [9.9833, 123.2000],
              "La Libertad": [9.2833, 123.1667],
              "Mabinay": [9.7333, 122.9167],
              "Manjuyod": [9.6833, 123.1500],
              "Pamplona": [9.5833, 123.0833],
              "San Jose": [9.3500, 122.7000],
              "Santa Catalina": [9.3333, 123.2000],
              "Siaton": [9.0667, 123.0333],
              "Sibulan": [9.3500, 123.2833],
              "Tayasan": [9.7500, 123.1000],
              "Valencia": [9.2833, 123.2333],
              "Vallehermoso": [9.6167, 123.0000],
              "Zamboanguita": [9.1167, 123.2000],
              
              "Enrique Villanueva": [9.2167, 123.6333],
              "Larena": [9.2833, 123.6167],
              "Lazi": [9.1333, 123.5833],
              "Maria": [9.1167, 123.4833],
              "San Juan": [9.1833, 123.5000],
              "Siquijor": [9.2167, 123.5167],
              
              "Almeria": [11.6333, 124.3833],
              "Biliran": [11.4667, 124.4667],
              "Cabucgayan": [11.5333, 124.5167],
              "Caibiran": [11.5667, 124.5833],
              "Culaba": [11.6500, 124.5167],
              "Kawayan": [11.5833, 124.4000],
              "Maripipi": [11.7833, 124.3500],
              "Naval": [11.5603, 124.3953],
              
              "Borongan City": [11.6053, 125.4336],
              "Arteche": [12.3000, 125.4500],
              "Balangiga": [11.1167, 125.3833],
              "Balangkayan": [11.4333, 125.5500],
              "Can-avid": [11.9667, 125.3167],
              "Dolores": [12.2000, 125.4667],
              "General MacArthur": [11.1833, 125.5167],
              "Giporlos": [11.0667, 125.4333],
              "Guiuan": [11.0353, 125.7258],
              "Hernani": [11.3000, 125.6167],
              "Jipapad": [12.2667, 125.3500],
              "Lawaan": [11.0000, 125.5000],
              "Llorente": [11.4667, 125.6500],
              "Maslog": [12.1333, 125.0667],
              "Maydolong": [11.3500, 125.5000],
              "Mercedes": [11.1667, 125.5833],
              "Oras": [12.1333, 125.4500],
              "Quinapondan": [10.9333, 125.5500],
              "Salcedo": [11.1000, 125.4833],
              "San Julian": [11.5500, 125.4333],
              "San Policarpo": [12.1833, 125.5000],
              "Sulat": [11.7833, 125.4833],
              "Taft": [12.3167, 125.3667],
              
              "Tacloban City": [11.2444, 125.0039],
              "Ormoc City": [11.0064, 124.6075],
              "Baybay City": [10.6794, 124.8011],
              "Abuyog": [10.7500, 125.0167],
              "Alangalang": [11.2000, 124.8500],
              "Albuera": [10.9167, 124.6833],
              "Babatngon": [11.4333, 124.8333],
              "Barugo": [11.3167, 124.7333],
              "Bato": [10.3167, 124.7833],
              "Burauen": [10.9833, 124.9000],
              "Calubian": [11.4500, 124.4167],
              "Capoocan": [11.2833, 124.6000],
              "Carigara": [11.3000, 124.6833],
              "Dagami": [11.0667, 124.9000],
              "Dulag": [10.9500, 125.0333],
              "Hilongos": [10.3833, 124.7500],
              "Hindang": [10.4500, 124.8833],
              "Inopacan": [10.5167, 124.7500],
              "Isabel": [10.9333, 124.4333],
              "Jaro": [11.2000, 124.7667],
              "Javier": [10.7667, 124.9667],
              "Julita": [11.0167, 124.9667],
              "Kananga": [11.1833, 124.5167],
              "La Paz": [10.8833, 124.9833],
              "Leyte": [11.3167, 124.4833],
              "MacArthur": [10.8667, 125.0500],
              "Mahaplag": [10.5833, 124.9833],
              "Matag-ob": [11.2833, 124.5500],
              "Matalom": [10.2833, 124.8000],
              "Mayorga": [11.1500, 124.9000],
              "Merida": [11.2667, 124.8500],
              "Palo": [11.1667, 124.9833],
              "Palompon": [11.0500, 124.3833],
              "Pastrana": [11.1667, 124.9500],
              "San Isidro": [11.4167, 124.4667],
              "San Miguel": [11.5500, 124.5000],
              "Santa Fe": [11.1500, 124.9500],
              "Tabango": [11.3167, 124.4167],
              "Tabontabon": [11.0500, 125.1500],
              "Tanauan": [11.1167, 125.0167],
              "Tolosa": [11.2000, 125.0333],
              "Tunga": [11.2333, 124.7500],
              "Villaba": [11.2167, 124.3833],
              
              "Allen": [12.5078, 124.2781],
              "Biri": [12.6667, 124.3667],
              "Bobon": [12.5167, 124.5667],
              "Capul": [12.4167, 124.1667],
              "Catarman": [12.4992, 124.6378],
              "Catubig": [12.3833, 124.8167],
              "Gamay": [12.5167, 125.2000],
              "Laoang": [12.5667, 125.0000],
              "Lapinig": [12.2667, 125.0000],
              "Las Navas": [12.3500, 124.9667],
              "Lavezares": [12.5333, 124.3333],
              "Lope de Vega": [12.3000, 125.1333],
              "Mapanas": [12.4167, 125.0000],
              "Mondragon": [12.5333, 124.8000],
              "Palapag": [12.5500, 125.1167],
              "Pambujan": [12.5667, 124.9333],
              "Rosario": [12.5000, 124.4167],
              "San Antonio": [12.4333, 124.7167],
              "San Isidro": [12.1833, 124.3667],
              "San Jose": [12.3667, 124.8000],
              "San Roque": [12.2833, 124.9500],
              "San Vicente": [12.4000, 125.0333],
              "Silvino Lobos": [12.3167, 124.3500],
              "Victoria": [12.3667, 124.2833],
              
              "Catbalogan City": [11.7750, 124.8881],
              "Calbayog City": [12.0664, 124.6028],
              "Almagro": [11.9000, 124.4000],
              "Basey": [11.2833, 125.0667],
              "Calbiga": [11.5667, 125.0167],
              "Daram": [11.6333, 124.8833],
              "Gandara": [12.0167, 124.8333],
              "Hinabangan": [11.7500, 125.0833],
              "Jiabong": [11.8500, 125.0000],
              "Marabut": [11.1167, 125.1833],
              "Matuguinao": [12.1333, 125.0500],
              "Motiong": [11.7167, 125.1500],
              "Pagsanghan": [11.6667, 125.2667],
              "Paranas": [11.9333, 124.9833],
              "Pinabacdao": [11.5500, 125.1333],
              "San Jorge": [12.0000, 124.7667],
              "San Jose de Buan": [11.9833, 124.7500],
              "San Sebastian": [11.3333, 125.0333],
              "Santa Margarita": [12.0000, 124.7000],
              "Santa Rita": [11.8167, 125.0500],
              "Santo Niño": [11.8500, 124.9000],
              "Tagapul-an": [11.9500, 124.8167],
              "Talalora": [12.1167, 124.7833],
              "Tarangnan": [11.9167, 125.0333],
              "Villareal": [11.5833, 124.9000],
              "Zumarraga": [11.5167, 125.0333],
              
              "Maasin City": [10.1306, 124.8406],
              "Anahawan": [10.1333, 125.2500],
              "Bontoc": [10.3667, 125.0167],
              "Hinunangan": [10.4000, 125.2000],
              "Hinundayan": [10.3167, 125.2500],
              "Libagon": [10.3000, 125.0667],
              "Liloan": [10.2000, 125.1833],
              "Limasawa": [9.9500, 125.0000],
              "Macrohon": [10.0667, 124.9833],
              "Malitbog": [10.1667, 124.8833],
              "Padre Burgos": [10.0167, 125.0667],
              "Pintuyan": [10.1500, 125.2167],
              "Saint Bernard": [10.0167, 125.1333],
              "San Francisco": [9.8833, 125.0167],
              "San Juan": [9.9333, 125.1333],
              "San Ricardo": [10.1833, 125.0833],
              "Silago": [10.5500, 125.1667],
              "Sogod": [10.3844, 125.0089],
              "Tomas Oppus": [10.2333, 125.0167]
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