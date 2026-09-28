@extends('layouts.header')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class='row'>
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Filter</h4>
                            <p class="card-description">
                                <form method='get' onsubmit='show();' enctype="multipart/form-data">
                                    <div class=row>
                                        {{-- <div class='col-md-2'>
                                            <div class="form-group">
                                                <label class="text-right">Company</label>
                                                <select data-placeholder="Select Company" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='company[]' multiple required>
                                                    <option value="">-- Select Employee --</option>
                                                    @foreach($companies as $comp)
                                                    <option value="{{$comp->id}}" @if (in_array($comp->id,$company)) selected @endif>{{$comp->company_code}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div> --}}
                                        <div class='col-md-2'>
                                            <div class="form-group">
                                                <label class="text-right">From</label>
                                                <input type="date" value='{{$from}}' class="form-control" name="from" max='{{date('Y-m-d')}}' onchange='get_min(this.value);' required />
                                            </div>
                                        </div>
                                        <div class='col-md-2'>
                                            <div class="form-group">
                                                <label class="text-right">To</label>
                                                <input type="date" value='{{$to}}' class="form-control" name="to" id='to' max='{{date('Y-m-d')}}' required />
                                                
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
                                                    <option value="Cancelled" @if ('Cancelled' == $status) selected @endif>Cancelled</option>
                                                    <option value="Declined" @if ('Declined' == $status) selected @endif>Declined</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class='col-md-2 mt-3'>
                                            <button type="submit" class="form-control form-control-sm btn btn-primary mb-2 btn-sm mt-3">Generate</button>
                                        </div>
                                    </div>
                                </form>
                            </p>
                            <h4 class="card-title">Monetized Transportation Allowance Report
                                {{-- <a href="/ob-report-export?company={{$company}}&from={{$from}}&to={{$to}}" title="Export" class="btn btn-outline-primary btn-icon-text btn-sm text-center"><i class="ti-arrow-down btn-icon-prepend"></i></a> --}}
                            </h4>

                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-detailed" id="table-mta-report">
                                    <thead>
                                        <tr>
                                            <th>MTA Reference</th>
                                            <th>Employee Number</th>
                                            <th>Employee Details</th>
                                            <th>Transaction Date</th>
                                            <th>Transaction Details</th>
                                            <th>Work Location</th>
                                            <th>Liters Loaded</th>
                                            <th>Price per Liter</th>
                                            <th>MTA Amount</th>
                                            <th>Attachment</th>
                                            <th>Status</th>
                                            <th>Bank</th>
                                            <th>Account Number</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($employee_mtas as $mta)
                                            <tr>
                                                <td>{{ $mta->mta_reference }}</td>
                                                <td>{{ $mta->user->employee->employee_number }}</td>
                                                <td>
                                                    <strong>{{ $mta->user->name }}</strong> <br>
                                                    <small>Position:&nbsp;{{ $mta->user->employee->position }}</small> <br>
                                                    <small>Location:&nbsp;{{ $mta->user->employee->location }}</small> <br>
                                                    <small>Department:&nbsp;{{ $mta->user->employee->department ? $mta->user->employee->department->name : "" }}</small>
                                                </td>
                                                <td>{{ date('M. d, Y', strtotime($mta->mta_date)) }}</td>
                                                <td>{{ $mta->notes }}</td>
                                                <td>{{ $mta->work_location }}</td>
                                                <td>{{ $mta->liters_loaded }} ltr(s)</td>
                                                <td>{{ number_format($mta->petron_price, 2) }}</td>
                                                <td>{{ number_format($mta->mta_amount, 2) }}</td>
                                                <td>
                                                    @if($mta->attachment)
                                                        <a href="{{url($mta->attachment)}}" target='_blank' class="text-start"><button type="button" class="btn btn-outline-info btn-sm ">View Attachment</button></a>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($mta->status == 'Pending')
                                                        <label class="badge badge-warning">{{ $mta->status }}</label>
                                                    @elseif($mta->status == 'Approved')
                                                        <i class="ti ti-user mr-1"></i>&nbsp;{{ $mta->approverMta->user->name ?? 'N/A' }}<br><br><label class="badge badge-success" title="{{$mta->approval_remarks}}">{{ $mta->status }}</label>
                                                    @elseif($mta->status == 'Declined' || $mta->status == 'Cancelled')
                                                        <label class="badge badge-danger" title="{{$mta->approval_remarks}}">{{ $mta->status }}</label>
                                                    @endif  
                                                </td>
                                                <td>{{ $mta->user->employee->bank_name ?? '-' }}</td>
                                                <td>{{ $mta->user->employee->bank_account_number ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                        <th colspan="8" style="text-align:right;">Total MTA Amount:</th>
                                        <th id="total_mta_amount">0.00</th>
                                        <th colspan="4"></th>
                                        </tr>
                                    </tfoot>
                                </table>
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

            function calculateTotalMTA() {
            let total = 0;

            $('#table-mta-report tbody tr').each(function () {
                let amountText = $(this).find('td:nth-child(9)').text().replace(/,/g, '');
                let amount = parseFloat(amountText) || 0;
                total += amount;
            });

            $('#total_mta_amount').text(total.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));
            }

            $(document).ready(function () {
                calculateTotalMTA();
            });
        });
    </script>
@endsection

@section('footer')
    <script src="{{ asset('body_css/vendors/inputmask/jquery.inputmask.bundle.js') }}"></script>
    <script src="{{ asset('body_css/vendors/inputmask/jquery.inputmask.bundle.js') }}"></script>
    <script src="{{ asset('body_css/js/inputmask.js') }}"></script>
@endsection
