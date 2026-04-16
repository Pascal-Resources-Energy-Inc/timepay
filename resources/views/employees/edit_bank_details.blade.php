<div class="modal fade" id="editAcctNo">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">Edit Bank Details</div>
            <form action="{{url('update-account-no/'.$user->employee->id)}}" method="post" onsubmit="show()">
                {{csrf_field()}}
                <div class="modal-body">
                    <div class='row mb-2'>
                        <div class="col-md-6">
                            <label for="bank_name">Bank Name</label>
                            <select data-placeholder="Bank Name" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='bank_name' id="bank_name" required>
                              <option value="">-- Bank Name --</option>
                                @foreach($banks as $bank)
                                    <option value="{{$bank->bank_name}}" {{ $bank->bank_name == $user->employee->bank_name ? 'selected' : '' }}>{{$bank->bank_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="account_no">Account Number</label>
                            <input type="text" name="account_no" id="account_no" class="form-control" value="{{$user->employee->bank_account_number}}" required>
                        </div>
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

<script>
    $(document).ready(function() {
        $('#bank_name').select2({
            tags: true,
            placeholder: "Bank Name",
            allowClear: true
        });
    });
</script>