@extends('layouts.header')

@section('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
  .activity-badge {
    font-size: 0.75rem;
    padding: 0.35rem 0.65rem;
  }
  .details-box {
    background-color: #f8f9fa;
    border-left: 3px solid #007bff;
    padding: 10px;
    margin-top: 5px;
    font-size: 0.85rem;
  }
  .details-box strong {
    color: #495057;
  }
  .change-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.8rem;
    font-weight: 500;
  }
  .change-badge.old {
    background-color: #fff3cd;
    color: #856404;
  }
  .change-badge.new {
    background-color: #d4edda;
    color: #155724;
  }
  .timeline-item {
    border-left: 2px solid #dee2e6;
    padding-left: 20px;
    margin-bottom: 20px;
    position: relative;
  }
  .timeline-item::before {
    content: '';
    width: 12px;
    height: 12px;
    background: #007bff;
    border-radius: 50%;
    position: absolute;
    left: -7px;
    top: 5px;
  }
  .timeline-item.created::before {
    background: #28a745;
  }
  .timeline-item.status_updated::before {
    background: #ffc107;
  }
  .timeline-item.deleted::before {
    background: #dc3545;
  }
</style>
@endsection

@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    
    <div class='row'>
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">
              <i class="ti-time"></i> TDS Activity History
            </h4>
            <p class="card-description">
              Track all changes and activities in the TDS module
            </p>

            <form method='get' action="{{ route('tds.history') }}" class="mb-4">
              <div class="row">
                <div class='col-md-3'>
                  <div class="form-group">
                    <label>From Date</label>
                    <input type="date" 
                           value='{{ request('from') }}' 
                           class="form-control form-control-sm" 
                           name="from"
                           max='{{ date('Y-m-d') }}' />
                  </div>
                </div>
                <div class='col-md-3'>
                  <div class="form-group">
                    <label>To Date</label>
                    <input type="date" 
                           value='{{ request('to') }}' 
                           class="form-control form-control-sm" 
                           name="to"
                           max='{{ date('Y-m-d') }}' />
                  </div>
                </div>
                <div class='col-md-3'>
                  <div class="form-group">
                    <label>Action Type</label>
                    <select class="form-control form-control-sm" name='action'>
                      <option value="">-- All Actions --</option>
                      @if(!empty($actions) && is_array($actions))
                        @foreach($actions as $action)
                          <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                            {{ ucwords(str_replace('_', ' ', $action)) }}
                          </option>
                        @endforeach
                      @endif
                    </select>
                  </div>
                </div>
                @if(auth()->user()->role == 'Admin')
                <div class='col-md-2'>
                  <div class="form-group">
                    <label>User</label>
                    <select class="form-control form-control-sm select2-user" name='user_id'>
                      <option value="">-- All Users --</option>
                      @if(isset($users) && count($users) > 0)
                        @foreach($users as $user)
                          <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                          </option>
                        @endforeach
                      @endif
                    </select>
                  </div>
                </div>
                @endif
                <div class='col-md-1'>
                  <label>&nbsp;</label>
                  <button type="submit" class="form-control btn btn-primary btn-sm">
                    <i class="ti-filter"></i> Filter
                  </button>
                </div>
              </div>
            </form>

            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th style="width: 12%">Date & Time</th>
                    <th style="width: 12%">Changed By</th>
                    <th style="width: 12%">Action</th>
                    <th style="width: 15%">Record</th>
                    <th style="width: 49%">Changes & Details</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($logs as $log)
                  <tr>
                    <td>
                      <small class="text-muted">
                        {{ $log->created_at->format('M d, Y') }}<br>
                        <strong>{{ $log->created_at->format('h:i A') }}</strong>
                      </small>
                    </td>
                    <td>
                      <i class="ti-user"></i> {{ $log->user ? $log->user->name : 'System' }}
                    </td>
                    <td>
                      @if($log->action == 'created')
                        <span class="badge badge-success activity-badge">
                          <i class="ti-plus"></i> Created
                        </span>
                      @elseif($log->action == 'updated')
                        <span class="badge badge-info activity-badge">
                          <i class="ti-pencil"></i> Updated
                        </span>
                      @elseif($log->action == 'status_updated')
                        <span class="badge badge-warning activity-badge">
                          <i class="ti-pencil"></i> Status Changed
                        </span>
                      @elseif($log->action == 'deleted')
                        <span class="badge badge-danger activity-badge">
                          <i class="ti-trash"></i> Deleted
                        </span>
                      @else
                        <span class="badge badge-secondary activity-badge">
                          <i class="ti-info"></i> {{ ucwords(str_replace('_', ' ', $log->action)) }}
                        </span>
                      @endif
                    </td>
                    <td>
                      <strong>{{ ucwords(str_replace('_', ' ', $log->record_type)) }}</strong>
                      @if($log->tds)
                        <br><small class="text-muted">{{ $log->tds->customer_name }}</small>
                      @elseif($log->record_identifier)
                        <br><small class="text-muted">{{ $log->record_identifier }}</small>
                      @endif
                    </td>
                    <td>
                      @if($log->details)
                        <div class="details-box">
                          @if($log->record_type == 'tds' && $log->action == 'created')
                            <strong>New TDS Record Created</strong><br>
                            <strong>Customer:</strong> {{ $log->details['customer_name'] ?? 'N/A' }}<br>
                            <strong>Business:</strong> {{ $log->details['business_name'] ?? 'N/A' }}<br>
                            @if(isset($log->details['location']))
                              <strong>Location:</strong> {{ $log->details['location'] }}<br>
                            @endif
                            <strong>Package:</strong> <span class="badge badge-primary">{{ $log->details['package_type'] ?? 'N/A' }}</span><br>
                            <strong>Amount:</strong> <span class="text-success font-weight-bold">₱{{ number_format($log->details['purchase_amount'] ?? 0, 2) }}</span><br>
                            @if(isset($log->details['program_type']) && $log->details['program_type'])
                              <strong>Program:</strong> {{ $log->details['program_type'] }}<br>
                            @endif
                            <strong>Initial Status:</strong> <span class="badge badge-info">{{ $log->details['status'] ?? 'N/A' }}</span>
                          
                          @elseif($log->record_type == 'tds' && $log->action == 'status_updated')
                            <strong>Status Change for:</strong> {{ $log->details['customer_name'] ?? 'N/A' }}<br>
                            <div class="mt-2">
                              <span class="change-badge old">
                                <i class="ti-arrow-left"></i> From: {{ $log->details['old_status'] ?? 'N/A' }}
                              </span>
                              <i class="ti-arrow-right mx-2"></i>
                              <span class="change-badge new">
                                <i class="ti-arrow-right"></i> To: {{ $log->details['new_status'] ?? 'N/A' }}
                              </span>
                            </div>
                          
                          @elseif($log->record_type == 'tds' && $log->action == 'deleted')
                            <strong>Deleted TDS Record</strong><br>
                            <strong>Customer:</strong> {{ $log->details['customer_name'] ?? 'N/A' }}<br>
                            <strong>Business:</strong> {{ $log->details['business_name'] ?? 'N/A' }}<br>
                            <strong>Amount:</strong> <span class="text-danger">₱{{ number_format($log->details['purchase_amount'] ?? 0, 2) }}</span>
                          
                          @elseif($log->record_type == 'sales_target' && in_array($log->action, ['created', 'updated']))
                            <strong>Sales Target {{ $log->action == 'created' ? 'Set' : 'Updated' }}</strong><br>
                            <strong>Employee:</strong> {{ $log->details['employee'] ?? 'N/A' }}<br>
                            <strong>Month:</strong> {{ isset($log->details['month']) ? \Carbon\Carbon::parse($log->details['month'])->format('F Y') : 'N/A' }}<br>
                            <strong>Target Amount:</strong> <span class="text-success font-weight-bold">₱{{ number_format($log->details['target_amount'] ?? 0, 2) }}</span><br>
                            @if(isset($log->details['notes']) && $log->details['notes'])
                              <strong>Notes:</strong> {{ $log->details['notes'] }}
                            @endif
                          
                          @else
                            @foreach($log->details as $key => $value)
                              @if(!is_array($value))
                                <strong>{{ ucwords(str_replace('_', ' ', $key)) }}:</strong> 
                                {{ $value }}<br>
                              @endif
                            @endforeach
                          @endif
                        </div>
                      @else
                        <span class="text-muted">No additional details available</span>
                      @endif
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="5" class="text-center py-4">
                      <i class="ti-info-alt" style="font-size: 2rem; color: #ccc;"></i>
                      <p class="text-muted mt-2">No activity logs found</p>
                    </td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>

            <div class="mt-3">
              {{ $logs->appends(request()->query())->links() }}
            </div>

            <div class="row mt-4">
              <div class="col-md-12">
                <div class="alert alert-info">
                  <strong>Summary:</strong> 
                  Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} 
                  of {{ $logs->total() }} activity logs
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
  $('.select2-user').select2({
    placeholder: '-- All Users --',
    allowClear: true,
    width: '100%'
  });
});
</script>
@endsection