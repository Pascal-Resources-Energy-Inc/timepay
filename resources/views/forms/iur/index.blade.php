@extends('layouts.header')
@section('content')
  <div class="main-panel">
    <div class="content-wrapper">
      <div class='row grid-margin'>
        <div class='col-lg-2 '>
          <div class="card card-tale">
            <div class="card-body">
              <div class="media">                
                <div class="media-body">
                  <h4 class="mb-4">Pending</h4>
                  <h2 class="card-text"></h2>
                </div>
              </div>
            </div>
          </div>
        </div> 
        <div class='col-lg-2'>
          <div class="card card-light-danger">
            <div class="card-body">
              <div class="media">
                <div class="media-body">
                  <h4 class="mb-4">Declined/Cancelled</h4>
                  <h2 class="card-text"></h2>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class='col-lg-2'>
          <div class="card text-success">
            <div class="card-body">
              <div class="media">                
                <div class="media-body">
                  <h4 class="mb-4">Approved</h4>
                  <h2 class="card-text"></h2>
                </div>
              </div>
            </div>
          </div>
        </div> 
      </div>
      <div class='row'>
        <div class="col-lg-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">ID & Uniform Request</h4>
              <a href="{{ route('iur.create') }}" class="btn btn-outline-success btn-icon-text">
                  <i class="ti-plus btn-icon-prepend"></i>
                  Apply ID & Uniform Request
                </a>
              <form method='get' onsubmit='show();' enctype="multipart/form-data">
                <div class="row mt-2">
                  <div class='col-md-2'>
                    <div class="form-group">
                      <label class="text-right">From</label>
                      <input type="date" value='{{$from}}' class="form-control form-control-sm" name="from"
                          max='{{ date('Y-m-d') }}' onchange='get_min(this.value);' required />
                    </div>
                  </div>
                  <div class='col-md-2'>
                    <div class="form-group">
                      <label class="text-right">To</label>
                      <input type="date" value='{{$to}}' class="form-control form-control-sm" id='to' name="to" required />
                    </div>
                  </div>
                  <div class='col-md-2 mr-2'>
                    <div class="form-group">
                      <label class="text-right">Status</label>
                      <select data-placeholder="Select Status" class="form-control form-control-sm " style='width:100%;' name='status' required>
                        <option value="">-- Select Status --</option>
                        <option value="Approved" @if ('Approved' == $status) selected @endif>Approved</option>
                        <option value="Pending" @if ('Pending' == $status) selected @endif>Pending</option>
                        <option value="Cancelled" @if ('Cancelled' == $status) selected @endif>Cancelled</option>
                        <option value="Declined" @if ('Declined' == $status) selected @endif>Declined</option>
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
                      <th width="10%">Action</th>
                      <th width="10%">UIR Reference</th>
                      <th width="12%">Request Date</th>
                      <th width="10%">Request For</th> 
                      <th width="12%">Type</th>
                      <th width="13%">Work Location</th>
                      <th width="25%">Details of Work</th>
                      <th width="8%">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($iurs as $iur)
                      <tr>
                        <td>
                          <a href="{{ route('iur.show', $iur->id) }}">
                            <button type="button" class="btn btn-info btn-rounded btn-icon" title="View ID and Uniform"><i class="ti-eye btn-icon-prepend"></i></button>      
                          </a>   
                          @if($iur->status == 'Pending')
                            <a href="{{ route('iur.edit', $iur->id) }}">
                              <button type="button" class="btn btn-primary btn-rounded btn-icon" title="Edit ID and Uniform"><i class="ti-pencil-alt btn-icon-prepend"></i></button> 
                            </a>
                          @endif
                          @if($iur->status == 'Pending')
                            <button type="button"class="btn btn-danger btn-rounded btn-icon btn-cancel" data-id="{{ $iur->id }}"title="Cancel ID and Uniform"><i class="fa fa-ban"></i></button>
                          @endif
                        </td>
                        <td>{{ $iur->iur_reference }}</td>
                        <td>{{ date('M. d, Y h:i A', strtotime($iur->created_at)) }}</td>
                        <td>{{ $iur->request_for }}</td>
                        <td>{{ $iur->type }}</td>
                        <td>{{ $iur->work_location }}</td>
                        <td>{{ $iur->details }}</td>
                        <td id="tdStatus{{ $iur->id }}">
                          @if ($iur->status == 'Pending')
                            <label class="badge badge-warning">{{ $iur->status }}</label>
                          @elseif($iur->status == 'Approved')
                            <label class="badge badge-success">{{ $iur->status }}</label>
                          @elseif($iur->status == 'Declined' or $iur->status == 'Cancelled')
                            <label class="badge badge-danger">{{ $iur->status }}</label>
                          @endif                        
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
                <form id="cancelForm" method="POST" style="display:none;">
                  @csrf
                  @method('PUT')
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
  $(document).on('click', '.btn-cancel', function () {
    let id = $(this).data('id');
    let url = "{{ route('iur.cancel', ':id') }}".replace(':id', id);

    Swal.fire({
        title: 'Are you sure?',
        text: "You want to cancel this request?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, cancel it!',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result.isConfirmed) {
            let form = $('#cancelForm');
            form.attr('action', url);
            form.submit();
        }
    });
  });
</script>