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
            <form method='POST' action='save-approver-setting' onsubmit='show()' enctype="multipart/form-data">
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
                            <label for="type_of_forms">Type of Form</label>
                            <select data-placeholder="Select Type of Form" class="form-control form-control-sm required js-example-basic-single" 
                                    style='width:100%;' name='type_of_forms[]' id="type_of_forms" multiple required>
                                <option value="">-- Select Type of Form --</option>
                                @foreach($form_types as $key => $value)
                                    <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            </select>
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