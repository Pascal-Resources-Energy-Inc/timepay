@extends('layouts.header')

@section('content')
<div class="main-panel">
  <div class="content-wrapper">

    <!-- HEADER -->
    <div class="row mb-3">
      <div class="col-md-6">
        <h4 class="pt-3">ID & Uniform Request Details</h4>
      </div>
      <div class="col-md-6 text-right">
        <a href="{{ url('iur') }}" class="btn btn-secondary"><i class="ti-arrow-left"></i>&nbsp;Back</a>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <h5>Request Information</h5>
        <hr>
        <div class="row">
          <div class="col-md-4">
            <strong>Reference No:</strong>
            <p>{{ $iur->iur_reference }}</p>
          </div>

          <div class="col-md-4">
            <strong>Type:</strong>
            <p>{{ $iur->type }}</p>
          </div>

          <div class="col-md-4">
            <strong>Work Location:</strong>
            <p>{{ $iur->work_location }}</p>
          </div>

          <div class="col-md-4">
            <strong>Request For:</strong>
            <p>{{ $iur->request_for }}</p>
          </div>

          <div class="col-md-4">
            <strong>Status:</strong>
            <p>
              @if ($iur->status == 'Pending')
                <span class="badge badge-warning">Pending</span>
              @elseif ($iur->status == 'Approved')
                <span class="badge badge-success">Approved</span>
              @else
                <span class="badge badge-danger">{{ $iur->status }}</span>
              @endif
            </p>
          </div>

          <div class="col-md-12">
            <strong>Details:</strong>
            <p>{{ $iur->details }}</p>
          </div>
        </div>

        <!-- UNIFORM SECTION -->
        @if($iur->request_for == 'Uniform' || $iur->request_for == 'Both')
        <h5 class="mt-4">Uniform Request</h5>
        <hr>

        <div class="row">
          <div class="col-md-4">
            <strong>Issued Before:</strong>
            <p>{{ $iur->issued ?? '-' }}</p>
          </div>

          @if($iur->issued == 'Yes')
          <div class="col-md-4">
            <strong>Issued Remarks:</strong>
            <p>{{ $iur->issued_remarks ?? '-' }}</p>
          </div>
          @endif

          <div class="col-md-4">
            <strong>Reason:</strong>
            <p>{{ $iur->issued_reasons ?? '-' }}</p>
          </div>

          <div class="col-md-4">
            <strong>Size:</strong>
            <p>{{ $iur->size }}</p>
          </div>

          <div class="col-md-8">
            <strong>Notes:</strong>
            <p>{{ $iur->notes ?? '-' }}</p>
          </div>
        </div>
        @endif

        <!-- ID SECTION -->
        @if($iur->request_for == 'ID' || $iur->request_for == 'Both')
        <h5 class="mt-4">ID Request</h5>
        <hr>
        <div class="row">
          <div class="col-md-6">
            <strong>Reason for ID:</strong>
            <p>{{ $iur->id_request ?? '-' }}</p>
          </div>

          <div class="col-md-6">
            <strong>ID Picture:</strong><br>

            @if($iur->id_picture)
              <img src="{{ asset($iur->id_picture) }}" 
                   style="max-width:200px; border:1px solid #ccc; padding:5px;">
              <br><br>
              <a href="{{ asset($iur->id_picture) }}" target="_blank" class="btn btn-sm btn-info">
                View Full Image
              </a>
            @else
              <p>No image uploaded</p>
            @endif
          </div>
        </div>
        <div class="col-md-12 text-right mt-3">
            <a href="{{ url('iur') }}" class="btn btn-secondary">Close</a>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection