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
                <input type="text" class="form-control" 
                       name="lead_generator" value="{{ old('lead_generator') }}" 
                       placeholder="e.g., Other Accounts" required>
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
                "Quezon City": ["Commonwealth", "Batasan Hills", "Fairview", "Novaliches", "Diliman", "Cubao", "Project 6"],
                "Manila": ["Ermita", "Malate", "Intramuros", "Binondo", "Tondo", "Sampaloc", "Sta. Cruz"],
                "Makati": ["Poblacion", "Bel-Air", "San Lorenzo", "Urdaneta", "Guadalupe", "Rockwell"],
                "Pasig": ["Kapitolyo", "Ugong", "Ortigas", "Rosario", "Santolan", "Malinao"],
                "Taguig": ["Bonifacio Global City", "Western Bicutan", "Fort Bonifacio", "Pinagsama", "Signal Village"],
                "Mandaluyong": ["Poblacion", "Addition Hills", "Plainview", "Highway Hills"],
                "Parañaque": ["BF Homes", "Baclaran", "Moonwalk", "San Antonio"],
                "Las Piñas": ["BF International", "Talon", "Pamplona"],
                "Muntinlupa": ["Alabang", "Putatan", "Poblacion", "Ayala Alabang"]
            }
        },
        "Calabarzon": {
            "Laguna": {
                "Santa Rosa": ["Poblacion", "Balibago", "Tagapo", "Labas", "Market Area", "Macabling"],
                "Calamba": ["Poblacion", "Parian", "Real", "Canlubang", "Majada"],
                "Biñan": ["Poblacion", "Canlalay", "San Antonio", "Santo Domingo"],
                "San Pedro": ["Poblacion", "Landayan", "San Vicente", "Nueva"]
            },
            "Cavite": {
                "Bacoor": ["Poblacion", "Molino", "Talaba", "Zapote", "Queens Row"],
                "Imus": ["Poblacion", "Anabu", "Tanzang Luma", "Bucandala", "Bayan Luma"],
                "Dasmariñas": ["Poblacion", "Salitran", "San Agustin", "Paliparan"],
                "General Trias": ["Poblacion", "San Francisco", "Pasong Kawayan"]
            },
            "Rizal": {
                "Antipolo": ["Poblacion", "San Roque", "Mambugan", "Cupang"],
                "Cainta": ["Poblacion", "San Isidro", "San Juan", "Santo Domingo"],
                "Taytay": ["Poblacion", "San Juan", "Dolores", "Muzon"]
            },
            "Batangas": {
                "Batangas City": ["Poblacion", "Alangilan", "Kumintang Ibaba", "Pallocan"],
                "Lipa": ["Poblacion", "Marawoy", "Tambo", "Tibig"]
            }
        },
        "Central Luzon": {
            "Pampanga": {
                "Angeles City": ["Poblacion", "Balibago", "Anunas", "Cutcut"],
                "San Fernando": ["Poblacion", "Dolores", "San Jose", "Sindalan"]
            },
            "Bulacan": {
                "Malolos": ["Poblacion", "Atlag", "Barihan", "Dakila"],
                "Meycauayan": ["Poblacion", "Malhacan", "Saluysoy", "Calvario"]
            }
        },
        "Visayas": {
            "Cebu": {
                "Cebu City": ["Lahug", "Capitol Site", "Mabolo", "Banilad", "Talamban", "Guadalupe"],
                "Mandaue": ["Centro", "Banilad", "Casuntingan", "Looc", "Tingub"],
                "Lapu-Lapu": ["Poblacion", "Mactan", "Pusok", "Basak"]
            }
        },
        "Mindanao": {
            "Davao del Sur": {
                "Digos": ["Poblacion", "Zone 1", "Zone 2", "Tres de Mayo"],
                "Davao City": ["Poblacion", "Buhangin", "Toril", "Calinan", "Tugbok", "Matina", "Talomo", "Agdao", "Panacan", "Lanang", "Ma-a", "Catalunan Grande"]
            },
            "Davao Occidental": {
                "Malita": ["Poblacion", "Little Baguio", "Tingolo"],
                "Don Marcelino": ["Poblacion", "Kiobog", "Kinanga"]
            },
            "Davao Oriental": {
                "Mati": ["Poblacion", "Central", "Dahican", "Sainz"],
                "Baganga": ["Poblacion", "Salingcomot", "Lambajon"]
            }
        },
        "Region XII - Soccsksargen": {
            "Cotabato (North Cotabato)": {
                "Kidapawan": ["Poblacion", "Amas", "Perez"],
                "Midsayap": ["Poblacion", "Damatulan", "Bual"]
            },
            "Sarangani": {
                "Alabel": ["Poblacion", "Ladol", "Spring"],
                "Glan": ["Poblacion", "Gumasa", "Big Margus"]
            },
            "South Cotabato": {
                "General Santos": ["Poblacion", "City Heights", "Dadiangas North", "Calumpang", "Lagao", "Bula"],
                "Koronadal": ["Poblacion", "Zone 1", "Zone 2", "Carpenter Hill"],
                "Polomolok": ["Poblacion", "Koronadal Proper", "Bentung"]
            },
            "Sultan Kudarat": {
                "Isulan": ["Poblacion", "Kalawag 1", "Bambad"],
                "Tacurong": ["Poblacion", "Baras", "New Isabela"]
            }
        },
        "Region XIII - Caraga": {
            "Agusan del Norte": {
                "Butuan": ["Poblacion", "Libertad", "Goldtown", "Banza", "Agao", "Anticala"],
                "Cabadbaran": ["Poblacion", "Sanghan", "Soriano"]
            },
            "Agusan del Sur": {
                "Bayugan": ["Poblacion", "Taglatawan", "Anahaw"],
                "Prosperidad": ["Poblacion", "La Paz", "San Pedro"]
            },
            "Surigao del Norte": {
                "Surigao City": ["Poblacion", "Washington", "Taft", "Luna", "Mabua"],
                "Siargao (Del Carmen)": ["Poblacion", "General Luna", "Dapa"]
            },
            "Surigao del Sur": {
                "Tandag": ["Poblacion", "Bongtud", "Awasian"],
                "Bislig": ["Poblacion", "Mangagoy", "San Vicente"]
            },
            "Dinagat Islands": {
                "San Jose": ["Poblacion", "Aurelio", "Santa Cruz"]
            }
        },
        "BARMM - Bangsamoro Autonomous Region": {
            "Basilan": {
                "Isabela City": ["Poblacion", "Sunrise Village", "Cabunbata"],
                "Lamitan": ["Poblacion", "Limo-ok", "Sangkahan"]
            },
            "Lanao del Sur": {
                "Marawi": ["Poblacion", "East Basak", "Banggolo"],
                "Malabang": ["Poblacion", "Pindolonan", "Kumalarang"]
            },
            "Maguindanao": {
                "Cotabato City": ["Poblacion", "Rosary Heights", "Bagua"],
                "Sultan Kudarat": ["Poblacion", "Midtungok", "Katenong"]
            },
            "Sulu": {
                "Jolo": ["Poblacion", "Chinese Pier", "Walled City"],
                "Parang": ["Poblacion", "Kawasan", "Kaunayan"]
            },
            "Tawi-Tawi": {
                "Bongao": ["Poblacion", "Karungdong", "Laminusa"],
                "Languyan": ["Poblacion", "Bakong", "Matatal"]
            }
        },
        "CAR - Cordillera Administrative Region": {
            "Abra": {
                "Bangued": ["Poblacion", "Zone 1", "Calaba"],
                "Dolores": ["Poblacion", "Talogtog", "Isit"]
            },
            "Apayao": {
                "Kabugao": ["Poblacion", "Malibang", "Dagara"],
                "Luna": ["Poblacion", "San Jose"]
            },
            "Benguet": {
                "Baguio": ["Poblacion", "Session Road", "Burnham", "Camp Allen", "Lower Rock Quarry", "Upper Bonifacio", "Malcolm Square"],
                "La Trinidad": ["Poblacion", "Wangal", "Balili", "Pico"],
                "Itogon": ["Poblacion", "Tinongdan", "Ucab"],
                "Tuba": ["Poblacion", "Camp One", "Twin Peaks"]
            },
            "Ifugao": {
                "Lagawe": ["Poblacion", "Burnay", "Olilicon"],
                "Banaue": ["Poblacion", "Viewpoint", "Tam-an"]
            },
            "Kalinga": {
                "Tabuk": ["Poblacion", "Bulanao", "Dagupan"],
                "Lubuagan": ["Poblacion", "Lower Uma", "Tanglag"]
            },
            "Mountain Province": {
                "Bontoc": ["Poblacion", "Caluttit", "Mainit"],
                "Sagada": ["Poblacion", "Demang", "Patay"]
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