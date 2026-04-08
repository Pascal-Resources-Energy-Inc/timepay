<!-- ne_report.blade.php -->

@extends('layouts.header')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">
			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Filter</h4>
						<form method='get' onsubmit='show();' enctype="multipart/form-data">
							<div class=row>
								{{-- <div class='col-md-2'>
									<div class="form-group">
										<label class="text-right">Employee</label>
										<select data-placeholder="Select Employee" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='employee[]' multiple>
											<option value="">-- Select Employee --</option>
											@foreach($employees as $emp)
											<option value="{{$emp->id}}" @if(in_array($emp->id,$employee)) selected @endif>{{$emp->employee->employee_code .' - '.$emp->employee->first_name . ' ' . $emp->employee->last_name}}</option>
											@endforeach
										</select>
									</div>
								</div> --}}
								<div class='col-md-3'>
									<div class="form-group">
										<label class="text-right">From</label>
										<input type="date" value='{{$from}}' class="form-control form-control-sm" name="from"
											 onchange='get_min(this.value);' required />
									</div>
								</div>
								<div class='col-md-3'>
									<div class="form-group">
										<label class="text-right">To</label>
										<input type="date" value='{{$to}}' class="form-control form-control-sm" id='to' name="to"
											 required />
									</div>
								</div>
								<div class='col-md-2'>
									<div class="form-group">
										<label class="invisible">Generate</label>
										<button type="submit" class="form-control form-control-sm btn btn-primary btn-sm">Generate</button>
									</div>
								</div>
							</div>
							
						</form>
						
						<div class='row'>
							<div class="col-lg-12 grid-margin stretch-card">
							    <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Conformity to Policies Informed Consent Report</h4>
                                        <div class="table-responsive">
                                            <table class="table table-hover table-bordered table-detailed">
                                                <thead>
                                                    <tr>
                                                        <th>Date Signed</th>
                                                        <th>Employee Name</th>
                                                        <th>Drug and Alcohol Abuse</th>
                                                        <th>Attendance and Timekeeping Policy</th>
                                                        <th>Code of Conduct</th>
                                                    </tr>
                                                </thead>
                                                <tbody> 
                                                    @foreach ($employees as $consent)
                                                    <tr>
                                                        <td>{{ $consent->signed_date ? date('d/m/Y', strtotime($consent->signed_date)) : '-' }}</td>
                                                        <td>
                                                            {{ optional($consent->employee)->first_name }} 
                                                            {{ optional($consent->employee)->last_name }}
                                                        </td>
                                                        <td>
                                                            <span class="badge 
                                                                {{ Str::contains($consent->dabp, "doesn't agree") ? 'badge-danger' : 'badge-success' }}">
                                                                {{ $consent->dabp ?? '-' }}
                                                            </span>
                                                            @if($consent->dabp_attachment)
                                                                <a href="{{ asset('storage/' . $consent->dabp_attachment) }}" target="_blank" class="ml-2">View Attachment</a>  
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge 
                                                                {{ Str::contains($consent->atkp, "doesn't agree") ? 'badge-danger' : 'badge-success' }}">
                                                                {{ $consent->atkp ?? '-' }}
                                                            </span>
                                                            @if($consent->atkp_attachment)
                                                                <a href="{{ asset('storage/' . $consent->atkp_attachment) }}" target="_blank" class="ml-2">View Attachment</a>  
                                                            @endif

                                                        </td>
                                                        <td>
                                                            <span class="badge 
                                                                {{ Str::contains($consent->coc, "doesn't agree") ? 'badge-danger' : 'badge-success' }}">
                                                                {{ $consent->coc ?? '-' }}
                                                                @if($consent->coc_attachment)
                                                                    <a href="{{ asset('storage/' . $consent->coc_attachment) }}" target="_blank" class="ml-2">View Attachment</a>      
                                                                @endif
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
							    </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
<!-- DataTables CSS and JS includes -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">

<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>

<style>
    .badge-success {
        background-color: #28a745 !important;
        color: #fff;
    }

    .badge-danger {
        background-color: #dc3545 !important;
        color: #fff;
    }
</style>

<script>
  $(document).ready(function() {
    new DataTable('.table-detailed', {
      paginate:false,
      dom: 'Bfrtip',
      buttons: [
          'copy', 'excel'
      ],
      columnDefs: [{
        "defaultContent": "-",
        "targets": "_all"
      }],
      order: [] 
    });
  });
</script>

@endsection