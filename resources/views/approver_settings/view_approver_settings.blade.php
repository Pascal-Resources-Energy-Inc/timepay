@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">FORMS APPROVER</h4>
                <p class="card-description">
                  @if (checkUserPrivilege('settings_add',auth()->user()->id) == 'yes')
                  <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#new_approver">
                    <i class="ti-plus btn-icon-prepend"></i>                                                    
                    New Approver
                  </button>
                  @endif
                </p>
                <div class="table-responsive">
                  <table class="table table-hover table-bordered tablewithSearch">
                    <thead>
                      <tr>
                        <th>FORM APPROVER</th>
                        <th>Type of Form</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($form_approvers as $form_approver)
                        <tr>
                            <td>{{$form_approver->user->name}}</td>
                            <td>{{$form_approver->form_type_name}}</td>
                            <td id="tdActionId{{ $form_approver->id }}" data-id="{{ $form_approver->id }}">
                              @if (checkUserPrivilege('settings_delete',auth()->user()->id) == 'yes')
                                <button title='Remove' id="{{ $form_approver->id }}" onclick="remove({{$form_approver->id}})"
                                    class="btn btn-rounded btn-danger btn-icon">
                                    <i class="fa fa-ban"></i>
                                </button>
                              @endif
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

@include('approver_settings.new_approver_setting') 

<script>
    function remove(id) {
        var element = document.getElementById('tdActionId'+id);
        var dataID = element.getAttribute('data-id');
        
        Swal.fire({
            title: "Are you sure?",
            text: "You want to remove this Form Approver?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById("loader").style.display = "block";
                
                $.ajax({
                    url: "remove-approver/" + id,
                    method: "GET",
                    data: {
                        id: id
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        document.getElementById("loader").style.display = "none";
                        
                        Swal.fire({
                            title: 'Removed!',
                            text: 'Form Approver has been removed!',
                            icon: 'success'
                        }).then(function() {
                            location.reload();
                        });
                    },
                    error: function(xhr, status, error) {
                        document.getElementById("loader").style.display = "none";
                        
                        Swal.fire({
                            title: 'Error!',
                            text: 'There was an error removing the approver.',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    }
</script>
@endsection