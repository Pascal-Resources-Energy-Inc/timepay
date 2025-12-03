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
								<div class='col-md-2'>
									<div class="form-group">
										<label class="text-right">From</label>
										<input type="date" value='{{$from ?? ''}}' class="form-control form-control-sm" name="from" id="from"
											 onchange='get_min(this.value);' max='{{ date('Y-m-d') }}' required />
									</div>
								</div>
								<div class='col-md-2'>
									<div class="form-group">
										<label class="text-right">To</label>
										<input type="date" value='{{$to ?? ''}}' class="form-control form-control-sm" id='to' name="to"
											 max='{{ date('Y-m-d') }}' required />
									</div>
								</div>
								<div class='col-md-2'>
									<div class="form-group">
										<label class="text-right">Status</label>
										<select data-placeholder="Select Status" class="form-control form-control-sm required" style='width:100%;' name='status'>
											<option value="">All</option>
											<option value="Processing" @if(isset($status) && $status == 'Processing') selected @endif>Processing</option>
											<option value="Claimed" @if(isset($status) && $status == 'Claimed') selected @endif>Claimed</option>
											<option value="Forfeited" @if(isset($status) && $status == 'Forfeited') selected @endif>Forfeited</option>
										</select>
									</div>
								</div>
								<div class='col-md-2'>
									<div class="form-group">
										<label class="invisible">Generate</label>
										<button type="submit" class="form-control form-control-sm btn btn-primary btn-sm">Generate</button>
									</div>
								</div>
								<div class='col-md-2'>
									<div class="form-group">
										<label class="invisible">Export</label>
										<button type="button" onclick="exportToExcel()" class="form-control form-control-sm btn btn-success btn-sm">
											<i class="mdi mdi-file-excel"></i> Export to Excel
										</button>
									</div>
								</div>
							</div>
						</form>
						</p>
						<div class='row'>
							<div class="col-lg-12 grid-margin stretch-card">
							  <div class="card">
								<div class="card-body">
								  <h4 class="card-title">Discounted LPG Refill Report</h4>
								  <div class="table-responsive">
									<table class="table table-hover table-bordered table-detailed">
									  <thead>
										<tr>
										  <th>Order Number</th>
										  <th>Date Ordered</th>
										  <th>Employee Name</th>
										  <th>Employee Number</th>
										  <th>Work Place</th>
										  <th>Total Items</th>
										  <th>Total Amount</th>
										  <th>Payment Method</th>
										  <th>Status</th>
										  <th>Claimed Date</th>
										</tr>
									  </thead>
									  <tbody> 
										@if(isset($purchases) && count($purchases) > 0)
											@foreach ($purchases as $purchase)
											<tr>
											  <td>{{$purchase->order_number}}</td>
											  <td>{{date('m/d/Y', strtotime($purchase->created_at))}}</td>
											  <td>{{$purchase->purchaser_name ?? 'N/A'}}</td>
											  <td>{{$purchase->employee_number ?? 'N/A'}}</td>
											  <td>{{$purchase->employee_work_place ?? 'N/A'}}</td>
											  <td>{{$purchase->total_items}}</td>
											  <td>₱{{number_format($purchase->total_amount, 2)}}</td>
											  <td>{{$purchase->payment_method}}</td>
											  <td>
												@if($purchase->status == 'Processing')
													<span class="badge badge-warning">Processing</span>
												@elseif($purchase->status == 'Claimed')
													<span class="badge badge-success">Claimed</span>
												@elseif($purchase->status == 'Forfeited')
													<span class="badge badge-danger">Forfeited</span>
												@endif
											  </td>
											  <td>{{$purchase->claimed_at ? date('m/d/Y', strtotime($purchase->claimed_at)) : 'N/A'}}</td>
											</tr>
											@endforeach
										@else
											<tr>
												<td colspan="10" class="text-center">No records found. Please select dates and click Generate.</td>
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
  function get_min(fromDate) {
    document.getElementById('to').setAttribute('min', fromDate);
  }

  async function exportToExcel() {
    const from = document.getElementById('from').value;
    const to = document.getElementById('to').value;
    
    if (!from || !to) {
      alert('Please select both From and To dates before exporting');
      return;
    }
    
    show();
    
    try {
      window.location.href = `{{ route('purchase.export') }}?from=${from}&to=${to}`;
      
      setTimeout(function() {
        const loader = document.getElementById("loader");
        if (loader) {
          loader.style.display = "none";
        }
      }, 2000);
      
    } catch (error) {
      console.error('Export error:', error);
      alert('Failed to export. Please try again.');
      const loader = document.getElementById("loader");
      if (loader) {
        loader.style.display = "none";
      }
    }
  }

  $(document).ready(function() {
    new DataTable('.table-detailed', {
      paginate: false,
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