@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">
			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Discount LPG Refill Reports</h4>
						<p class="card-description">
						<form method='get' id="exportForm" onsubmit='return false;' enctype="multipart/form-data">
							<div class=row>
								<div class='col-md-4'>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label text-right">From</label>
										<div class="col-sm-8">
											<input type="date" value='' class="form-control form-control-sm" 
												name="from" id="from" max='{{ date('Y-m-d') }}' 
												onchange='get_min(this.value);' required />
										</div>
									</div>
								</div>
								<div class='col-md-4'>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label text-right">To</label>
										<div class="col-sm-8">
											<input type="date" value='' class="form-control form-control-sm" 
												id='to' name="to" max='{{ date('Y-m-d') }}' required />
										</div>
									</div>
								</div>
								<div class='col-md-4'>
									<button type="button" onclick="exportToExcel()" 
										class="form-control form-control-sm btn btn-success mb-2 btn-sm">
										<i class="mdi mdi-file-excel"></i> Export to Excel
									</button>
								</div>
							</div>
						</form>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>

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
			const response = await fetch(`{{ route('purchase.export') }}?from=${from}&to=${to}`);
			
			if (!response.ok) {
				throw new Error('Export failed');
			}
			
			const blob = await response.blob();
			
			const url = window.URL.createObjectURL(blob);
			const a = document.createElement('a');
			a.style.display = 'none';
			a.href = url;
			a.download = `LPG_Refill_Report_${from}_to_${to}.xlsx`;
			
			document.body.appendChild(a);
			a.click();
			
			window.URL.revokeObjectURL(url);
			document.body.removeChild(a);
			
		} catch (error) {
			console.error('Export error:', error);
			alert('Failed to export. Please try again.');
		} finally {
			const loader = document.getElementById("loader");
			if (loader) {
				loader.style.display = "none";
			}
		}
	}
</script>
@endsection