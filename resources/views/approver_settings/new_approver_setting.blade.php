<!-- Modal -->
<div class="modal fade" id="new_approver" tabindex="-1" role="dialog" aria-labelledby="approverdata" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approverdata">New Approver</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('approver.store') }}" onsubmit='show()' enctype="multipart/form-data">
                @csrf      
                <div class="modal-body">
                    <div class="row">
                        <div class='col-md-12 form-group'>
                            <label for="user_id">Select User</label>
                            <select data-placeholder="User" class="form-control form-control-sm required js-example-basic-single" 
                                    style='width:100%;' name='user_id' id="user_id" required>
                                <option value="">--Select User--</option>
                                @foreach($users as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Type of Form</label>
                            <select class="form-control form-control-sm js-example-basic-multiple" style='width:100%;' name="type_of_forms[]" id="type_of_forms" multiple required>
                                @foreach($form_types as $key => $value)
                                    <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 form-group" id="work_location_container" style="display: none;">
                            <label>Work Location Area</label>
                            <select class="form-control form-control-sm js-example-basic-multiple" style='width:100%;' name="work_location[]" id="work_location" multiple>
                                <option value="Region 1-3">Region 1-3</option>                                    
                                <option value="Region 4">Region 4</option>
                                <option value="Region 5">Region 5</option>
                                <option value="Region 6 - Panay Island">Region 6 - Panay Island</option>
                                <option value="Region 8 - Bohol">Region 8 - Bohol</option>
                                <option value="Region 18 - Negros Island Region">Region 18 - Negros Island Region</option>
                                <option value="MDS - All Area">MDS - All Area</option>
                            </select>
                        </div> 
                        {{-- <div class="col-md-12 form-group">
                            <label for="type_of_forms">Type of Form</label>
                            <select data-placeholder="Select Type of Form" class="form-control form-control-sm required js-example-basic-single" 
                                    style='width:100%;' name='type_of_forms[]' id="type_of_forms" multiple required>
                                <option value="">-- Select Type of Form --</option>
                                @foreach($form_types as $key => $value)
                                    <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            </select>
                        </div> --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>      
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {

        $('#user_id').select2({
            dropdownParent: $('#new_approver')
        });

        $('#type_of_forms').select2({
            dropdownParent: $('#new_approver')
        });

        $('#work_location').select2({
            dropdownParent: $('#new_approver')
        });
        
        // Show/hide work location based on mta selection
        $('#type_of_forms').on('change', function () {
            let selectedForms = $(this).val() || [];

            if (selectedForms.includes('mta')) {
                $('#work_location_container').slideDown();
                $('#work_location').prop('required', true);
            } else {
                $('#work_location_container').slideUp();
                $('#work_location')
                    .prop('required', false)
                    .val(null)
                    .trigger('change');
            }
        });

        // $('#user_id').on('change', function () {
        //     let userId = $(this).val();

        //     $('#type_of_forms option').prop('disabled', false);

        //     if (userId) {
        //         $.ajax({
        //             url: '/get-user-approver-forms/' + userId,
        //             type: 'GET',
        //             success: function (data) {

        //                 // disable existing
        //                 data.forEach(function (form) {
        //                     $('#type_of_forms option[value="' + form + '"]')
        //                         .prop('disabled', true);
        //                 });

        //                 // ✅ remove disabled selections
        //                 let selected = $('#type_of_forms').val() || [];
        //                 selected = selected.filter(val => !data.includes(val));

        //                 $('#type_of_forms').val(selected).trigger('change');
        //             }
        //         });
        //     }
        // });

        $('#user_id').on('change', function () {
            let userId = $(this).val();

            $('#type_of_forms option').prop('disabled', false);

            if (userId) {
                $.ajax({
                    url: '/get-user-approver-forms/' + userId,
                    type: 'GET',
                    success: function (data) {

                        data = data || [];

                        data.forEach(function (form) {
                            $('#type_of_forms option[value="' + form + '"]')
                                .prop('disabled', true);
                        });

                        let selected = $('#type_of_forms').val() || [];

                        selected = selected.filter(val => !data.includes(val));

                        $('#type_of_forms').val(selected).trigger('change');
                    }
                });
            }
        });

    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
@if(session('success'))
Swal.fire({
    icon: 'success',
    title: 'Success',
    text: "{{ session('success') }}"
});
@endif
</script>