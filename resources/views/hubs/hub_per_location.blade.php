@extends('layouts.header')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h4 class="card-title mb-0">Hub Per Location</h4>
                  <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#hubModal">
                    <i class="ti-plus btn-icon-prepend"></i>
                    Add New Hub
                  </button>
                </div>
                
                <p class="card-description">
                  <form method='get' onsubmit='show();' enctype="multipart/form-data">
                    <div class="row">
                      <!-- Region Filter -->
                      <div class='col-md-3'>
                        <div class="form-group">
                          <label for="region">Region</label>
                          <select name="region" id="region" class="form-control" onchange="updateTerritories()">
                            <option value="">Select Region</option>
                            @foreach($regions as $reg)
                              <option value="{{ $reg->region }}" {{ $region == $reg->region ? 'selected' : '' }}>
                                {{ $reg->region }}
                              </option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      
                      <!-- Territory Filter -->
                      <div class='col-md-3'>
                        <div class="form-group">
                          <label for="territory">Territory</label>
                          <select name="territory" id="territory" class="form-control" onchange="updateAreas()" {{ !$region ? 'disabled' : '' }}>
                            <option value="">Select Territory</option>
                            @foreach($territories as $terr)
                              <option value="{{ $terr->territory }}" {{ $territory == $terr->territory ? 'selected' : '' }}>
                                {{ $terr->territory }}
                              </option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      
                      <!-- Area Filter -->
                      <div class='col-md-3'>
                        <div class="form-group">
                          <label for="area">Area</label>
                          <select name="area" id="area" class="form-control" {{ !$territory ? 'disabled' : '' }}>
                            <option value="">Select Area</option>
                            @foreach($areas as $ar)
                              <option value="{{ $ar->area }}" {{ $area == $ar->area ? 'selected' : '' }}>
                                {{ $ar->area }}
                              </option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      
                      <!-- Hub Status Filter -->
                      <div class='col-md-2'>
                        <div class="form-group">
                          <label for="hub_status">Hub Status</label>
                          <select name="hub_status" id="hub_status" class="form-control">
                            <option value="">All Status</option>
                            @foreach($hub_statuses as $status)
                              <option value="{{ $status->hub_status }}" {{ $hub_status == $status->hub_status ? 'selected' : '' }}>
                                {{ $status->hub_status }}
                              </option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      
                      <!-- Filter Button -->
                      <div class='col-md-1'>
                        <div class="form-group">
                          <label>&nbsp;</label><br>
                          <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                      </div>
                    </div>
                  </form>
                </p>

                <!-- Success Message -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  {{ session('success') }}
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                @endif

                <!-- Results Count -->
                @if(isset($hubs) && $hubs->count() > 0)
                <div class="mb-3">
                  <span class="ml-2 text-muted">Total: {{ $hubs->count() }} hubs</span>
                </div>
                @endif

                <div class="table-responsive">
                  <table border="1" class="table table-hover table-bordered" id='hub_table'>
                      <thead>
                          <tr>
                              <th>Region</th>
                              <th>Territory</th>
                              <th>Area</th>
                              <th>Hub Name</th>
                              <th>Hub Code</th>
                              <th>Address</th>
                              <th>Hub Status</th>
                              <th>Latitude</th>
                              <th>Longtitude</th>
                              <th>Map Location</th>
                              <th>Actions</th>
                          </tr>
                      </thead>
                      <tbody>
                        @if(isset($hubs) && $hubs->count() > 0)
                          @foreach($hubs as $hub)
                          <tr>
                              <td>{{ $hub->region }}</td>
                              <td>{{ $hub->territory }}</td>
                              <td>{{ $hub->area }}</td>
                              <td>{{ $hub->hub_name }}</td>
                              <td>{{ $hub->hub_code }}</td>
                              <td>{{ $hub->retail_hub_address }}</td>
                              <td>{{ $hub->hub_status }}</td>
                              <td>
                                @if(isset($hub->lat))
                                  {{ number_format($hub->lat, 5) }}
                                @else
                                  <span class="text-muted">N/A</span>
                                @endif
                              </td>

                              <td>
                                @if(isset($hub->long))
                                  {{ number_format($hub->long, 5) }}
                                @else
                                  <span class="text-muted">N/A</span>
                                @endif
                              </td>
                              <td class="text-center">
                                @if($hub->google_map_location_link)
                                  <a href="{{ $hub->google_map_location_link }}" 
                                    target="_blank" 
                                    class="btn btn-sm btn-outline-primary"
                                    title="View on Google Maps">
                                    <i class="fa fa-map-marker"></i> View Map
                                  </a>
                                @else
                                  <span class="text-muted">No Map</span>
                                @endif
                              </td>
                              <td>
                                <div class="btn-group" role="group">
                                  <button data-toggle="modal" data-target="#edit-hub-{{ $hub->id }}"
                                    class="btn btn-sm btn-outline-info" 
                                    title="Edit Hub">
                                    <i class="fa fa-edit"></i> Edit
                                  </button>
                                </div>
                              </td>
                          </tr>
                          @endforeach
                        @else
                          <tr>
                            <td colspan="11" class="text-center">No hubs found</td>
                          </tr>
                        @endif
                      </tbody>
                  </table>
                </div>

              </div>
            </div>
          </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">

<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>

<script>
function show() {
    console.log('Filtering hubs...');
}

function updateTerritories() {
    const region = document.getElementById('region').value;
    const territorySelect = document.getElementById('territory');
    const areaSelect = document.getElementById('area');
    
    territorySelect.innerHTML = '<option value="">Select Territory</option>';
    areaSelect.innerHTML = '<option value="">Select Area</option>';
    areaSelect.disabled = true;
    
    if (region) {
        territorySelect.disabled = false;
        
        fetch(`{{ route('hub-per-location.territories') }}?region=${region}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(territory => {
                    const option = document.createElement('option');
                    option.value = territory.territory;
                    option.textContent = territory.territory;
                    territorySelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching territories:', error);
            });
    } else {
        territorySelect.disabled = true;
    }
}

function updateAreas() {
    const territory = document.getElementById('territory').value;
    const areaSelect = document.getElementById('area');
    
    areaSelect.innerHTML = '<option value="">Select Area</option>';
    
    if (territory) {
        areaSelect.disabled = false;
        
        fetch(`{{ route('hub-per-location.areas') }}?territory=${territory}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(area => {
                    const option = document.createElement('option');
                    option.value = area.area;
                    option.textContent = area.area;
                    areaSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching areas:', error);
            });
    } else {
        areaSelect.disabled = true;
    }
}
</script>

<script>
    function get_min(value)
    {
        document.getElementById("to").min = value;
    }

    $(document).ready(function() 
    {
        new DataTable('.table', 
        {
            paginate:false,
            dom: 'Bfrtip',
            buttons: 
            [
                'copy', 
                'excel'
            ],
            columnDefs: 
            [
                {
                    "defaultContent": "-",
                    "targets": "_all"
                },
                {
                    "orderable": false,
                    "targets": [-1, -2]  // Disable sorting on Map Location and Actions columns
                }
            ],
            order: [] 
        });
    });
</script>

@foreach($hubs as $hub)
@include('hubs.edit_hub')
@endforeach  

@include('hubs.create_hub')

@endsection