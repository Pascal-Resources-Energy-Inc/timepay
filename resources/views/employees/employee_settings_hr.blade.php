@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        {{-- <div class='row'>
            <div class="col-lg-4 grid-margin ">
                <div class="card">
                    <div class="card-body text-center">
                        <img class="rounded-circle" style='width:170px;height:170px;' src='{{URL::asset($user->employee->avatar)}}' onerror="this.src='{{URL::asset('/images/no_image.png')}}';">
                        <h3 class="card-text mt-3">{{$user->employee->first_name}} @if($user->employee->middle_initial != null){{$user->employee->middle_initial}}.@endif {{$user->employee->last_name}}</h3>
                        <h4 class="card-text mt-2">{{$user->employee->position}}</h4>
                        <h5 class="card-text mt-2">Biometric Code : {{$user->employee->employee_number}}</h5>
                        <h5 class="card-text mt-2">Employee Code : {{$user->employee->employee_code}}</h5>
                        @if($user->employee->signature)
                            <img src='{{URL::asset($user->employee->signature)}}' onerror="this.src='{{URL::asset('/images/signature.png')}}';" height='80px;' width='250px'><br>
                        @endif
                        <button class="btn btn-primary btn-sm mt-3" data-toggle="modal" data-target="#uploadAvatar">
                            Upload Avatar
                        </button>
                        <button class="btn btn-info btn-sm mt-3" data-toggle="modal" data-target="#uploadSignature">
                            Upload Signature
                        </button>
                        <a class="btn btn-warning btn-sm mt-3" href='{{url("print-id/".$user->employee->id)}}' target="_blank">
                            Print ID
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 grid-margin">
                <div class="card">
                    <div class="card-body text-left">
                        <div class="template-demo">
                            <div class='row m-2'>
                                <div class='col-md-12 text-center'>
                                    <strong>
                                        <h3><i class="fa fa-user" aria-hidden="true"></i> Personal Information
                                            @if (checkUserPrivilege('employees_edit',auth()->user()->id) == 'yes')
                                                <button class="btn btn-outline-primary btn-sm btn-icon-text" title="Edit Information" data-toggle="modal" data-target="#editInfo"><i class="fa fa-pencil"></i></button>
                                            @endif
                                        </h3>
                                    </strong>
                                </div>
                            </div>
                            <div class='row m-2 border-bottom'>
                                <div class='col-md-3 '>
                                    <small>Nickname </small>
                                </div>
                                <div class='col-md-9'>
                                    {{$user->employee->nick_name}}
                                </div>
                            </div>
                            <div class='row  m-2 border-bottom'>
                                <div class='col-md-3'>
                                    <small> Full Name </small>
                                </div>
                                <div class='col-md-9'>
                                    {{$user->employee->first_name}} @if($user->employee->middle_initial != null){{$user->employee->middle_initial}}.@endif {{$user->employee->last_name}}
                                </div>
                            </div>
                            <div class='row  m-2 border-bottom'>
                                <div class='col-md-3'>
                                    <small> Email </small>
                                </div>
                                <div class='col-md-9'>
                                    {{$user->employee->personal_email}}
                                </div>
                            </div>
                            <div class='row  m-2 border-bottom'>
                                <div class='col-md-3'>
                                    <small> Phone</small>
                                </div>
                                <div class='col-md-9'>
                                    {{$user->employee->personal_number}}
                                </div>
                            </div>
                            <div class='row  m-2 border-bottom'>
                                <div class='col-md-3'>
                                    <small>Marital Status </small>
                                </div>
                                <div class='col-md-9'>
                                    {{$user->employee->marital_status}}
                                </div>
                            </div>
                            <div class='row  m-2 border-bottom'>
                                <div class='col-md-3'>
                                    <small>Religion </small>
                                </div>
                                <div class='col-md-3'>
                                    {{$user->employee->religion}}
                                </div>
                                <div class='col-md-3'>
                                    <small>Gender </small>
                                </div>
                                <div class='col-md-3'>
                                    {{$user->employee->gender}}
                                </div>
                            </div>
                            <div class='row  m-2 border-bottom'>
                                <div class='col-md-3'>
                                    <small>Address </small>
                                </div>
                                <div class='col-md-9'>
                                    <small> Present : {{$user->employee->present_address}} </small><br>
                                    <small> Permanent : {{$user->employee->permanent_address}} </small>
                                </div>
                            </div>
                            <div class='row  m-2 border-bottom'>
                                <div class='col-md-3'>
                                    <small>Birth </small>
                                </div>
                                <div class='col-md-9'>
                                    @php
                                    $d1 = new DateTime($user->employee->birth_date);
                                    $d2 = new DateTime();
                                    $diff = $d2->diff($d1);
                                    @endphp
                                    <small> Date : {{date('F d, Y',strtotime($user->employee->birth_date))}} : {{$diff->y}} Years Old</small><br>
                                    <small> Place : {{$user->employee->birth_place}} </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($user->employee->payment_info)
                <div class="card mt-3">
                    <div class="card-body text-left">
                        <div class="template-demo">
                            <div class='row m-2'>
                                <div class='col-md-12 text-center'>
                                    <strong>
                                        <h3><i class="fa fa-money" aria-hidden="true"></i> Payment Information</h3>
                                    </strong>
                                </div>
                            </div>
                            <div class='row  m-2 border-bottom'>
                                <div class='col-md-3'>
                                    <small>Payment Period</small>
                                </div>
                                <div class='col-md-3'>
                                    {{$user->employee->payment_info->payment_period}}
                                </div>
                                <div class='col-md-3'>
                                    <small>Payment Type</small>
                                </div>
                                <div class='col-md-3'>
                                    {{$user->employee->payment_info->payment_type}}
                                </div>
                            </div>
                            <div class='row  m-2 border-bottom'>
                                <div class='col-md-3'>
                                    <small>Bank Name</small>
                                </div>
                                <div class='col-md-3'>
                                    {{$user->employee->bank_name}}
                                </div>
                                <div class='col-md-3'>
                                    <small>Account Number</small>
                                </div>
                                <div class='col-md-3'>
                                    {{$user->employee->bank_account_number}}
                                </div>
                            </div>
                            <div class='row  m-2 border-bottom'>
                                <div class='col-md-3'>
                                    <small>Rate</small>
                                </div>
                                <div class='col-md-3'>
                                    <a href="#" data-toggle="modal" onclick='reset_data();' data-target="#rateData">********</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @endif 
            </div>
        </div> --}}
        <div class="row">
            <div class="col-3">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link active show" id="v-pills-personal-tab" data-toggle="pill" href="#v-pills-personal" role="tab" aria-controls="v-pills-personal" aria-selected="true"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;Personal Information</a>
                    <a class="nav-link" id="v-pills-employment-tab" data-toggle="pill" href="#v-pills-employment" role="tab" aria-controls="v-pills-employment" aria-selected="true"><i class="fa fa-user-plus" aria-hidden="true"></i>&nbsp;Employment Information</a>
                    <a class="nav-link" id="v-pills-schedule-tab" data-toggle="pill" href="#v-pills-schedule" role="tab" aria-controls="v-pills-schedule" aria-selected="false"><i class="fa fa-calendar" aria-hidden="true"></i>&nbsp;Schedule</a>
                    {{-- <a class="nav-link" id="v-pills-contact-tab" data-toggle="pill" href="#v-pills-contact" role="tab" aria-controls="v-pills-contact" aria-selected="false"><i class="fa fa-phone" aria-hidden="true"></i>&nbsp;Contact</a> --}}
                    <a class="nav-link" id="v-pills-beneficiaries-tab" data-toggle="pill" href="#v-pills-beneficiaries" role="tab" aria-controls="v-pills-beneficiaries" aria-selected="false"><i class="fa fa-users" aria-hidden="true"></i>&nbsp;Beneficiaries</a>
                    <a class="nav-link" id="v-pills-history-tab" data-toggle="pill" href="#v-pills-history" role="tab" aria-controls="v-pills-history" aria-selected="false"><i class="fa fa-history" aria-hidden="true"></i>&nbsp;History</a>
                    <a class="nav-link" id="v-pills-benefits-tab" data-toggle="pill" href="#v-pills-benefits" role="tab" aria-controls="v-pills-benefits" aria-selected="false"><i class="fa fa-star" aria-hidden="true"></i>&nbsp;Benefits</a>
                    <a class="nav-link" id="v-pills-bank-tab" data-toggle="pill" href="#v-pills-bank" role="tab" aria-controls="v-pills-bank" aria-selected="false"><i class="fa fa-bank" aria-hidden="true"></i>&nbsp;Bank Details</a>
                    <a class="nav-link" id="v-pills-policies-tab" data-toggle="pill" href="#v-pills-policies" role="tab" aria-controls="v-pills-policies" aria-selected="false"><i class="fa fa-shield" aria-hidden="true"></i>&nbsp;Conformity to Policies</a>
                    {{-- <a class="nav-link" id="v-pills-government-tab" data-toggle="pill" href="#v-pills-government" role="tab" aria-controls="v-pills-government" aria-selected="false"><i class="fa fa-files-o" aria-hidden="true"></i>&nbsp;Government Records and Licenses</a> --}}
                    <a class="nav-link" id="v-pills-training-tab" data-toggle="pill" href="#v-pills-training" role="tab" aria-controls="v-pills-training" aria-selected="false">
                      <i class="fa fa-book" aria-hidden="true"></i>
                      &nbsp;Training
                    </a>
                    <a class="nav-link" id="v-pills-nte-tab" data-toggle="pill" href="#v-pills-nte" role="tab" aria-controls="v-pills-nte" aria-selected="false">
                      <i class="fa fa-ban" aria-hidden="true"></i>
                      &nbsp;NTE Uploads
                    </a>
                    <a class="nav-link" id="v-employee-documents-tab" data-toggle="pill" href="#v-employee-documents" role="tab" aria-controls="v-employee-documents" aria-selected="false">
                      <i class="fa fa-file" aria-hidden="true"></i>
                      &nbsp;201 Files
                    </a>
                    <a class="nav-link" id="v-employee-org-chart-tab" data-toggle="pill" href="#v-employee-org-chart" role="tab" aria-controls="v-employee-org-chart" aria-selected="false">
                      <i class="fa fa-sitemap" aria-hidden="true"></i>
                      &nbsp;Org Chart
                    </a>
                </div>
            </div>
            <div class="col-9">
                <div class="tab-content tab-employee" style="border: 1px solid #CED4DA" id="v-pills-tabContent">
                    <div class="tab-pane fade active show" id="v-pills-personal" role="tabpanel" aria-labelledby="v-pills-personal-tab">
                        {{-- <div class="card">
                            <div class="template-demo">
                                <div class="template-demo">
                                    <div class='row m-2'>
                                        <div class='col-md-12 text-center mt-3 mb-3'>
                                            <img class="rounded-circle" style='width:120px;height:120px;' src='{{URL::asset($user->employee->avatar)}}' onerror="this.src='{{URL::asset('/images/no_image.png')}}';">
                                            <h3 class="card-text mt-3">{{$user->employee->first_name}} @if($user->employee->middle_initial != null){{$user->employee->middle_initial}}.@endif {{$user->employee->last_name}}</h3>
                                            <h4 class="card-text mt-2">{{$user->employee->position}}</h4>
                                            <h5 class="card-text mt-2">Biometric Code : {{$user->employee->employee_number}}</h5>
                                            @if($user->employee->signature)
                                                <img src='{{URL::asset($user->employee->signature)}}' onerror="this.src='{{URL::asset('/images/signature.png')}}';" height='80px;' width='250px'><br>
                                            @endif
                                            <button class="btn btn-primary btn-sm mt-3" data-toggle="modal" data-target="#uploadAvatar">
                                                Upload Avatar
                                            </button>
                                            <button class="btn btn-info btn-sm mt-3" data-toggle="modal" data-target="#uploadSignature">
                                                Upload Signature
                                            </button>
                                            <!-- <a class="btn btn-warning btn-sm mt-3" href='{{url("print-id/".$user->employee->id)}}' target="_blank">
                                                Print ID
                                            </a> -->
                                            <strong>
                                                <h3 class="mt-3">Personal Information
                                                    @if (checkUserPrivilege('employees_edit',auth()->user()->id) == 'yes')
                                                        <button class="btn btn-outline-primary btn-sm btn-icon-text" title="Edit Information" data-toggle="modal" data-target="#editInfo"><i class="fa fa-pencil"></i></button>
                                                    @endif
                                                </h3>
                                            </strong>
                                        </div>
                                    </div>
                                    <div class='row m-2 border-bottom'>
                                        <div class='col-md-3 '>
                                            <small>Nickname </small>
                                        </div>
                                        <div class='col-md-9'>
                                            {{$user->employee->nick_name}}
                                        </div>
                                    </div>
                                    <div class='row  m-2 border-bottom'>
                                        <div class='col-md-3'>
                                            <small> Full Name </small>
                                        </div>
                                        <div class='col-md-9'>
                                            {{$user->employee->first_name}} @if($user->employee->middle_initial != null){{$user->employee->middle_initial}}.@endif {{$user->employee->last_name}}
                                        </div>
                                    </div>
                                    <div class='row  m-2 border-bottom'>
                                        <div class='col-md-3'>
                                            <small> Email </small>
                                        </div>
                                        <div class='col-md-9'>
                                            {{$user->employee->personal_email}}
                                        </div>
                                    </div>
                                    <div class='row  m-2 border-bottom'>
                                        <div class='col-md-3'>
                                            <small> Phone</small>
                                        </div>
                                        <div class='col-md-9'>
                                            {{$user->employee->personal_number}}
                                        </div>
                                    </div>
                                    <div class='row  m-2 border-bottom'>
                                        <div class='col-md-3'>
                                            <small>Marital Status </small>
                                        </div>
                                        <div class='col-md-9'>
                                            {{$user->employee->marital_status}}
                                        </div>
                                    </div>
                                    <div class='row  m-2 border-bottom'>
                                        <div class='col-md-3'>
                                            <small>Religion </small>
                                        </div>
                                        <div class='col-md-3'>
                                            {{$user->employee->religion}}
                                        </div>
                                        <div class='col-md-3'>
                                            <small>Gender </small>
                                        </div>
                                        <div class='col-md-3'>
                                            {{$user->employee->gender}}
                                        </div>
                                    </div>
                                    <div class='row  m-2 border-bottom'>
                                        <div class='col-md-3'>
                                            <small>Address </small>
                                        </div>
                                        <div class='col-md-9'>
                                            <small> Present : {{$user->employee->present_address}} </small><br>
                                            <small> Permanent : {{$user->employee->permanent_address}} </small>
                                        </div>
                                    </div>
                                    <div class='row  m-2 border-bottom'>
                                        <div class='col-md-3'>
                                            <small>Birth </small>
                                        </div>
                                        <div class='col-md-9'>
                                            @php
                                            $d1 = new DateTime($user->employee->birth_date);
                                            $d2 = new DateTime();
                                            $diff = $d2->diff($d1);
                                            @endphp
                                            <small> Date : {{date('F d, Y',strtotime($user->employee->birth_date))}} : {{$diff->y}} Years Old</small><br>
                                            <small> Place : {{$user->employee->birth_place}} </small>
                                        </div>
                                    </div>
                                    <div class='row m-2'>
                                        <div class='col-md-12 text-center mt-3 mb-3'>
                                            <strong>
                                                <h3>Contact Person (In case of Emergency)
                                                    @if (checkUserPrivilege('employees_edit',auth()->user()->id) == 'yes')
                                                        <button class="btn btn-outline-primary btn-sm btn-icon-text" title="Edit Contact Person" data-toggle="modal" data-target="#editContactInfo"><i class="fa fa-pencil"></i></button>
                                                    @endif
                                                </h3>
                                            </strong>
                                        </div>
                                    </div>
                                    <div class='row m-2 border-bottom'>
                                        <div class='col-md-3'>
                                            <small> Contact Person </small>
                                        </div>
                                        <div class='col-md-9'>
                                            {{$user->employee->contact_person ? $user->employee->contact_person->name : ""}}
                                        </div>
                                    </div>
                                    <div class='row  m-2 border-bottom'>
                                        <div class='col-md-3'>
                                            <small> Contact Number </small>
                                        </div>
                                        <div class='col-md-9'>
                                            {{$user->employee->contact_person ? $user->employee->contact_person->contact_number : ""}}
                                        </div>
                                    </div>
                                    <div class='row  m-2 border-bottom'>
                                        <div class='col-md-3'>
                                            <small> Relation </small>
                                        </div>
                                        <div class='col-md-9'>
                                            {{$user->employee->contact_person ? $user->employee->contact_person->relation : ""}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <div class="card shadow-sm">
                            <div class="card-body text-center">
                                <img class="rounded-circle mb-3"
                                    style="width:120px;height:120px;object-fit:cover;"
                                    src="{{ URL::asset($user->employee->avatar) }}"
                                    onerror="this.src='{{ URL::asset('/images/no_image.png') }}';">
                                <h3 class="mb-0">
                                    {{ $user->employee->first_name }}
                                    @if($user->employee->middle_initial)
                                        {{ $user->employee->middle_initial }}.
                                    @endif
                                    {{ $user->employee->last_name }}
                                </h3>
                                <p class="text-muted mb-1">{{ $user->employee->position }}</p>
                                <span class="badge badge-primary p-2">
                                    Biometric Code: {{ $user->employee->employee_number }}
                                </span>
                                @if($user->employee->signature)
                                    <div class="mt-3">
                                        <img src="{{ URL::asset($user->employee->signature) }}"
                                            onerror="this.src='{{ URL::asset('/images/signature.png') }}';"
                                            style="height:60px;">
                                    </div>
                                @endif

                                <div class="mt-3">
                                    <button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#uploadAvatar">
                                        Upload Avatar
                                    </button>
                                    <button class="btn btn-outline-info btn-sm" data-toggle="modal" data-target="#uploadSignature">
                                        Upload Signature
                                    </button>
                                </div>
                            </div>
                            <hr>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">
                                        <i class="fa fa-user text-primary"></i>&nbsp;Personal Information
                                    </h5>
                                    @if (checkUserPrivilege('employees_edit',auth()->user()->id) == 'yes')
                                        <button class="btn btn-sm btn-outline-primary float-right" data-toggle="modal" data-target="#editInfo">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <small class="text-muted">Nickname</small>
                                        <div>{{ $user->employee->nick_name }}</div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <small class="text-muted">Personal Email</small>
                                        <div>{{ $user->employee->personal_email }}</div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <small class="text-muted">Phone</small>
                                        <div>{{ $user->employee->personal_number ?? '-' }}</div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <small class="text-muted">Marital Status</small>
                                        <div>
                                            <span class="badge badge-secondary">
                                                {{ $user->employee->marital_status }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <small class="text-muted">Religion</small>
                                        <div>{{ $user->employee->religion ?? '-' }}</div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <small class="text-muted">Gender</small>
                                        <div>{{ $user->employee->gender ?? '-' }}</div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <small class="text-muted">Address</small>
                                        <div>
                                            Present:&nbsp;{{ $user->employee->present_address }} <br>
                                            Permanent:&nbsp;{{ $user->employee->permanent_address }}
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <small class="text-muted">Birth Info</small>
                                        @php
                                            $d1 = new DateTime($user->employee->birth_date);
                                            $d2 = new DateTime();
                                            $diff = $d2->diff($d1);
                                        @endphp
                                        <div>
                                            {{ date('F d, Y', strtotime($user->employee->birth_date)) }}
                                            <span class="badge badge-info">{{ $diff->y }} yrs old</span><br>
                                            {{ $user->employee->birth_place }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">
                                        <i class="fa fa-phone text-success"></i>&nbsp;Contact Information
                                    </h5>
                                    @if (checkUserPrivilege('employees_edit',auth()->user()->id) == 'yes')
                                        <button class="btn btn-sm btn-outline-primary float-right" data-toggle="modal" data-target="#editContactInfo">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                    @endif
                                </div>
                                {{-- <h5 class="mb-3">
                                    <strong>Contact Information</strong>
                                    @if (checkUserPrivilege('employees_edit',auth()->user()->id) == 'yes')
                                        <button class="btn btn-sm btn-outline-primary float-right" data-toggle="modal" data-target="#editContactInfo">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                    @endif
                                </h5> --}}

                                @if($user->employee->contact_person)
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <small class="text-muted">Name</small>
                                            <div>{{ $user->employee->contact_person->name }}</div>
                                        </div>

                                        <div class="col-md-6 mb-2">
                                            <small class="text-muted">Contact Number</small>
                                            <div>{{ $user->employee->contact_person->contact_number }}</div>
                                        </div>

                                        <div class="col-md-12 mb-2">
                                            <small class="text-muted">Relation</small>
                                            <div>
                                                <span class="badge badge-warning">
                                                    {{ $user->employee->contact_person->relation }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-muted">No contact person assigned</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="v-pills-employment" role="tabpanel" aria-labelledby="v-pills-employment-tab">
                        {{-- <div class="card">
                            <div class="template-demo">
                                <div class='row m-2'>
                                    <div class='col-md-12 text-center mt-3 mb-3'>
                                        <strong>
                                            <h3>Employment Information 
                                                @if (checkUserPrivilege('employees_edit',auth()->user()->id) == 'yes')
                                                    <button class="btn btn-outline-primary btn-sm btn-icon-text" title="Edit Employee Information" data-toggle="modal" data-target="#editEmpInfo"><i class="fa fa-pencil"></i></button>
                                                    <button class="btn btn-outline-primary btn-sm btn-icon-text" title="Notice of Personnel Action" data-toggle="modal" data-target="#createNopa"><i class="fa fa-pencil-square-o"></i></button>
                                                    <button class="btn btn-outline-primary btn-sm btn-icon-text" title="Edit employee no" data-toggle="modal" data-target="#editEmpNo">
                                                        <i class="fa fa-id-card-o"></i>
                                                    </button>
                                                @endif
                                            </h3>
                                        </strong>
                                    </div>
                                </div>
                                <div class='row  m-2 border-bottom'>
                                    <div class='col-md-3'>
                                        <small> Email </small>
                                    </div>
                                    <div class='col-md-9'>
                                        {{$user->email}}
                                    </div>
                                </div>
                                <div class='row m-2 border-bottom'>
                                    <div class='col-md-3 '>
                                        <small>Company </small>
                                    </div>
                                    <div class='col-md-9'>
                                        @if($user->employee->company) {{$user->employee->company->company_name}} @endif
                                    </div>
                                </div>
                                <div class='row m-2 border-bottom'>
                                    <div class='col-md-3 '>
                                        <small>Deparment </small>
                                    </div>
                                    <div class='col-md-9'>
                                        @if($user->employee->department) {{$user->employee->department->name}} @endif
                                    </div>
                                </div>
                                <div class='row m-2 border-bottom'>
                                    <div class='col-md-3 '>
                                        <small>Location </small>
                                    </div>
                                    <div class='col-md-9'>
                                        @if($user->employee->location) {{$user->employee->location}} @endif
                                    </div>
                                </div>
                                <div class='row  m-2 border-bottom'>
                                    <div class='col-md-3'>
                                        <small> Classification </small>
                                    </div>
                                    <div class='col-md-9'>
                                        {{$user->employee->classification_info ? $user->employee->classification_info->name : ""}}
                                    </div>
                                </div>
                                <div class='row  m-2 border-bottom'>
                                    <div class='col-md-3'>
                                        <small> Level </small>
                                    </div>
                                    <div class='col-md-9'>
                                        {{$user->employee->level_info ? $user->employee->level_info->name : "" }}
                                    </div>
                                </div>
                                <div class='row  m-2 border-bottom'>
                                    <div class='col-md-3'>
                                        <small> Date Hired </small>
                                    </div>
                                    <div class='col-md-3'>
                                        {{date('M d, Y',strtotime($user->employee->original_date_hired))}}
                                    </div>
                                    <div class='col-md-6'>
                                        @php
                                        $date_from = new DateTime($user->employee->original_date_hired);
                                        $date_diff = $date_from->diff(new DateTime(date('Y-m-d')));
                                        @endphp
                                        {{$date_diff->format('%y Year %m months %d days')}}
                                    </div>
                                </div>
                            </div>
                            @if (checkUserPrivilege('payroll_view',auth()->user()->id) == 'yes')
                            <div class="template-demo">
                                <div class='row m-2'>
                                    <div class='col-md-12 text-center mt-3 mb-3'>
                                        <strong>
                                            <h3>Salary Information 
                                                @if (checkUserPrivilege('employees_edit',auth()->user()->id) == 'yes')
                                                @if (empty($user->employee->employee_salary))
                                                    <button class="btn btn-outline-primary btn-sm btn-icon-text" title="Employee Salary" data-toggle="modal" data-target="#createEmpSalary">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                @endif
                                                    <button class="btn btn-outline-primary btn-sm btn-icon-text" title="Salary Notice of Personnel Action" data-toggle="modal" data-target="#createSalaryNopa"><i class="fa fa-pencil-square-o"></i></button>
                                                @endif
                                            </h3>
                                        </strong>
                                    </div>
                                </div>
                                <div class='row  m-2 border-bottom'>
                                    <div class='col-md-3'>
                                        <small> Basic Salary </small>
                                    </div>
                                    <div class='col-md-9'>
                                        {{ $user->employee->employee_salary ? $user->employee->employee_salary->basic_salary : ""}}
                                    </div>
                                </div>
                                <div class='row m-2 border-bottom'>
                                    <div class='col-md-3 '>
                                        <small>De Minimis</small>
                                    </div>
                                    <div class='col-md-9'>
                                        {{ $user->employee->employee_salary ? $user->employee->employee_salary->de_minimis : ""}}
                                    </div>
                                </div>
                                <div class='row m-2 border-bottom'>
                                    <div class='col-md-3 '>
                                        <small>Other Allowances </small>
                                    </div>
                                    <div class='col-md-9'>
                                        {{ $user->employee->employee_salary ? $user->employee->employee_salary->other_allowance : ""}}
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="template-demo">
                                <div class='row m-2'>
                                    <div class='col-md-12 text-center mt-3 mb-3'>
                                        <strong>
                                            <h3>Government Records and Licenses</h3>
                                        </strong>
                                    </div>
                                </div>
                                <div class='row  m-2 border-bottom'>
                                    <div class='col-md-3'>
                                        <small>SSS</small>
                                    </div>
                                    <div class='col-md-3'>
                                        <small>{{$user->employee->sss_number}}</small>
                                    </div>
                                    <div class='col-md-3'>
                                        <small>HDMF</small>
                                    </div>
                                    <div class='col-md-3'>
                                        <small>{{$user->employee->hdmf_number}}</small>
                                    </div>
                                </div>
                                <div class='row  m-2 border-bottom'>
                                    <div class='col-md-3'>
                                        <small>PHILHEALTH</small>
                                    </div>
                                    <div class='col-md-3'>
                                        <small>{{$user->employee->phil_number}}</small>
                                    </div>
                                    <div class='col-md-3'>
                                        <small>TIN</small>
                                    </div>
                                    <div class='col-md-3'>
                                        <small>{{$user->employee->tax_number}}</small>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <div class="card shadow-sm">
                            <div class="card-body border-bottom">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">
                                        <i class="fa fa-briefcase text-primary"></i> Employment Information
                                    </h5>
                                    @if (checkUserPrivilege('employees_edit',auth()->user()->id) == 'yes')
                                        <div>
                                            <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#editEmpInfo">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-info" data-toggle="modal" data-target="#createNopa">
                                                <i class="fa fa-file-text"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#editEmpNo">
                                                <i class="fa fa-id-card"></i>
                                            </button>
                                        </div>
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <small class="text-muted">Work Email</small>
                                        <div>{{ $user->email }}</div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <small class="text-muted">Company</small>
                                        <div>{{ optional($user->employee->company)->company_name }}</div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <small class="text-muted">Department</small>
                                        <div>{{ optional($user->employee->department)->name }}</div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <small class="text-muted">Location</small>
                                        <div>{{ $user->employee->location }}</div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <small class="text-muted">Classification</small>
                                        <div>
                                            <span class="badge badge-info">
                                                {{ optional($user->employee->classification_info)->name }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <small class="text-muted">Level</small>
                                        <div>
                                            <span class="badge badge-secondary">
                                                {{ optional($user->employee->level_info)->name }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <small class="text-muted">Date Hired</small>
                                        @php
                                            $date_from = new DateTime($user->employee->original_date_hired);
                                            $date_diff = $date_from->diff(new DateTime());
                                        @endphp
                                        <div>
                                            {{ date('F d, Y', strtotime($user->employee->original_date_hired)) }}
                                            <span class="badge badge-success ml-2">
                                                {{ $date_diff->y }}y {{ $date_diff->m }}m {{ $date_diff->d }}d
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if (checkUserPrivilege('payroll_view',auth()->user()->id) == 'yes')
                            <div class="card-body border-bottom">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">
                                        <i class="fa fa-money text-success"></i>&nbsp;Salary Information
                                    </h5>

                                    @if (checkUserPrivilege('employees_edit',auth()->user()->id) == 'yes')
                                        <div>
                                            @if (empty($user->employee->employee_salary))
                                                <button class="btn btn-sm btn-outline-success" data-toggle="modal" data-target="#createEmpSalary">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            @endif
                                            <button class="btn btn-sm btn-outline-info" data-toggle="modal" data-target="#createSalaryNopa">
                                                <i class="fa fa-file-text"></i>
                                            </button>
                                        </div>
                                    @endif
                                </div>

                                @php $salary = $user->employee->employee_salary; @endphp

                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <small class="text-muted">Basic Salary</small>
                                        <div class="font-weight-bold text-success">
                                            ₱ {{ number_format(optional($salary)->basic_salary, 2) }}
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <small class="text-muted">De Minimis</small>
                                        <div>₱ {{ number_format(optional($salary)->de_minimis, 2) }}</div>
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <small class="text-muted">Other Allowances</small>
                                        <div>₱ {{ number_format(optional($salary)->other_allowance, 2) }}</div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="card-body">
                                <h5 class="mb-3">
                                    <i class="fa fa-id-card text-warning"></i> Government Records
                                </h5>

                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <small class="text-muted">SSS</small>
                                        <div>{{ $user->employee->sss_number }}</div>
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <small class="text-muted">HDMF (Pag-IBIG)</small>
                                        <div>{{ $user->employee->hdmf_number }}</div>
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <small class="text-muted">PhilHealth</small>
                                        <div>{{ $user->employee->phil_number }}</div>
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <small class="text-muted">TIN</small>
                                        <div>{{ $user->employee->tax_number }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="v-pills-schedule" role="tabpanel" aria-labelledby="v-pills-schedule-tab">
                        {{-- <div class="card">
                            <div class="template-demo">
                                <div class='row'>
                                    <div class='col-md-12 text-center mt-3 mb-3'>
                                        <strong>
                                            <h3>Schedule</h3>
                                        </strong>
                                    </div>
                                </div>
                                <div class='row  m-2 border-bottom'>
                                    <div class='col-md-3'>
                                    </div>
                                    <div class='col-md-3'>
                                        <small>Start Time</small>
                                    </div>
                                    <div class='col-md-3'>
                                        <small>End Time</small>
                                    </div>
                                    <div class='col-md-3'>
                                        <small>Total hours</small>
                                    </div>
                                </div>
                                @foreach($user->employee->ScheduleData as $schedule)
                                <div class='row  m-2 border-bottom'>
                                    <div class='col-md-3'>
                                        <small>{{$schedule->name}}</small>
                                    </div>
                                    <div class='col-md-3'>
                                        <small>{{$schedule->time_in_from}}</small> <br>
                                        <small>{{$schedule->time_in_to}}</small>

                                    </div>
                                    <div class='col-md-3'>
                                        <small>{{$schedule->time_out_from}}</small> <br>
                                        <small>{{$schedule->time_out_to}}</small>
                                    </div>
                                    <div class='col-md-3'>
                                        <small>{{number_format($schedule->working_hours,1)}} </small>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div> --}}
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-primary text-white text-center">
                                <h5 class="mb-0">
                                    <i class="fa fa-calendar"></i>&nbsp;Daily Schedule
                                </h5>
                            </div>
                            <div class="card-body">
                                @php
                                    $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];

                                    $scheduleMap = collect($user->employee->ScheduleData)->keyBy('name');
                                @endphp
                                <div class="row text-center">
                                    @foreach($days as $day)
                                        @php
                                            $sched = $scheduleMap->get($day);
                                        @endphp
                                        <div class="col border p-2 calendar-day">
                                            <strong>{{ $day }}</strong>
                                            <hr class="my-1">
                                            @if($sched)
                                                <div class="schedule-box bg-light p-2 rounded">

                                                    <div class="text-success">
                                                        <i class="fa fa-sign-in"></i>
                                                        {{ date('h:i A', strtotime($sched->time_in_from)) }}
                                                    </div>

                                                    <div class="text-danger">
                                                        <i class="fa fa-sign-out"></i>
                                                        {{ date('h:i A', strtotime($sched->time_out_from)) }}
                                                    </div>

                                                    <div class="mt-1">
                                                        <span class="badge badge-info">
                                                            {{ number_format($sched->working_hours,1) }} hrs
                                                        </span>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="text-muted small mt-3">
                                                    No Schedule
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="tab-pane fade" id="v-pills-contact" role="tabpanel" aria-labelledby="v-pills-contact-tab">
                        <div class="card">
                            <div class="template-demo">
                                <div class='row m-2'>
                                    <div class='col-md-12 text-center mt-3 mb-3'>
                                        <strong>
                                            <h3>Contact Person (In case of Emergency)
                                                @if (checkUserPrivilege('employees_edit',auth()->user()->id) == 'yes')
                                                    <button class="btn btn-outline-primary btn-sm btn-icon-text" title="Edit Contact Person" data-toggle="modal" data-target="#editContactInfo"><i class="fa fa-pencil"></i></button>
                                                @endif
                                            </h3>
                                        </strong>
                                    </div>
                                </div>
                                <div class='row m-2 border-bottom'>
                                    <div class='col-md-3'>
                                        <small> Contact Person </small>
                                    </div>
                                    <div class='col-md-9'>
                                        {{$user->employee->contact_person ? $user->employee->contact_person->name : ""}}
                                    </div>
                                </div>
                                <div class='row  m-2 border-bottom'>
                                    <div class='col-md-3'>
                                        <small> Contact Number </small>
                                    </div>
                                    <div class='col-md-9'>
                                        {{$user->employee->contact_person ? $user->employee->contact_person->contact_number : ""}}
                                    </div>
                                </div>
                                <div class='row  m-2 border-bottom'>
                                    <div class='col-md-3'>
                                        <small> Relation </small>
                                    </div>
                                    <div class='col-md-9'>
                                        {{$user->employee->contact_person ? $user->employee->contact_person->relation : ""}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="tab-pane fade" id="v-pills-beneficiaries" role="tabpanel" aria-labelledby="v-pills-beneficiaries-tab">
                        <div class="card">
                            <div class="template-demo">
                                <div class='row m-2'>
                                    <div class='col-md-12 text-center mt-3 mb-3'>
                                        <strong>
                                            <h3>Beneficiaries
                                                @if (checkUserPrivilege('employees_edit',auth()->user()->id) == 'yes')
                                                    <button class="btn btn-outline-primary btn-sm btn-icon-text" title="Edit Beneficiaries" data-toggle="modal" data-target="#editBeneficiaries"><i class="fa fa-pencil"></i></button>
                                                @endif
                                            </h3>
                                        </strong>
                                    </div>
                                </div>
                                
                                @foreach($user->employee->beneficiaries as $key => $value)
                                <div class='row  m-2 border-bottom'>
                                    <div class='col-md-3'>
                                        <small>{{$value->relation}}</small>
                                    </div>
                                    <div class='col-md-9'>
                                        <small>{{$value->first_name . ' ' . $value->last_name}}</small>
                                    </div>
                                </div>
                                @endforeach                            
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="v-pills-history" role="tabpanel" aria-labelledby="v-pills-contact-tab">
                        <div class="card">
                            <div class="template-demo">
                                <div class='row m-2'>
                                    <div class='col-md-12 text-center mt-3 mb-3'>
                                        <strong>
                                            <h3>Notice of Personnel Action
                                            </h3>
                                        </strong>
                                    </div>
                                </div>
                                <div class='row  m-2 border-bottom'>
                                    <div class='col-md-3'>
                                        <small> Changed By: </small>
                                    </div>
                                    <div class='col-md-3'>
                                        <small> Changed At: </small>
                                    </div>
                                    <div class='col-md-3'>
                                        <small>View Changes</small>
                                    </div>
                                    <div class='col-md-3'>
                                        <small>Attachment</small>
                                    </div>
                                </div>
                                @foreach ($user->employee->employeeMovement as $movement)
                                <div class='row m-2 border-bottom'>
                                    <div class='col-md-3'>
                                        {{ optional($movement->user_info)->name ?? 'N/A' }}
                                    </div>
                                    <div class='col-md-3'>
                                        {{date('M d, Y',strtotime($movement->changed_at ))}}
                                    </div>
                                    <div class='col-md-3'>
                                        <a href='#' data-toggle="modal" data-target="#viewNopa{{$movement->id}}">View</a>
                                    </div>
                                    <div class='col-md-3'>
                                        @if ($movement->nopa_attachment)
                                        <a href="{{ url($movement->nopa_attachment) }}" target="_blank">Attachment</a>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @if (checkUserPrivilege('payroll_view',auth()->user()->id) == 'yes')
                        <div class="card">
                            <div class="template-demo">
                                <div class='row m-2'>
                                    <div class='col-md-12 text-center mt-3 mb-3'>
                                        <strong>
                                            <h3>Salary Notice of Personnel Action
                                            </h3>
                                        </strong>
                                    </div>
                                </div>
                                <div class='row  m-2 border-bottom'>
                                    <div class='col-md-3'>
                                        <small> Changed By: </small>
                                    </div>
                                    <div class='col-md-3'>
                                        <small> Changed At: </small>
                                    </div>
                                    <div class='col-md-3'>
                                        <small>View Changes</small>
                                    </div>
                                    <div class='col-md-3'>
                                        <small>Attachment</small>
                                    </div>
                                </div>
                                @foreach ($user->employee->salaryMovement as $movement)
                                <div class='row m-2 border-bottom'>
                                    <div class='col-md-3'>
                                        {{ optional($movement->change_by)->name ?? 'N/A' }}
                                    </div>
                                    <div class='col-md-3'>
                                        {{date('M d, Y',strtotime($movement->changed_at ))}}
                                    </div>
                                    <div class='col-md-3'>
                                        <a href='#' data-toggle="modal" data-target="#viewSalaryNopa{{$movement->id}}">View</a>
                                    </div>
                                    <div class='col-md-3'>
                                        @if ($movement->salary_nopa_attachment)
                                        <a href="{{ url($movement->salary_nopa_attachment) }}" target="_blank">Attachment</a>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="tab-pane fade" id="v-pills-bank" role="tabpanel" aria-labelledby="v-pills-bank-tab">
                        <div class="card">
                            <div class="template-demo">
                                <div class='row m-2'>
                                    <div class='col-md-12 text-center mt-3 mb-3'>
                                        <strong>
                                            <h3>Bank Details
                                                <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#editAcctNo">
                                                    <i class="fa fa-pencil"></i>
                                                </button>
                                            </h3>
                                        </strong>
                                    </div>
                                </div>
                                <div class='row  m-2 border-bottom'>
                                    <div class='col-md-3'>
                                        <small> BANK NAME </small>
                                    </div>
                                    <div class='col-md-9'>
                                        {{$user->employee->bank_name}}
                                    </div>
                                </div>
                                <div class='row  m-2 border-bottom'>
                                    <div class='col-md-3'>
                                        <small> ACCOUNT NUMBER </small>
                                    </div>
                                    <div class='col-md-9'>
                                        {{$user->employee->bank_account_number}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="v-pills-benefits" role="tabpanel" aria-labelledby="v-pills-benefits">
                        <div class="card p-5">
                          <div class="template-demo">
                            <div class='row m-2'>
                                <div class='col-md-12 text-center mt-3 mb-3'>
                                    <strong>
                                        <h3>Employee Benefits
                                          <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#addEmployeeBenefitsModal">
                                            <i class="fa fa-plus"></i>
                                          </button>
                                        </h3>
                                    </strong>
                                </div>
                            </div>
                            <div class="table-responsive">
                              <table class="table table-hover table-bordered tablewithSearch">
                                <thead>
                                  <tr>
                                    <th>Employee Name</th>
                                    <th>Benefits</th>
                                    <th>Amount</th>
                                    <th>Date Posted</th>
                                    <th>Posted By</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  @foreach ($employeeBenefits as $eb)
                                    <tr>
                                      <td>{{$eb->user->name}}</td>
                                      <td>
                                        @switch($eb->benefits_name)
                                            @case('SL')
                                              Salary Loan
                                                @break
                                            @case('EA')
                                              Educational Assistance
                                                @break
                                            @case('WG')
                                              Wedding Gift
                                                @break
                                            @case('BA')
                                              Bereavement Assistance
                                                @break
                                            @case('HMO')
                                              Health Card (HMO)
                                                @break
                                            @default
                                        @endswitch
                                      </td>
                                      <td><span>&#8369;</span>{{$eb->amount}}</td>
                                      <td>{{date('M. d, Y', strtotime($eb->date))}}</td>
                                      <td>{{$eb->postedBy->name}}</td>
                                    </tr>
                                  @endforeach
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                    </div>
                    {{-- <div class="tab-pane fade" id="v-pills-government" role="tabpanel" aria-labelledby="v-pills-government-tab">
                        <div class="card">
                            <div class="template-demo">
                                <div class='row m-2'>
                                    <div class='col-md-12 text-center mt-3 mb-3'>
                                        <strong>
                                            <h3>Government Records and Licenses</h3>
                                        </strong>
                                    </div>
                                </div>
                                <div class='row  m-2 border-bottom'>
                                    <div class='col-md-3'>
                                        <small>SSS</small>
                                    </div>
                                    <div class='col-md-3'>
                                        <small>{{$user->employee->sss_number}}</small>
                                    </div>
                                    <div class='col-md-3'>
                                        <small>HDMF</small>
                                    </div>
                                    <div class='col-md-3'>
                                        <small>{{$user->employee->hdmf_number}}</small>
                                    </div>
                                </div>
                                <div class='row  m-2 border-bottom'>
                                    <div class='col-md-3'>
                                        <small>PHILHEALTH</small>
                                    </div>
                                    <div class='col-md-3'>
                                        <small>{{$user->employee->phil_number}}</small>
                                    </div>
                                    <div class='col-md-3'>
                                        <small>TIN</small>
                                    </div>
                                    <div class='col-md-3'>
                                        <small>{{$user->employee->tax_number}}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="tab-pane fade" id="v-pills-policies" role="tabpanel" aria-labelledby="v-pills-policies-tab">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-primary text-white text-center">
                                <h5 class="mb-0">
                                    <i class="fa fa-shield"></i> Conformity to Policies
                                </h5>
                            </div>
                            <div class="card-body">
                                <div id="policyAccordion">
                                    <div class="card mb-2">
                                        <div class="card-header bg-light" id="headingDABP">
                                            <h6 class="mb-0 d-flex justify-content-between align-items-center">
                                                <button class="btn btn-link text-left w-100" data-toggle="collapse" data-target="#collapseDABP">
                                                    <b>Drug and Alcohol Abuse Policy</b>
                                                </button>
                                                @if(!empty($user->dabp))
                                                    <span class="badge badge-success">Completed</span>
                                                @else
                                                    <span class="badge badge-warning">Pending</span>
                                                @endif
                                            </h6>
                                        </div>

                                        <div id="collapseDABP" class="collapse show" data-parent="#policyAccordion">
                                            <div class="card-body">
                                                <p><strong>Pascal Resources Energy, Inc.</strong> is committed to a policy which involves its employees a working environment wherein safety is assured. While the Company has no intention of intruding into the private lives of its employees, it expects its employees to understand that the use of illegal drugs on or off the job has an impact on safety and performance which interferes with the Company's objectives of providing a safe working environment.</p>
                                                <p>Pursuant to this objective, the Company has established this DRUG AND ALCOHOL ABUSE POLICY, which requires in essence, all employees to report for work drug and alcohol-free.</p>
                                                <p>Employees are required to strictly abide to the guidelines listed below. In the event that any employee is found violating any of these guidelines, appropriate disciplinary action as prescribed in the Company's Employee Handbook, including suspension and termination, will be imposed.</p>
                                                <p>Pursuant to this objective, the Company has established this DRUG AND ALCOHOL ABUSE POLICY, which requires in essence, all employees to report for work drug and alcohol-free.</p>
                                                <p>Employees are required to strictly abide to the guidelines listed below. In the event that any employee is found violating any of these guidelines, appropriate disciplinary action as prescribed in the Company's Employee Handbook, including suspension and termination, will be imposed.</p>
                                                <p>1. All Employees are strictly prohibited to use, sell, or possess alcohol or illegal and/or regulated drugs in the Company premises or while in the performance of their respective duties. The prohibition is likewise applicable during Company-related and/or sponsored activity such as, but not limited to, sports and recreational events, excursions and parties.</p>
                                                <p>2. Any employees found to be under the influence of illegal drugs or alcohol shall be ordered to leave the Company premises immediately, or desist from continuing in the performance of his functions, in case he is outside the Company premises.</p>
                                                <p>3. Where appropriate, testing will be conducted to determine the presence of illegal drugs and alcohol use.</p>
                                                <p>4. The Company reserves the right to conduct inspections, searches, and seizures of an employee or his personal belongings when on the job or in other Company premises when appropriate under the circumstances. This shall be done as a means of enforcing the provision.</p>
                                                <p>5. In the event that any visitor or employee of other companies doing business with the Company is found to be in violation of this policy, he will be refused entry or immediately removed from the Company premises.</p>
                                                <hr>
                                                <p class="text-center"><b>ACKNOWLEDGEMENT - DABP</b></p>
                                                <p>I hereby acknowledge having received, read and understood the Company's "DRUG AND ALCOHOL ABUSE POLICY." I am aware that any violation on my part of any of the provision as stated in the DRUG AND ALCOHOL ABUSE POLICY may subject me to disciplinary action, which can include suspension or termination of employment, as prescribed in our Employee Handbook.</p>
                                                <div class="col-md-12">
                                                    {{-- <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="dabp"
                                                            value="Yes, I understand and agree on this."
                                                            {{ $user->dabp == 'Yes, I understand and agree on this.' ? 'checked' : '' }}>
                                                        <label class="form-check-label text-success">
                                                            ✔ Agree
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="dabp"
                                                            value="No, I understand it but doesn't agree on this."
                                                            {{ $user->dabp == "No, I understand it but doesn't agree on this." ? 'checked' : '' }}>
                                                        <label class="form-check-label text-danger">
                                                            ✖ Disagree
                                                        </label>
                                                    </div> --}}
                                                    <form class="policy-form" data-id="{{ $user->id }}" enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="type" value="dabp">

                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="dabp"
                                                                value="Yes, I understand and agree on this."
                                                                {{ $user->dabp == 'Yes, I understand and agree on this.' ? 'checked' : '' }}>
                                                            <label class="form-check-label text-success">✔&nbsp;Yes, I understand and agree on this.</label>
                                                        </div>

                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="dabp"
                                                                value="No, I understand it but doesn't agree on this."
                                                                {{ $user->dabp == "No, I understand it but doesn't agree on this." ? 'checked' : '' }}>
                                                            <label class="form-check-label text-danger">✖&nbsp;No, I understand it but doesn't agree on this.</label>
                                                        </div>
                                                        @if($user->dabp == "No, I understand it but doesn't agree on this.")
                                                            <input type="file" name="attachment" class="form-control mt-2">
                                                        @endif
                                                        @if($user->dabp_attachment)
                                                            <a href="{{ asset('storage/'.$user->dabp_attachment) }}" class="mt-2" target="_blank">
                                                                View Attachment
                                                            </a>
                                                        @endif
                                                        @if($user->dabp == "No, I understand it but doesn't agree on this.")
                                                            <div align="right">
                                                                <button type="submit" class="btn btn-primary mt-2">Update</button>
                                                            </div>
                                                        @endif
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-2">
                                        <div class="card-header bg-light" id="headingATKP">
                                            <h6 class="mb-0 d-flex justify-content-between align-items-center">
                                                <button class="btn btn-link text-left w-100" data-toggle="collapse" data-target="#collapseATKP">
                                                    <b>Attendance & Timekeeping Policies and Procedures</b>
                                                </button>
                                                @if(!empty($user->atkp))
                                                    <span class="badge badge-success">Completed</span>
                                                @else
                                                    <span class="badge badge-warning">Pending</span>
                                                @endif
                                            </h6>
                                        </div>

                                        <div id="collapseATKP" class="collapse" data-parent="#policyAccordion">
                                            <div class="card-body">
                                                <p>The Company has explained this in detail during the New Employee Orientation which I am in attendance.</p>
                                                <p>I was given opportunity to ask question to clarify my quries and I know whom to contact for any further clarification I might have in the future.</p>
                                                <hr>
                                                <p class="text-center"><b>ACKNOWLEDGEMENT - ATKP</b></p>
                                                <p>I hereby acknowledge having received, read and understood the Company's "ATTENDANCE AND TIMEKEEPING POLICY & PROCEUDRES." I am aware that any violation on my part of any of the provision as stated in the said policy may subject me to disciplinary action, which can include suspension or termination of employment, as prescribed in our Employee Handbook.</p>
                                                <div class="col-md-12">
                                                    {{-- <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="atkp"
                                                            value="Yes, I understand and agree on this."
                                                            {{ $user->atkp == 'Yes, I understand and agree on this.' ? 'checked' : '' }}>
                                                        <label class="form-check-label text-success">✔ Agree</label>
                                                    </div>

                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="atkp"
                                                            value="No, I understand it but doesn't agree on this."
                                                            {{ $user->atkp == "No, I understand it but doesn't agree on this." ? 'checked' : '' }}>
                                                        <label class="form-check-label text-danger">✖ Disagree</label>
                                                    </div> --}}
                                                    <form class="policy-form" data-id="{{ $user->id }}" enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="type" value="atkp">

                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="atkp"
                                                                value="Yes, I understand and agree on this."
                                                                {{ $user->atkp == 'Yes, I understand and agree on this.' ? 'checked' : '' }}>
                                                            <label class="form-check-label text-success">✔&nbsp;Yes, I understand and agree on this.</label>
                                                        </div>

                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="atkp"
                                                                value="No, I understand it but doesn't agree on this."
                                                                {{ $user->atkp == "No, I understand it but doesn't agree on this." ? 'checked' : '' }}>
                                                            <label class="form-check-label text-danger">✖&nbsp;No, I understand it but doesn't agree on this.</label>
                                                        </div>
                                                        @if($user->atkp == "No, I understand it but doesn't agree on this.")
                                                            <input type="file" name="attachment" class="form-control mt-2">
                                                        @endif
                                                        @if($user->atkp_attachment)
                                                            <a href="{{ asset('storage/'.$user->atkp_attachment) }}" class="mt-2" target="_blank">
                                                                View Attachment
                                                            </a>
                                                        @endif
                                                        @if($user->atkp == "No, I understand it but doesn't agree on this.")
                                                            <div align="right">
                                                                <button type="submit" class="btn btn-primary mt-2">Update</button>
                                                            </div>
                                                        @endif
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-2">
                                        <div class="card-header bg-light" id="headingCOC">
                                            <h6 class="mb-0 d-flex justify-content-between align-items-center">
                                                <button class="btn btn-link text-left w-100" data-toggle="collapse" data-target="#collapseCOC">
                                                    <b>Code of Conduct</b>
                                                </button>
                                                @if(!empty($user->coc))
                                                    <span class="badge badge-success">Completed</span>
                                                @else
                                                    <span class="badge badge-warning">Pending</span>
                                                @endif
                                            </h6>
                                        </div>

                                        <div id="collapseCOC" class="collapse" data-parent="#policyAccordion">
                                            <div class="card-body">
                                                <p>The Company has explained this in detail during the New Employee Orientation which I am in attendance.</p>
                                                <p>I was given opportunity to ask question to clarify my quries and I know whom to contact for any further clarification I might have in the future.</p>
                                                <hr>
                                                <p class="text-center"><b>ACKNOWLEDGEMENT - COC</b></p>
                                                <p>I hereby acknowledge having received, read and understood the Company's "CODE OF CONDUCT". I am aware that have the right to access our Company's Employee Handbook.</p>
                                                <div class="col-md-12">
                                                    {{-- <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="coc"
                                                            value="Yes, I understand and agree on this."
                                                            {{ $user->coc == 'Yes, I understand and agree on this.' ? 'checked' : '' }}>
                                                        <label class="form-check-label text-success">✔ Agree</label>
                                                    </div>

                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="coc"
                                                            value="No, I understand it but doesn't agree on this."
                                                            {{ $user->coc == "No, I understand it but doesn't agree on this." ? 'checked' : '' }}>
                                                        <label class="form-check-label text-danger">✖ Disagree</label>
                                                    </div> --}}
                                                    <form class="policy-form" data-id="{{ $user->id }}" enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="type" value="coc">

                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="coc"
                                                                value="Yes, I understand and agree on this."
                                                                {{ $user->coc == 'Yes, I understand and agree on this.' ? 'checked' : '' }}>
                                                            <label class="form-check-label text-success">✔&nbsp;Yes, I understand and agree on this.</label>
                                                        </div>

                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="coc"
                                                                value="No, I understand it but doesn't agree on this."
                                                                {{ $user->coc == "No, I understand it but doesn't agree on this." ? 'checked' : '' }}>
                                                            <label class="form-check-label text-danger">✖&nbsp;No, I understand it but doesn't agree on this.</label>
                                                        </div>
                                                        @if($user->coc == "No, I understand it but doesn't agree on this.")
                                                            <input type="file" name="attachment" class="form-control mt-2">
                                                        @endif
                                                        @if($user->coc_attachment)
                                                            <a href="{{ asset('storage/'.$user->coc_attachment) }}" class="mt-2" target="_blank">
                                                                View Attachment
                                                            </a>
                                                        @endif
                                                        @if($user->coc == "No, I understand it but doesn't agree on this.")
                                                            <div align="right">
                                                                <button type="submit" class="btn btn-primary mt-2">Update</button>
                                                            </div>
                                                        @endif
                                                    </form>
                                                </div>
                                                @if($user->consent_signature)
                                                    @php
                                                        $signatureValue = $user->consent_signature;
                                                        try {
                                                            $signatureValue = Crypt::decryptString($user->consent_signature);
                                                        } catch (\Throwable $e) {
                                                            try {
                                                                $signatureValue = Crypt::decrypt($user->consent_signature);
                                                            } catch (\Throwable $e) {
                                                                // keep raw value
                                                            }
                                                        }

                                                        $signatureSrc = null;
                                                        if (!empty($signatureValue)) {
                                                            $signatureValue = trim($signatureValue);
                                                            if (strpos($signatureValue, 'data:image') === 0) {
                                                                $signatureSrc = $signatureValue;
                                                            } elseif (base64_decode($signatureValue, true) !== false) {
                                                                $signatureSrc = 'data:image/png;base64,' . $signatureValue;
                                                            }
                                                        }
                                                    @endphp

                                                    @if(!empty($signatureSrc))
                                                        <hr>
                                                        <div class="mt-4">
                                                            <h6 class="mb-3">
                                                                <i class="fa fa-pen-fancy text-info"></i>&nbsp;<strong>Digital Signature</strong>
                                                                <span class="badge badge-success float-right">
                                                                    <i class="fa fa-check-circle"></i>&nbsp;Signed
                                                                </span>
                                                            </h6>
                                                            <div class="border rounded p-3 bg-light text-center">
                                                                <img src="{{ $signatureSrc }}"
                                                                    alt="Signature"
                                                                    class="img-fluid"
                                                                    style="max-height: 120px; margin: 0 auto;">
                                                                <p class="text-muted small mt-2 mb-0">
                                                                    Signed on {{ $user->signed_date ? \Carbon\Carbon::parse($user->signed_date)->format('F d, Y') : 'Unknown' }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @else
                                                    <hr>
                                                    <div class="mt-4">
                                                        <h6 class="mb-3">
                                                            <i class="fa fa-pen-fancy text-secondary"></i>&nbsp;<strong>Digital Signature</strong>
                                                            <span class="badge badge-warning float-right">
                                                                <i class="fa fa-exclamation-circle"></i>&nbsp;Not Signed
                                                            </span>
                                                        </h6>
                                                        <div class="alert alert-info" role="alert">
                                                            <small>
                                                                <i class="fa fa-info-circle"></i>&nbsp;Please upload your digital signature to complete the policy acknowledgement.
                                                            </small>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="v-pills-training" role="tabpanel" aria-labelledby="v-pills-training">
                      <div class="card p-2">
                        <div class="template-demo">
                          <div class='row m-2'>
                            <div class='col-md-12 text-center mt-3 mb-3'>
                              <h3>Training
                                <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#addTrainingModal">
                                  <i class="fa fa-plus"></i>
                                </button>
                              </h3>
                            </div>
                          </div>
                          <div class="table-responsive">
                            <table class="table table-bordered table-hover tablewithSearch">
                                <thead>
                                <tr>
                                    <th>Training</th>
                                    <th>Training Period</th>
                                    <th>Bond Period</th>
                                    <th>Attachment</th>
                                    <th>Certificate</th>
                                    <th>Amount</th>
                                    <!-- <th>Action</th> -->
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($employeeTraining) > 0)
                                    @foreach ($employeeTraining as $et)
                                    <tr>
                                        <td>{{$et->training}}</td>
                                        <td>{{date('M. d, Y', strtotime($et->start_date))}} - {{date('M. d, Y', strtotime($et->end_date))}}</td>
                                        <td>@if($et->bond_start_date){{date('M. d, Y', strtotime($et->bond_start_date))}} - {{date('M. d, Y', strtotime($et->bond_end_date))}}@endif</td>
                                        <td> @if ($et->attachment)
                                            <a href="{{ url($et->attachment) }}" target="_blank">Attachment</a>
                                            @endif
                                        </td>
                                        <td> @if ($et->training_attachment)
                                            <a href="{{ url($et->training_attachment) }}" target="_blank">Certificate</a>
                                            @endif
                                        </td>
                                        <td><span>&#8369;</span>{{ number_format($et->amount,2)}}</td>
                                        <!-- <td></td> -->
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                    <td colspan="7" class="text-center">No data available.</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="v-pills-nte" role="tabpanel" aria-labelledby="v-pills-nte">
                      <div class="card p-2">
                        <div class="template-demo">
                          <div class='row m-2'>
                            <div class='col-md-12 text-center mt-3 mb-3'>
                              <h3>Employee NTE
                                <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#uploadNteModal">
                                  <i class="fa fa-plus"></i>
                                </button>
                              </h3>
                            </div>
                          </div>
                          <div class="table-responsive">
                            <table class="table table-hover table-bordered tablewithSearch">
                              <thead>
                                <tr>
                                  <th>File</th>
                                </tr>
                              </thead>
                              <tbody>
                                @foreach ($employeeNte as $nte)
                                  <tr>
                                    <td>
                                      <a href="{{url($nte->file_path)}}" title="View file" target="_blank">
                                        {{$nte->file_name}}
                                      </a>
                                    </td>
                                  </tr>
                                @endforeach
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="v-employee-documents" role="tabpanel" aria-labelledby="v-employee-documents">
                      <div class="card p-4">
                        <div class="template-demo">
                          <div class='row m-2'>
                            <div class='col-md-12 text-center mt-3 mb-3'>
                              <h3>Employee Documents
                                <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#empDocsModal">
                                  <i class="fa fa-plus"></i>
                                </button>
                              </h3>
                            </div>
                          </div>
                          @php
                            $documentTypes = documentTypes();
                          @endphp
                          @foreach ($documentTypes as $key=>$docs)
                            <div class="row">
                              <div class="col-md-4 border border-1 border-secondary border-top-bottom border-left-right" style="width: 100%;">
                                {{$docs}}
                              </div>
                                <div class="col-md-4 border border-1 border-secondary border-top-bottom border-left-right" style="width: 100%;">
                                  @php
                                    $empty = false;
                                  @endphp
                                  @foreach ($employeeDocuments as $item)
                                    @if($key === $item->document_type)
                                      Passed
                                      @php
                                        $empty = true;
                                      @endphp
                                    @endif
                                  @endforeach
                                  @if(!$empty)
                                    Not Yet Submitted
                                  @endif
                                </div>
                              <div class="col-md-4 border border-1 border-secondary border-top-bottom border-left-right" style="width: 100%;">
                                @foreach ($employeeDocuments as $item)
                                  @if($key == $item->document_type)
                                    <a href="{{url($item->file_path)}}" target="_blank">{{$item->file_name}}</a>
                                  @endif
                                @endforeach
                              </div>
                            </div>
                          @endforeach
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="v-employee-org-chart" role="tabpanel" aria-labelledby="v-employee-org-chart">
                      <div class="card p-4">
                        <div class="template-demo">
                          <div class='row m-2'>
                            <div class='col-md-12 text-center mt-3 mb-3 h-100'>
                              <h3>Org Chart
                                
                            </h3>
                           
                        
                            
                         
                            <div id="orgChart"/>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .nav-pills .nav-link {
        margin-bottom: 10px;
        border-radius: 15px;
        color: #000f21;
        border: 1px solid #b6d0ed;
        padding: .75rem 1.75rem;
    }
    .nav-pills {
        border-bottom: 0px solid #CED4DA;
    }
    .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
        color: #fff;
        background-color: #248AFD;
    }
    .tab-employee {
        padding: 10px;
    }
</style>

<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).on('submit', '.policy-form', function(e){
    e.preventDefault();

    let form = $(this);
    let id = form.data('id');
    let formData = new FormData(this);

    $.ajax({
        url: "/account-setting-hr/updateConsent/" + id,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },

        success: function(res){
            Swal.fire({
                icon: 'success',
                title: 'Saved!',
                text: res.message,
                didClose: function(){
                    // Option 1: Reload entire page
                    location.reload();

                    // Option 2: Reload only the policies tab (uncomment to use)
                    // $('.tab-pane#v-pills-policies').load(location.href + ' .tab-pane#v-pills-policies');

                    // Option 3: Update badge status (uncomment to use)
                    // form.closest('.card').find('.badge')
                    //     .removeClass('badge-warning')
                    //     .addClass('badge-success')
                    //     .html('<i class="fa fa-check-circle"></i>&nbsp;Completed');
                }
            });
        },

        error: function(xhr){
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                let msg = Object.values(errors)[0][0];

                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: msg
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong'
                });
            }
        }
    });
});

</script>

@include('employees.upload_avatar')
@include('employees.upload_signature')
@include('employees.edit_info')
@include('employees.edit_employee_info')
@include('employees.edit_contact_info')
@include('employees.edit_beneficiaries')
@include('employees.create_nopa')
@include('employees.create_salary_nopa')
@include('employees.add_salary')
@foreach($user->employee->employeeMovement as $movement)
        @include('employees.view_nopa')   
@endforeach
@foreach($user->employee->salaryMovement as $movement)
        @include('employees.view_salary_nopa')   
@endforeach
@include('employee_benefits.add_employee_benefits')
@include('hr-portal.new-training')
@include('hr-portal.new-nte')
@include('hr-portal.edit-employee-document')
@include('employees.edit_employee_no_modal')
@include('employees.edit_bank_details')
@endsection
@section('js')

@endsection
