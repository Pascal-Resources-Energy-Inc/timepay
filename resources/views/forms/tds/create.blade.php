@extends('layouts.header')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                    <h4 class="card-title">Register New Dealer</h4>
                        <hr>
                        <form action="{{ route('tds.store') }}" method="POST" id="dealerForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="latitude" id="hidden_latitude">
                            <input type="hidden" name="longitude" id="hidden_longitude">
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
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <div class='row'>
    <div class="col-lg-12 ">
        <div class="card">
            <div class="card-header">
                <h4>Register New Sale</h4>
            </div>
            <form action="{{ route('tds.store') }}" method="POST">
                @csrf

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label>Customer Name</label>
                            <input type="text" name="customer_name" class="form-control" required>
                        </div>


                        <div class="col-md-6 mb-3">
                            <label>Quantity</label>
                            <input type="number" name="quantity" class="form-control" required>
                        </div>

                    </div>

                    
                    <div class="mt-4">
                        <h5>Location</h5>
                        <div id="location_map" style="height:400px;"></div>

                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">
                    </div>

                </div>

                <div class="card-footer text-right">
                    <button class="btn btn-success">Save Sale</button>
                </div>

            </form>
        </div>
    </div>
</div> --}}

@endsection
