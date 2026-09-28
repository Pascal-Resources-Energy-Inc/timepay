@extends('layouts.header')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Planning</h4>
                        <p class="card-description">
                            <!-- Import Button -->
                            <button type="button" class="btn btn-outline-info btn-icon-text" data-toggle="modal" data-target="#importModal">
                                <i class="ti-plus btn-icon-prepend"></i>
                                Import Excel
                            </button>
                        </p>
                        
                        <form method='get' onsubmit='show();' enctype="multipart/form-data">
                            <div class=row>
                                <div class='col-md-2'>
                                    <div class="form-group">
                                        <label class="text-right">From</label>
                                        <input type="date" value='{{ request("from", date("Y-m-d")) }}' class="form-control form-control-sm" name="from"
                                            max='{{ date("Y-m-d") }}' onchange='get_min(this.value);' required />
                                    </div>
                                </div>
                                <div class='col-md-2'>
                                    <div class="form-group">
                                        <label class="text-right">To</label>
                                        <input type="date" value='{{ request("to", date("Y-m-d")) }}' class="form-control form-control-sm" id='to' name="to" required />
                                    </div>
                                </div>
                                <div class='col-md-2 mr-2'>
                                    <div class="form-group">
                                        <label class="text-right">Status</label>
                                        <select data-placeholder="Select Status" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='status' required>
                                            <option value="">-- Select Status --</option>
                                            <option value="Approved" {{ request("status") == "Approved" ? "selected" : "" }}>Approved</option>
                                            <option value="Pending" {{ request("status") == "Pending" ? "selected" : "" }}>Pending</option>
                                            <option value="Cancelled" {{ request("status") == "Cancelled" ? "selected" : "" }}>Cancelled</option>
                                            <option value="Declined" {{ request("status") == "Declined" ? "selected" : "" }}>Declined</option>
                                        </select>
                                    </div>
                                </div>
                                <div class='col-md-2'>
                                    <div class="form-group">
                                        <label class="invisible">Filter</label>
                                        <button type="submit" class="form-control form-control-sm btn btn-primary btn-sm">Filter</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered tablewithSearch">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Name</th>
                                        <th>Destination</th>
                                        <th>Est. Time In</th>
                                        <th>Est. Time out</th>
                                        <th>Activity</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($plannings ?? [] as $planning)
                                    <tr>
                                        <td>{{ date('m/d/Y', strtotime($planning->date)) }}</td>
                                        <td>{{ $planning->employee->first_name ?? '' }} {{ $planning->employee->last_name ?? '' }}</td>
                                        <td>{{ $planning->destination }}</td>
                                        <td>{{ $planning->est_timein }}</td>
                                        <td>{{ $planning->est_timeout }}</td>
                                        <td>{{ $planning->activity }}</td>
                                        <td></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No records found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Planning Excel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="importForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="excel_file">Select Excel File</label>
                        <input type="file" class="form-control" id="excel_file" name="excel_file" accept=".xlsx,.xls,.csv" required>
                        <small class="form-text text-muted">
                            Excel should have 3 columns: Date, Destination, Activity
                        </small>
                    </div>
                    
                    <div class="alert alert-info">
                        <strong>Note:</strong>
                        <ul class="mb-0">
                            <li>Excel must have headers: <strong>Date</strong>, <strong>Destination</strong>, <strong>Activity</strong></li>
                            <li>Employee name and schedule will be automatically fetched</li>
                            <li>Date format: MM/DD/YYYY or YYYY-MM-DD</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('obScript')
<script>
$(document).ready(function() {
    $('#importForm').on('submit', function(e) {
        e.preventDefault();
        
        const loader = document.getElementById("loader");
        if (loader) {
            loader.style.display = "block";
        }
        
        let formData = new FormData(this);
        
        $.ajax({
            url: "{{ route('planning.import') }}",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (loader) {
                    loader.style.display = "none";
                }
                
                $('#importModal').modal('hide');
                
                Swal.fire({
                    title: "Success!",
                    text: response.message + " (" + response.imported + " rows imported)",
                    icon: "success"
                }).then(function() {
                    location.reload();
                });
            },
            error: function(xhr) {
                if (loader) {
                    loader.style.display = "none";
                }
                
                let errorMessage = "Failed to import file. Please try again.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    title: "Error!",
                    text: errorMessage,
                    icon: "error"
                });
            }
        });
    });
});

function cancelPlanning(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "You want to cancel this Planning?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: 'Yes, cancel it!',
        cancelButtonText: 'No, keep it',
        dangerMode: true,
    }).then((result) => { 
        if (result.isConfirmed) {
            const loader = document.getElementById("loader");
            if (loader) {
                loader.style.display = "block";
            }
            
            $.ajax({
                url: "{{ url('disable-planning') }}/" + id,
                method: "GET",
                data: {
                    id: id
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(data) {
                    if (loader) {
                        loader.style.display = "none";
                    }
                    
                    Swal.fire({
                        title: "Cancelled!",
                        text: "Planning has been cancelled!",
                        icon: "success"
                    }).then(function() {
                        location.reload();
                    });
                },
                error: function(xhr, status, error) {
                    if (loader) {
                        loader.style.display = "none";
                    }
                    
                    Swal.fire({
                        title: "Error!",
                        text: "Failed to cancel Planning. Please try again.",
                        icon: "error"
                    });
                }
            });
        } else {
            Swal.fire({
                text: "Planning cancellation was stopped.",
                icon: "info"
            });
        }
    });
}

// Date validation
document.addEventListener('DOMContentLoaded', function() {
    const fromDate = document.querySelector('input[name="from"]');
    const toDate = document.querySelector('input[name="to"]');
    
    if (fromDate && toDate) {
        fromDate.addEventListener('change', function() {
            toDate.min = this.value;
        });
    }
});

function get_min(value) {
    document.getElementById('to').min = value;
}
</script>
@endsection