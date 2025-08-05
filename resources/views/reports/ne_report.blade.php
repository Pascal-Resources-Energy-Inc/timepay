<!-- ne_report.blade.php -->

@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">
			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Filter</h4>
						<p class="card-description">
						<form method='get' onsubmit='show();' enctype="multipart/form-data">
							<div class=row>
								<!-- <div class='col-md-2'>
									<div class="form-group">
										<label class="text-right">Company</label>
										<select data-placeholder="Select Company" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='company[]' multiple required>
											<option value="">-- Select Company --</option>
											@foreach($companies as $comp)
											<option value="{{$comp->id}}" @if (in_array($comp->id,$company)) selected @endif>{{$comp->company_code}}</option>
											@endforeach
										</select>
									</div>
								</div> -->
								 <div class='col-md-2'>
									<div class="form-group">
										<label class="text-right">Employee</label>
										<select data-placeholder="Select Employee" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='employee[]' multiple>
											<option value="">-- Select Employee --</option>
											@foreach($employees as $emp)
											<option value="{{$emp->id}}" @if(in_array($emp->id,$employee)) selected @endif>{{$emp->employee_code .' - '.$emp->user_info->name}}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class='col-md-2'>
									<div class="form-group">
										<label class="text-right">From</label>
										<input type="date" value='{{$from}}' class="form-control form-control-sm" name="from"
											 onchange='get_min(this.value);' required />
									</div>
								</div>
								<div class='col-md-2'>
									<div class="form-group">
										<label class="text-right">To</label>
										<input type="date" value='{{$to}}' class="form-control form-control-sm" id='to' name="to"
											 required />
									</div>
								</div>
								<div class='col-md-2 mr-2'>
									<div class="form-group">
										<label class="text-right">Status</label>
										<select data-placeholder="Select Status" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='status' required>
											<option value=""></option>
											<option value="ALL" @if ('ALL' == $status) selected  @endif>All</option>
											<option value="Approved" @if ('Approved' == $status) selected @endif>Approved</option>
											<option value="Pending" @if ('Pending' == $status) selected @endif>Pending</option>
											<option value="Rejected" @if ('Rejected' == $status) selected @endif>Rejected</option>
										</select>
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
						</p>
						<div class='row'>
							<div class="col-lg-12 grid-margin stretch-card">
							  <div class="card">
								<div class="card-body">
								  <h4 class="card-title">Number Enrollment Report 
									</h4>
								  <div class="table-responsive">
									<table class="table table-hover table-bordered table-detailed">
									  <thead>
										<tr>
										  <th>Date Filed</th>
										  <th>Employee Name</th>
										  <th>Enrollment Type</th>
										  <th>Employee Number</th>
										  <th>Old Phone Number</th>
										  <th>New Phone Number</th>
										  <th>Network Provider</th>
										</tr>
									  </thead>
									  <tbody> 
										@foreach ($employee_nes as $ne)
										<tr>
										  <td>{{date('d/m/Y', strtotime($ne->applied_date))}}</td>
										  <td>{{$ne->user->name ?? ''}}</td>
										  <td>{{ ucfirst(str_replace('_', ' ', $ne->enrollment_type ?? 'N/A')) }}</td>
										  <td>{{$ne->employee_number}}</td>
										  <td>{{$ne->old_phonenumber ?? 'N/A'}}</td>
										  <td>{{$ne->cellphone_number}}</td>
										  <td>{{$ne->network_provider}}</td>
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