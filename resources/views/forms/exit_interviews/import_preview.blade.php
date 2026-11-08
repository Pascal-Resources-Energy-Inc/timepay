@extends('layouts.header')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
      <div>
        <h4 class="mb-1 font-weight-bold">Legacy Exit Interview Import</h4>
        <p class="mb-0 text-muted">Review the workbook results before saving valid records.</p>
      </div>
      <form method="post" action="{{ route('exit-interviews.import.cancel') }}">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-secondary">Cancel</button>
      </form>
    </div>

    <div class="row mb-4">
      <div class="col-sm-6 col-lg-3 mb-3">
        <div class="card border h-100">
          <div class="card-body">
            <small class="text-muted text-uppercase">Workbook rows</small>
            <div class="h3 mb-0">{{ $plan['stats']['rows'] }}</div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-lg-3 mb-3">
        <div class="card border-success h-100">
          <div class="card-body">
            <small class="text-success text-uppercase">Ready</small>
            <div class="h3 mb-0 text-success">{{ $plan['stats']['ready'] }}</div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-lg-3 mb-3">
        <div class="card border-danger h-100">
          <div class="card-body">
            <small class="text-danger text-uppercase">Invalid</small>
            <div class="h3 mb-0 text-danger">{{ $plan['stats']['invalid'] }}</div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-lg-3 mb-3">
        <div class="card border-warning h-100">
          <div class="card-body">
            <small class="text-warning text-uppercase">Employee not resolved</small>
            <div class="h3 mb-0 text-warning">{{ $plan['stats']['unresolved'] }}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="alert alert-info">
      Only rows marked ready will be imported. Imported records will not send invitation or confirmation emails.
      Blank legacy ratings will remain unanswered.
    </div>

    @php
      $problemRows = array_values(array_filter($plan['report'], function ($row) {
        return $row['result'] !== 'ready';
      }));
    @endphp

    <div class="card border shadow-sm mb-4">
      <div class="card-header bg-light d-flex align-items-center justify-content-between">
        <h6 class="mb-0 font-weight-bold">Rows requiring attention</h6>
        <span class="badge badge-secondary">{{ count($problemRows) }}</span>
      </div>
      <div class="card-body p-0">
        @if (empty($problemRows))
          <div class="p-4 text-center text-success">All workbook rows are ready to import.</div>
        @else
          <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
              <thead class="bg-light">
                <tr>
                  <th>Excel row</th>
                  <th>Legacy reference</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Result</th>
                  <th>Reason</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($problemRows as $row)
                  <tr>
                    <td>{{ $row['excel_row'] }}</td>
                    <td>{{ $row['legacy_reference_no'] ?: '—' }}</td>
                    <td>{{ $row['full_name'] ?: '—' }}</td>
                    <td>{{ $row['email'] ?: '—' }}</td>
                    <td>
                      <span class="badge {{ $row['result'] === 'invalid' ? 'badge-danger' : 'badge-warning' }}">
                        {{ ucfirst($row['result']) }}
                      </span>
                    </td>
                    <td>{{ $row['message'] }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>

    <div class="d-flex justify-content-end align-items-center">
      <form method="post" action="{{ route('exit-interviews.import.cancel') }}" class="mr-2">
        @csrf
        <button type="submit" class="btn btn-sm btn-light border">Cancel</button>
      </form>
      <form method="post" action="{{ route('exit-interviews.import.commit') }}" onsubmit="show()">
        @csrf
        <input type="hidden" name="import_token" value="{{ $token }}">
        <button type="submit" class="btn btn-sm btn-success" {{ $plan['stats']['ready'] === 0 ? 'disabled' : '' }}>
          Import {{ $plan['stats']['ready'] }} Ready Record(s)
        </button>
      </form>
    </div>
  </div>
</div>
@endsection
