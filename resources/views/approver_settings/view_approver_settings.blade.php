@extends('layouts.header')
@section('content')
<style>
  .access-settings { color: #24324a; }
  .access-settings .access-card { border: 1px solid #e6ebf2; border-radius: 14px; box-shadow: 0 6px 24px rgba(30, 50, 80, .06); overflow: hidden; }
  .access-settings .access-header { display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap; padding: 26px 28px; border-bottom: 1px solid #edf0f5; }
  .access-settings .access-heading { margin: 0 0 6px; color: #17243b; font-size: 21px; font-weight: 700; }
  .access-settings .access-subtitle { margin: 0; color: #6b788b; font-size: 14px; line-height: 1.5; }
  .access-settings .access-add { border-radius: 8px; padding: 11px 17px; font-weight: 600; }
  .access-settings .access-body { padding: 22px 28px 28px; }
  .access-settings .access-table { width: 100% !important; border-collapse: collapse !important; }
  .access-settings .access-table thead th { background: #f6f8fb; border-color: #e8edf4; color: #58677c; font-size: 12px; font-weight: 700; letter-spacing: .035em; text-transform: uppercase; padding: 14px 16px; }
  .access-settings .access-table tbody td { border-color: #edf0f5; padding: 16px; vertical-align: middle; white-space: normal; }
  .access-settings .access-user { color: #17243b; font-weight: 600; }
  .access-settings .access-location { color: #69788b; }
  .access-settings .access-badge { display: inline-flex; align-items: center; border-radius: 30px; padding: 5px 10px; font-size: 12px; font-weight: 700; line-height: 1.3; }
  .access-settings .access-badge--approver { color: #176b45; background: #e4f5ec; }
  .access-settings .access-badge--viewer { color: #285b9e; background: #e9f1fc; }
  .access-settings .access-badge--other { color: #5e6a7a; background: #eef1f5; }
  .access-settings .access-remove { border-radius: 8px; padding: 7px 11px; font-size: 13px; white-space: nowrap; }
  .access-settings .dataTables_wrapper .dataTables_filter { float: none; width: 100%; margin: 0 0 16px; text-align: left; }
  .access-settings .dataTables_wrapper .dataTables_filter label { display: flex; flex-direction: column; align-items: flex-start; gap: 6px; margin: 0; font-weight: 600; }
  .access-settings .dataTables_wrapper .dataTables_filter input { width: 300px; max-width: 100%; margin-left: 0; border: 1px solid #78889c; border-radius: 7px; min-height: 38px; padding: 6px 10px; background: #fff; color: #24324a; }
  .access-settings .dataTables_wrapper .dataTables_filter input:focus { border-color: #26805b; outline: 2px solid rgba(38, 128, 91, .2); outline-offset: 1px; }
  .access-settings .dataTables_info, .access-settings .dataTables_paginate { padding-top: 16px !important; }
  @media (max-width: 767px) {
    .access-settings .access-header, .access-settings .access-body { padding: 20px; }
    .access-settings .access-add { width: 100%; }
    .access-settings .access-table { min-width: 750px; }
    .access-settings .dataTables_filter { text-align: left; }
  }
</style>
<div class="main-panel access-settings">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card access-card w-100">
          <div class="access-header">
            <div>
              <h4 class="access-heading">Form Access Settings</h4>
              <p class="access-subtitle">Manage who can view and approve form requests.</p>
            </div>
            @if (checkUserPrivilege('settings_add', auth()->user()->id) == 'yes')
              <button type="button" class="btn btn-info btn-sm d-flex align-items-center" data-toggle="modal" data-target="#new_approver">
                <i class="ti-plus" aria-hidden="true"></i>
              </button>
            @endif
          </div>
          <div class="access-body">
            @if ($errors->any())
              <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
            @endif
            <div class="table-responsive">
              <table class="table table-hover tablewithSearch access-table">
                <thead>
                  <tr>
                    <th>User</th>
                    <th>Type of Form</th>
                    <th>Access Type</th>
                    <th>Work Location</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($form_approvers as $form_approver)
                    @php
                      $accessType = strtolower($form_approver->access_type ?? 'approver');
                      $badgeClass = in_array($accessType, ['approver', 'viewer']) ? $accessType : 'other';
                    @endphp
                    <tr>
                      <td class="access-user">{{ $form_approver->user->name }}</td>
                      <td>{{ $form_approver->form_type_name }}</td>
                      <td><span class="access-badge access-badge--{{ $badgeClass }}">{{ ucfirst($accessType) }}</span></td>
                      <td class="access-location">{{ $form_approver->work_location ?? 'N/A' }}</td>
                      <td id="tdActionId{{ $form_approver->id }}" data-id="{{ $form_approver->id }}">
                        @if (checkUserPrivilege('settings_delete', auth()->user()->id) == 'yes')
                          <button type="button" title="Remove access" aria-label="Remove access" id="{{ $form_approver->id }}" onclick="remove({{ $form_approver->id }})" class="btn btn-outline-danger btn-sm access-remove">
                            <i class="fa fa-ban mr-1" aria-hidden="true"></i> Remove
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
    // function remove(id) {
    //     var element = document.getElementById('tdActionId'+id);
    //     var dataID = element.getAttribute('data-id');
        
    //     Swal.fire({
    //         title: "Are you sure?",
    //         text: "Do you want to remove this form access?",
    //         icon: "warning",
    //         showCancelButton: true,
    //         confirmButtonColor: '#3085d6',
    //         cancelButtonColor: '#d33',
    //         confirmButtonText: 'Yes, remove it!'
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             document.getElementById("loader").style.display = "block";
                
    //             $.ajax({
    //                 url: "remove-approver/" + id,
    //                 method: "GET",
    //                 data: {
    //                     id: id
    //                 },
    //                 headers: {
    //                     'X-CSRF-TOKEN': '{{ csrf_token() }}'
    //                 },
    //                 success: function(data) {
    //                     document.getElementById("loader").style.display = "none";
                        
    //                     Swal.fire({
    //                         title: 'Removed!',
    //                         text: 'Form Approver has been removed!',
    //                         icon: 'success'
    //                     }).then(function() {
    //                         location.reload();
    //                     });
    //                 },
    //                 error: function(xhr, status, error) {
    //                     document.getElementById("loader").style.display = "none";
                        
    //                     Swal.fire({
    //                         title: 'Error!',
    //                         text: 'There was an error removing the approver.',
    //                         icon: 'error'
    //                     });
    //                 }
    //             });
    //         }
    //     });
    // }
  function remove(id) {

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

            $("#loader").show();

            $.ajax({
                url: "/remove-approver/" + id,
                type: "POST", // ✅ IMPORTANT
                data: {
                    _method: "DELETE", // ✅ spoof DELETE
                    _token: "{{ csrf_token() }}" // ✅ required
                },
                success: function(response) {

                    $("#loader").hide();

                    if (response.success) {

                        Swal.fire({
                            title: 'Removed!',
                            text: response.message,
                            icon: 'success'
                        });

                        // ✅ REMOVE ROW WITHOUT RELOAD
                        $('#tdActionId' + id).closest('tr').fadeOut(300, function () {
                            $(this).remove();
                        });

                    } else {
                        Swal.fire('Error!', response.message, 'error');
                    }
                },
                error: function() {

                    $("#loader").hide();

                    Swal.fire({
                        title: 'Error!',
                        text: 'Something went wrong.',
                        icon: 'error'
                    });
                }
            });

        }
    });
  }
</script>
@endsection
