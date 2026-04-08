<head>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<!-- Modal -->
<div class="modal fade" id="newEmployee" tabindex="-1" role="dialog" aria-labelledby="newEmployeeData" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newEmployeeData">New Employee</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="card">
          <div class="row">
            <div class="col-md-12 mx-0">
              <form id="msform" class='text-center' method='post' action='{{url('/new-employee')}}' enctype="multipart/form-data">
                {{ csrf_field() }}
                <ul id="progressbar">
                  <li class="active user" id="account" class=''><strong>Personal</strong></li>
                  <li class='employment'><strong>Employment</strong></li>
                  <li id="payment"><strong>Government</strong></li>
                  <li id="confirm"><strong>Gallery</strong></li>
                </ul>
                <fieldset class='text-right' id='personal_information'>
                  <div class="form-card">
                    <h2 class="fs-title">Personal Information</h2>
                    <div class='row mb-2'>
                      <div class='col-md-4'>
                        <label for="first_name">First Name</label>&nbsp;<span class="text-danger">*</span>
                        <input type="text" name="first_name" class='form-control form-control-sm required' placeholder="First Name" required/>
                      </div>
                      <div class='col-md-4'>
                        <label for="middle_name">Middle Name</label>
                        <input type="text" name="middle_name" class='form-control form-control-sm ' placeholder="Middle Name"/>
                      </div>
                      <div class='col-md-4'>
                        <label for="last_name">Last Name</label>&nbsp;<span class="text-danger">*</span>
                        <input type="text" name="last_name" class='form-control form-control-sm required' placeholder="Last Name" required/>
                      </div>
                    </div>
                    <div class='row mb-2'>
                      <div class='col-md-2'>
                        <label for="suffix">Suffix</label>
                        <input type="text" name="suffix" class='form-control form-control-sm ' placeholder="Suffix"/>
                      </div>
                      <div class='col-md-3'>
                        <label for="nickname">Nickname</label>&nbsp;<span class="text-danger">*</span>
                        <input type="text" name="nickname" class='form-control form-control-sm required' placeholder="Nickname" required/>
                      </div>
                      <div class='col-md-3'>
                        <label for="marital_status">Marital Status</label>&nbsp;<span class="text-danger">*</span>
                        <select class='form-control required form-control-sm ' name='marital_status' required>
                          <option value=''>--Select Marital Status--</option>
                          @foreach($marital_statuses as $marital_status)
                              <option value='{{$marital_status->name}}'>{{$marital_status->name}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class='col-md-2'>
                        <label for="religion">Religion</label>&nbsp;<span class="text-danger">*</span>
                        <input type="text" name="religion" class='form-control form-control-sm required' placeholder="Religion"/>
                      </div>
                      <div class='col-md-2'>
                        <label for="gender">Gender</label>&nbsp;<span class="text-danger">*</span>
                        <select data-placeholder="Gender" class="form-control form-control-sm required" name='gender' required>
                          <option value="">--Select Gender--</option>
                          <option value="MALE">MALE</option>
                          <option value="FEMALE">FEMALE</option>
                      </select>
                      </div>
                    </div>
                    <hr>
                    <h2 class="fs-title">Birth Information</h2>
                    <div class="row mb-2">
                      <div class="col-md-4"> 
                        <label for="birthdate">Birth Date</label>&nbsp;<span class="text-danger">*</span>
                        <input type="date" name="birthdate" class='form-control form-control-sm required' max='{{date('Y-m-d', strtotime('-18 year'))}}' placeholder="BirthDate"/>
                      </div>
                      <div class="col-md-4"> 
                        <label for="birthplace">Birth Place</label>&nbsp;<span class="text-danger">*</span> 
                        <input type="text" name="birthplace" class='form-control form-control-sm required'placeholder="Manila"/>
                      </div>
                    </div>
                    <hr>
                    <h2 class="fs-title">Contact Details</h2>
                    <div class='row mb-2'>
                      <div class='col-md-4'>
                        <label for="personal_email">Personal Email</label>&nbsp;<span class="text-danger">*</span>
                        <input type="email" name="personal_email" class='form-control required form-control-sm' placeholder="Example@gmail.com"/>
                      </div>
                      <div class="col-md-4">
                        <label for="personal_number">Personal Contact Number</label>&nbsp;<span class="text-danger">*</span>
                        <input type="text" name="personal_number" class="form-control form-control-sm required"
                              placeholder="Personal Contact Number" maxlength="11" pattern="\d{11}" required
                              oninput="this.value = this.value.replace(/\D/g,'').slice(0,11);">
                      </div>
                    </div>
                    <div class='row mb-2'>
                      <div class='col-md-6'>
                        <label for="present_address">Present Address</label>&nbsp;<span class="text-danger">*</span>
                        <input type="text" name="present_address" class='form-control form-control-sm required' placeholder="Present Address"/>
                      </div>
                      <div class='col-md-6'>
                        <input onclick='same_as_current(this.value)' type="checkbox" class="ml-1 form-check-input" id="same_as"> <label class='ml-4' for='same_as'><small><i>Same as Current Address</i></small></label>
                        <input type="text" id='permanent_address' name="permanent_address" class='form-control form-control-sm required' placeholder="Permanent Address" required/>
                      </div>
                    </div>
                    <hr>
                  </div>
                  <input type="button" name="next" class="next action-button btn btn-info" value="Next"/>
                </fieldset>
                <fieldset class='text-right' id='employment_information'>
                  <div class="form-card">
                    <h2 class="fs-title">Employment Information</h2>
                    <div class='row mb-2'>
                      <div class='col-md-4 mb-2'>
                        <label for="company">Company</label>&nbsp;<span class="text-danger">*</span>
                        <select data-placeholder="Company" class="form-control form-control-sm required js-example-basic-single " style='width:100%;' name='company' required>
                          <option value="">--Select Company--</option>
                          @foreach($companies as $company)
                            <option value="{{$company->id}}">{{$company->company_name}} - {{$company->company_code}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class='col-md-4 mb-2'>
                        <label for="position">Position</label>&nbsp;<span class="text-danger">*</span>
                        <input type="text" name="position" class='form-control form-control-sm required' placeholder="Position"/>
                      </div>
                      <div class="col-md-4 mb-2">
                          <label for="department">Department</label>&nbsp;<span class="text-danger">*</span>
                          <select data-placeholder="Department" class="form-control form-control-sm required js-example-basic-single" style="width:100%;" name="department" required>
                              <option value="">--Select Department--</option>
                              <option value="0">N/A</option>
                              @foreach($departments as $department)
                                  <option value="{{ $department->id }}">{{ $department->code }} - {{ $department->name }}</option>
                              @endforeach
                          </select>
                      </div>
                      <div class='col-md-4 mb-2'>
                        <label for="location">Location</label>&nbsp;<span class="text-danger">*</span>
                        <input type="text" name="location" class="form-control form-control-sm required" placeholder="Location" style="width:100%;" required>
                      </div>
                      <div class='col-md-4 mb-2'>
                        <label for="project">Project</label>&nbsp;<span class="text-danger">*</span>
                        <select data-placeholder="Project" class="form-control form-control-sm required js-example-basic-single " style='width:100%;' name='project' required>
                            <option value="">--Select Project--</option>
                            <option value="N/A">N/A</option>
                            @foreach($projects as $project)
                              <option value="{{$project->project_id}}">{{$project->project_id . '-' . $project->project_title}}</option>
                            @endforeach
                        </select>
                      </div>
                      <div class='col-md-4 mb-2'>
                        <label for="classification">Classification</label>&nbsp;<span class="text-danger">*</span>
                        <select data-placeholder="Classification" class="form-control form-control-sm required js-example-basic-single " style='width:100%;' name='classification' required>
                          <option value="">--Select Classification--</option>
                          @foreach($classifications as $classification)
                            <option value="{{$classification->id}}">{{$classification->name}}</option>
                          @endforeach
                      </select>
                      </div>
                      <div class='col-md-4 mb-2'>
                        <label for="level">Level</label>&nbsp;<span class="text-danger">*</span>
                        <select data-placeholder="Level" class="form-control form-control-sm required js-example-basic-single " style='width:100%;' name='level' required>
                          <option value="">--Select Level--</option>
                          @foreach($levels as $level)
                            <option value="{{$level->id}}">{{$level->name}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class='col-md-4 mb-2'>
                        <label for="immediate_supervisor">Immediate Supervisor</label>&nbsp;<span class="text-danger">*</span>
                        <select data-placeholder=" Immediate Supervisor" class="form-control form-control-sm required js-example-basic-single " style='width:100%;' name='immediate_supervisor' required>
                          <option value="">-- Immediate Supervisor--</option>
                            @foreach($users as $user)
                              <option value="{{$user->id}}">{{$user->name}}</option>
                            @endforeach
                        </select>
                      </div>
                      <div class='col-md-4 mb-2'>
                        <label for="biometric_code">Biometric Code</label>&nbsp;<span class="text-danger">*</span>
                        <input type="text" name="biometric_code" class='form-control form-control-sm required' placeholder="Biometric Code"/>
                      </div>
                      <div class='col-md-4 mb-2'>
                        <label for="date_hired">Date Hired</label>&nbsp;<span class="text-danger">*</span>
                        <input type="date" name="date_hired" class='form-control form-control-sm required' placeholder="Start Date"/>
                      </div>
                      <div class='col-md-4 mb-2'>
                        <label for="work_email">Work Email</label>&nbsp;<span class="text-danger">*</span>
                        <input type="email" name="work_email" class='form-control form-control-sm required' placeholder="Work Email" required/>
                      </div>
                      <div class='col-md-4 mb-2'>
                        <label for="schedule">Schedule</label>&nbsp;<span class="text-danger">*</span>
                        <select data-placeholder="Schedule Period" class="form-control form-control-sm required js-example-basic-single" style='width:100%' name='schedule' required>
                          <option value="">-- Schedule Period --</option>
                          @foreach($schedules as $schedule)
                            <option value="{{$schedule->id}}">{{$schedule->schedule_name}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class='col-md-4 mb-2'>
                        <label for="cost_center">Cost Center</label>&nbsp;<span class="text-danger">*</span>
                        <input type="text" name="cost_center" class='form-control form-control-sm required' placeholder="Cost Center"/>
                      </div>
                    </div>
                    <hr>
                    <div class='row mb-2'>
                      <div class='col-md-5'>
                        <h2 class="fs-title">Forms Approver
                        <button type="button" onclick='add_approver()' class="btn btn-inverse-success btn-icon btn-sm">
                          <i class="ti-plus"></i>
                        </button>
                        
                        <button type="button" onclick='remove_approver()' class="btn btn-inverse-danger btn-icon btn-sm">
                          <i class="ti-minus"></i>
                        </button>
                        </h2>
                        <div class='approvers-data'>
                          <div class='row mb-2  mt-2 ' id='approver_0'>
                            <div class='col-md-1  align-self-center'>
                              <small class='align-items-center'>1</small>
                            </div>
                            <div class='col-md-10'>
                              <select data-placeholder="Approver" class="form-control form-control-sm js-example-basic-single" style='width:100%;' name='approver[0][approver_id]'>
                                <option value="">-- Approver --</option>
                                  @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                  @endforeach
                              </select>
                              <input type="checkbox" name='approver[0][as_final]'> Tag as Final
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class='col-md-7'>
                        <h2 class="fs-title">Payment Information</h2>
                        <div class='row'> 
                          <div class='col-md-6 mb-2'>
                            <label for="bank_name">Bank Name</label>&nbsp;<span class="text-danger">*</span>
                            <select data-placeholder="Bank Name" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='bank_name' required>
                              <option value="">-- Bank Name --</option>
                              @foreach($banks as $bank)
                              <option value="{{$bank->bank_name}}">{{$bank->bank_name}} - {{$bank->bank_code}}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class='col-md-6 mb-2'>
                            <label for="bank_account_number">Account Number</label>&nbsp;<span class="text-danger">*</span>
                            <input type="text" name="bank_account_number" class='form-control form-control-sm required' placeholder="Bank Account"/>
                          </div>
                        </div>
                        <div class='row'> 
                          <div class='col-md-6 mb-2'>
                            <label for="rate">Monthly Rate</label>&nbsp;<span class="text-danger">*</span>
                            <input type="number" name="rate" step="any" class='form-control form-control-sm required' placeholder="Monthly Rate"/>
                          </div>
                          <div class='col-md-6 mb-2'>
                            <label for="work_description">Work Description</label>&nbsp;<span class="text-danger">*</span>
                            <select data-placeholder="Work Description" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='work_description' required>
                              <option value="">-- Work Description --</option>
                              <option value="Monthly">Monthly</option>
                              <option value="Non-Monthly">Non-Monthly</option>
                            </select>
                          </div>
                          <div class='col-md-4 mb-2'>
                            <label for="tax_application">TAX Application</label>&nbsp;<span class="text-danger">*</span>
                            <select data-placeholder="TAX Application" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='tax_application' required>
                              <option value="">-- TAX Application --</option>
                              <option value="Minimum">Minimum</option>
                              <option value="Non-Minimum">Non-Minimum</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                    <hr>
                  </div>
                  <input type="button" name="previous" class="previous action-button-previous btn btn-secondary" value="Previous"/>
                  <input type="button" name="next" class="next action-button btn btn-info" value="Next Step"/>
                </fieldset>
                <fieldset class='text-right' id='government_information'>
                  <div class="form-card">
                    <h2 class="fs-title">Government Information</h2>
                    <div class='row mb-2'>
                      <div class='col-md-3'>
                        <label for="sss">SSS</label>&nbsp;<span class="text-danger">*</span>
                        <input type='text' name='sss' data-inputmask-alias="99-9999999-9"  class='form-control form-control-sm required'>
                      </div>
                      <div class='col-md-3'>
                        <label for="philhealth">Philhealth</label>&nbsp;<span class="text-danger">*</span>
                        <input type='text' name='philhealth' data-inputmask-alias="9999-9999-9999" class='form-control form-control-sm required'>
                      </div>
                      <div class='col-md-3'>
                        <label for="pagibig">Pagibig</label>&nbsp;<span class="text-danger">*</span>
                        <input type='text' name='pagibig' data-inputmask-alias="9999-9999-9999" class='form-control form-control-sm required'>
                      </div>
                      <div class='col-md-3'>
                        <label for="tin">TIN</label>&nbsp;<span class="text-danger">*</span>
                        <input type='text' name='tin' data-inputmask-alias="999-999-999" class='form-control form-control-sm required'>
                      </div>
                    </div>
                    <hr>
                    <h2 class="fs-title">Upload Supporting Documents</h2>
                    <div class='row mb-2'>
                      <div class='col-md-12'>
                        <input type='file' name='documents[]' class='form-control form-control-sm' multiple>
                      </div>
                    </div>
                    <hr>
                  </div>
                  <input type="button" name="previous" class="previous action-button-previous btn btn-secondary" value="Previous"/>
                  <input type="button" name="next" class="next action-button btn btn-info" value="Next Step"/>
                </fieldset>
                <fieldset class='text-right' id='images'> 
                  <div class="form-card">
                    <hr>
                    <div class="form-group row">
                      <div class="col-lg-3 align-self-center text-right">
                          <img id='avatar' src="{{URL::asset('/images/no_image.png')}}" class="rounded-circle circle-border m-b-md border" alt="profile" height='125px;' width='125px;'>
                      </div>
                      <div class="col-lg-3 align-self-center text-left">
                        <label title="Upload image file" for="inputImage" class="btn btn-primary btn-sm ">
                            <input type="file" accept="image/*" name="file" id="inputImage" style="display:none" onchange='uploadimage(this)'>
                            Upload Avatar
                        </label><br>
                      </div>
                      <div class="col-lg-3 text-center">
                        <img id='signature' src="{{URL::asset('/images/signature.png')}}" class="rounded-square circle-border m-b-md border" alt="profile" height='125px;' width='225px;'>
                      </div>
                      <div class="col-lg-3 align-self-center text-left">
                        <label title="Upload signature file" for="inputSignature" class="btn btn-primary btn-sm ">
                            <input type="file" accept="image/*" name="signature" id="inputSignature" style="display:none" onchange='uploadsignature(this)'>
                            Upload Signature
                        </label>
                      </div>
                    </div>
                    <hr>
                  </div>
                  <input type="button" name="previous" class="previous action-button-previous btn btn-secondary" value="Previous"/>
                  <button type="submit" class="btn btn-primary">Save</button>
                </fieldset>
              </form>
            </div>
          </div>
        </div>
      </div>
      {{-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save</button>
      </div> --}}
    </div>
  </div>
</div>

<style>
  .select2-container--default .select2-selection--single {
    padding: 0.75rem 1.375rem !important;
  }
</style>
    
<script>
  function same_as_current(value)
  {
    var checkbox = document.querySelector('#same_as');
    if(checkbox.checked == true)
    {
      document.getElementById("permanent_address").readOnly = true;
      document.getElementById("permanent_address").required = false;
      document.getElementById("permanent_address").value = "";
      document.getElementById("permanent_address").classList.remove("required");
      document.getElementById("permanent_address").style.border = '1px solid #CED4DA';    
      

    }
    else
    {
      document.getElementById("permanent_address").readOnly = false;
      document.getElementById("permanent_address").required = true;
      document.getElementById("permanent_address").value = "";
      document.getElementById("permanent_address").classList.add("required");
    }
  
    
  }

  function add_approver()
  {
    var lastItemID = $('.approvers-data').children().last().attr('id');
    if(lastItemID){
        var last_id = lastItemID.split("_");
        finalLastId = parseInt(last_id[1]) + 1;
        level = finalLastId + 1;
    }else{
        finalLastId = 0;
        level = finalLastId + 1;
    }
                                
        var item = "<div class='row mb-2  mt-2 ' id='approver_"+finalLastId+"'>";
            item+= "<div class='col-md-1  align-self-center'>";
            item+= "<small class='align-items-center'>"+level+"</small>";
            item+= "</div>";
            item+= " <div class='col-md-10'>";
            item+= " <select data-placeholder='Approver' class='form-control form-control-sm required js-example-basic-single' style='width:100%;' name='approver["+finalLastId+"][approver_id]' required>";
            item+= "<option value=''>-- Approver --</option>";
            item+= " @foreach($users as $user)";
            item+= "<option value='{{$user->id}}'>{{$user->name}}</option>";
            item+= "@endforeach";
            item+= "</select>";
            item+= "<input type='checkbox' name='approver["+finalLastId+"][as_final]'> Tag as Final";
            item+= "</div>";
            item+= "</div>";
          
            $(".approvers-data").append(item);
            $(".js-example-basic-single").select2();

  }
  function remove_approver()
  {
    if($('div.approvers-data div.row').length > 1)
    {
    var lastItemID = $('.approvers-data').children().last().attr('id');
    $('#'+lastItemID).remove();
    }

  }
  function uploadimage(input)
  {
      if (input.files && input.files[0]) {
          var reader = new FileReader();
          
          reader.onload = function(e) {
              $('#avatar').attr('src', e.target.result);
          }
          
          reader.readAsDataURL(input.files[0]);
      }
  }
  function uploadsignature(input)
  {
      if (input.files && input.files[0]) {
          var reader = new FileReader();
          
          reader.onload = function(e) {
              $('#signature').attr('src', e.target.result);
          }
          
          reader.readAsDataURL(input.files[0]);
      }
  }

  document.querySelectorAll('.next').forEach(button => {
    button.addEventListener('click', function (e) {
      const currentFieldset = this.closest('fieldset');
      const personalEmailInput = document.querySelector('input[name="personal_email"]');
      const workEmailInput = document.querySelector('input[name="work_email"]');

      if (currentFieldset.id === 'personal_information') {
        const email = personalEmailInput.value.trim();
        if (email !== '' && !isValidEmail(email)) {
          Swal.fire({
            icon: 'error',
            title: 'Invalid Email',
            text: 'Please enter a valid email address (e.g., yourname@example.com)',
          });
          personalEmailInput.focus();
          return;
        }
      }

      if (currentFieldset.id === 'employment_information') {
        const email = workEmailInput.value.trim();
        if (email !== '' && !isValidEmail(email)) {
          Swal.fire({
            icon: 'error',
            title: 'Invalid Work Email',
            text: 'Please enter a valid work email address (e.g., name@company.com)',
          });
          workEmailInput.focus();
          return;
        }
      }

    });
  });

  function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

</script>



