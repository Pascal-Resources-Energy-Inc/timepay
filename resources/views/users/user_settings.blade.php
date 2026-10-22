@extends('layouts.header')
@section('content')
@php
    $employee = $user->employee;
    $middle = $employee->middle_initial ? ' '.$employee->middle_initial.'.' : '';
    $suffix = trim((string) $employee->name_suffix);
    $fullName = trim(
        $employee->first_name.$middle.' '.$employee->last_name.
        ($suffix !== '' ? ' '.$suffix : '')
    ) ?: auth()->user()->name;
    $birthDate = $age = $hireDate = $tenure = null;
    if ($employee->birth_date) {
        try { $birthDate = new DateTime($employee->birth_date); $age = $birthDate->diff(new DateTime())->y; } catch (Exception $e) {}
    }
    if ($employee->original_date_hired) {
        try {
            $hireDate = new DateTime($employee->original_date_hired); $diff = $hireDate->diff(new DateTime(date('Y-m-d'))); $parts = array();
            if ($diff->y) $parts[] = $diff->y.' year'.($diff->y == 1 ? '' : 's');
            if ($diff->m) $parts[] = $diff->m.' month'.($diff->m == 1 ? '' : 's');
            if (!$diff->y && !$diff->m) $parts[] = $diff->d.' day'.($diff->d == 1 ? '' : 's');
            $tenure = implode(', ', $parts);
        } catch (Exception $e) {}
    }
@endphp
<style>
.account-profile{--blue:#2563eb;--navy:#172033;--muted:#697386;--line:#e5eaf2;--soft:#eff6ff;color:var(--navy)}
.account-profile .content-wrapper{background:#f6f8fc;padding:28px}.profile-hero{position:relative;overflow:hidden;margin-bottom:24px;padding:30px 32px;border-radius:18px;background:linear-gradient(120deg,#173b72,#2563eb 58%,#3b82f6);box-shadow:0 14px 32px rgba(30,64,175,.18);color:#fff}.profile-hero:after{position:absolute;right:-65px;bottom:-110px;width:260px;height:260px;border:42px solid rgba(255,255,255,.08);border-radius:50%;content:''}.profile-hero>*{position:relative;z-index:1}.profile-hero small{font-weight:700;letter-spacing:.11em;text-transform:uppercase;color:rgba(255,255,255,.78)}.profile-hero h1{margin:9px 0 7px;font-size:30px;font-weight:700;color:#fff}.profile-hero p{margin:0;color:rgba(255,255,255,.8);font-size:15px}
.profile-layout{display:grid;grid-template-columns:minmax(250px,310px) minmax(0,1fr);gap:24px;align-items:start;width:100%;min-width:0}.profile-layout>main{width:100%;min-width:0}.profile-sidebar{position:sticky;top:88px;min-width:0}.profile-card,.profile-section{width:100%;max-width:100%;overflow:hidden;border:1px solid var(--line);border-radius:16px;background:#fff;box-shadow:0 8px 24px rgba(25,39,70,.06)}.profile-card+.profile-card,.profile-section+.profile-section{margin-top:18px}.profile-identity{position:relative;padding:30px 24px 24px;text-align:center}.profile-identity:before{position:absolute;top:0;left:0;width:100%;height:82px;background:linear-gradient(120deg,#dbeafe,#eef2ff);content:''}.avatar-wrap{position:relative;z-index:1;display:inline-block}.profile-avatar{width:132px;height:132px;border:5px solid #fff;border-radius:50%;background:#e9eef7;box-shadow:0 8px 22px rgba(31,57,104,.17);object-fit:cover}.status-dot{position:absolute;right:9px;bottom:10px;width:17px;height:17px;border:3px solid #fff;border-radius:50%;background:#22c55e}.profile-name{margin:14px 0 0;font-size:21px;font-weight:700}.profile-role{margin:5px 0 14px;color:var(--muted);font-size:14px}.employee-code{display:inline-flex;align-items:center;max-width:100%;padding:7px 12px;border-radius:999px;background:var(--soft);color:#1e40af;font-size:12px;font-weight:700;text-align:left}.employee-code i{margin-right:7px}.profile-contacts{margin:22px 0 0;padding:18px 0 0;border-top:1px solid var(--line);text-align:left;list-style:none}.profile-contacts li{display:flex;gap:11px;color:var(--muted);font-size:13px;line-height:1.45}.profile-contacts li+li{margin-top:12px}.profile-contacts i{width:17px;margin-top:2px;color:var(--blue);text-align:center}.profile-contacts span{min-width:0;overflow-wrap:anywhere}.signature{margin-top:20px;padding-top:18px;border-top:1px solid var(--line);text-align:left}.signature small,.sidebar-title,.detail-label{display:block;color:var(--muted);font-size:11px;font-weight:700;letter-spacing:.07em;text-transform:uppercase}.signature img{display:block;max-width:100%;height:64px;margin:8px auto 0;object-fit:contain}
.sidebar-card{padding:18px}.sidebar-title{margin:0 0 12px}.profile-nav{margin:0;padding:0;list-style:none}.profile-nav li+li{margin-top:4px}.profile-nav a{display:flex;align-items:center;padding:10px 11px;border-radius:9px;color:#3c485d;font-size:13px;font-weight:600}.profile-nav a:hover{background:var(--soft);color:#1e40af;text-decoration:none}.profile-nav i:first-child{width:21px;margin-right:8px;color:var(--blue);text-align:center}.profile-nav .fa-chevron-right{margin-left:auto;font-size:9px;color:#a5afbf}.password-btn{display:flex;align-items:center;justify-content:center;width:100%;min-height:42px;border:0;border-radius:10px;background:var(--blue);box-shadow:0 5px 12px rgba(37,99,235,.2);color:#fff;font-weight:600}.password-btn:hover{background:#1e40af;color:#fff}.password-btn i{margin-right:8px}
.profile-section{scroll-margin-top:92px}.section-head{display:flex;align-items:center;padding:20px 22px;border-bottom:1px solid var(--line)}.section-icon{display:inline-flex;flex:0 0 40px;align-items:center;justify-content:center;width:40px;height:40px;margin-right:13px;border-radius:11px;background:var(--soft);color:var(--blue);font-size:17px}.section-head h2{margin:0;font-size:17px;font-weight:700}.section-head p{margin:3px 0 0;color:var(--muted);font-size:12px}.section-body{padding:22px}.details-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:22px 28px}.detail{min-width:0}.detail.full{grid-column:1/-1}.detail-label{margin-bottom:6px}.detail-value{margin:0;color:#263247;font-size:14px;font-weight:600;line-height:1.55;overflow-wrap:anywhere}.detail-value.empty{color:#7a8598;font-weight:500}.info-badge{display:inline-flex;margin-left:6px;padding:3px 7px;border-radius:999px;background:#ecfdf5;color:#047857;font-size:10px;font-weight:700;vertical-align:middle}.address+.address{margin-top:9px}.address strong{display:inline-block;min-width:76px;color:var(--muted);font-size:12px}
.beneficiary-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}.beneficiary{display:flex;align-items:center;min-width:0;padding:14px;border:1px solid var(--line);border-radius:11px;background:#fbfcfe}.beneficiary-initials{display:inline-flex;flex:0 0 38px;align-items:center;justify-content:center;width:38px;height:38px;margin-right:11px;border-radius:50%;background:#e0e7ff;color:#4338ca;font-size:13px;font-weight:700}.beneficiary div{min-width:0}.beneficiary h3{margin:0;font-size:13px;font-weight:700;overflow-wrap:anywhere}.beneficiary p{margin:3px 0 0;color:var(--muted);font-size:11px}.empty-state{padding:12px 4px;color:var(--muted);font-size:13px;text-align:center}.empty-state i{display:block;margin-bottom:8px;color:#c1c9d6;font-size:25px}.schedule-table{width:100%;margin:0;border-collapse:collapse}.schedule-table th{padding:0 14px 12px;border:0;color:var(--muted);font-size:10px;letter-spacing:.06em;text-transform:uppercase}.schedule-table td{padding:14px;border-top:1px solid var(--line);color:#344056;font-size:13px;vertical-align:middle}.day-pill{display:inline-flex;min-width:72px;justify-content:center;padding:6px 10px;border-radius:8px;background:#f1f5f9;font-size:11px;font-weight:700}.time{display:block;font-weight:600}.time+.time{margin-top:3px;color:var(--muted);font-size:11px;font-weight:500}.hours{color:#1e40af;font-weight:700;white-space:nowrap}
@media(max-width:1199px){.account-profile .content-wrapper{padding:22px}.profile-layout{grid-template-columns:1fr}.profile-sidebar{position:static;display:grid;grid-template-columns:minmax(0,1fr) minmax(220px,280px);gap:16px}.profile-sidebar .profile-identity{grid-row:1/3}.profile-sidebar .profile-card{margin-top:0}}
@media(max-width:767px){.profile-sidebar{display:block}.profile-sidebar .profile-card+.profile-card{margin-top:14px}.profile-nav{display:flex;gap:7px;overflow-x:auto;padding-bottom:4px;scrollbar-width:thin}.profile-nav li,.profile-nav li+li{flex:0 0 auto;margin:0}.profile-nav a{border:1px solid var(--line);background:#f8fafc;white-space:nowrap}.profile-nav .fa-chevron-right{display:none}}
@media(max-width:575px){.account-profile .content-wrapper{padding:12px}.profile-hero{margin-bottom:14px;padding:22px 18px;border-radius:14px}.profile-hero:after{right:-110px;bottom:-140px}.profile-hero h1{font-size:24px}.profile-hero p{font-size:13px;line-height:1.55}.profile-layout{gap:14px}.profile-card,.profile-section{border-radius:13px}.profile-section+.profile-section{margin-top:14px}.profile-identity{padding:26px 18px 20px}.profile-avatar{width:112px;height:112px}.section-head,.section-body{padding:17px}.section-head{align-items:flex-start}.section-icon{flex-basis:36px;width:36px;height:36px;margin-right:10px}.details-grid,.beneficiary-grid{grid-template-columns:1fr;gap:17px}.detail.full{grid-column:auto}.info-badge{display:table;margin:5px 0 0}.schedule-table,.schedule-table tbody,.schedule-table tr,.schedule-table td{display:block;width:100%}.schedule-table thead{display:none}.schedule-table tr{margin-bottom:10px;padding:10px 13px;border:1px solid var(--line);border-radius:11px;background:#fbfcfe}.schedule-table tr:last-child{margin-bottom:0}.schedule-table td{display:flex;align-items:center;justify-content:space-between;gap:14px;padding:8px 0;border:0;text-align:right}.schedule-table td:before{flex:0 0 88px;content:attr(data-label);color:var(--muted);font-size:10px;font-weight:700;letter-spacing:.05em;text-align:left;text-transform:uppercase}.schedule-table td:first-child{padding-top:2px}.schedule-table td:last-child{padding-bottom:2px}.time{max-width:170px}.address strong{display:block;margin-bottom:2px}}
@media(max-width:359px){.account-profile .content-wrapper{padding:8px}.profile-hero{padding:20px 16px}.profile-hero h1{font-size:22px}.section-head,.section-body{padding:15px}.schedule-table td{gap:8px}.schedule-table td:before{flex-basis:76px}}
</style>
<div class="main-panel account-profile"><div class="content-wrapper">
    @if($errors->any())
        <div class="alert alert-danger alert-dismissable" role="alert"><button aria-label="Close" data-dismiss="alert" class="close" type="button">&times;</button><strong>{{ $errors->first() }}</strong></div>
    @endif
    <div class="profile-layout">
        <aside class="profile-sidebar" aria-label="Profile summary">
            <section class="profile-card profile-identity">
                <div class="avatar-wrap"><img class="profile-avatar" src="{{ URL::asset($employee->avatar ?: '/images/no_image.png') }}" alt="{{ $fullName }}" onerror="this.src='{{ URL::asset('/images/no_image.png') }}';"></div>
                <h2 class="profile-name">
                  {{ $fullName }}
                </h2>
                <p class="profile-role">{{ $employee->position ?: 'Position not specified' }}</p>
                <span class="employee-code"><i class="fa fa-id-badge"></i>Biometric {{ $employee->employee_number ?: 'not assigned' }}</span>
                <ul class="profile-contacts">
                    <li><i class="fa fa-envelope"></i><span>{{ auth()->user()->email ?: 'Work email not provided' }}</span></li>
                    <li><i class="fa fa-phone"></i><span>{{ $employee->personal_number ?: 'Phone number not provided' }}</span></li>
                    <li><i class="fa fa-map-marker"></i><span>{{ $employee->location ?: 'Work location not provided' }}</span></li>
                </ul>
                @if($employee->signature)
                    <div class="signature">
                        <small>Signature</small>
                        <img src="{{ URL::asset($employee->signature) }}" alt="Signature of {{ $fullName }}">
                    </div>
                @else
                    <small class="text-muted" style="background-color: #cccccc; border-radius: 4px; padding: 5px;">No signature</small>
                @endif
            </section>
            <section class="profile-card sidebar-card nav-card"><h3 class="sidebar-title">Profile sections</h3><nav aria-label="Profile sections"><ul class="profile-nav">
                <li><a href="#personal"><i class="fa fa-user"></i>Personal information<i class="fa fa-chevron-right"></i></a></li>
                <li><a href="#employment"><i class="fa fa-briefcase"></i>Employment<i class="fa fa-chevron-right"></i></a></li>
                <li><a href="#schedule"><i class="fa fa-calendar"></i>Schedule<i class="fa fa-chevron-right"></i></a></li>
                <li><a href="#emergency"><i class="fa fa-phone"></i>Emergency contact<i class="fa fa-chevron-right"></i></a></li>
                <!-- <li><a href="#beneficiaries"><i class="fa fa-heart"></i>Beneficiaries<i class="fa fa-chevron-right"></i></a></li> -->
                @if($employee->payment_info)<li><a href="#payment"><i class="fa fa-credit-card"></i>Payment information<i class="fa fa-chevron-right"></i></a></li>@endif
            </ul></nav></section>
            <section class="profile-card sidebar-card">
                <h3 class="sidebar-title">Account security</h3>
                <button class="password-btn" type="button" data-toggle="modal" data-target="#editUserPassword{{ $user->id }}">
                    <i class="fa fa-lock"></i>Change password
                </button>
            </section>
        </aside>
        <main>
            <section class="profile-section" id="personal">
                <div class="section-head"><span class="section-icon"><i class="fa fa-user"></i></span><div><h2>Personal information</h2><p>Your basic personal and contact details</p></div></div>
                <div class="section-body"><div class="details-grid">
                    <div class="detail"><span class="detail-label">Nickname</span><p class="detail-value {{ $employee->nick_name ? '' : 'empty' }}">{{ $employee->nick_name ?: 'Not provided' }}</p></div>
                    <div class="detail"><span class="detail-label">Personal email</span><p class="detail-value {{ $employee->personal_email ? '' : 'empty' }}">{{ $employee->personal_email ?: 'Not provided' }}</p></div>
                    <div class="detail"><span class="detail-label">Phone number</span><p class="detail-value {{ $employee->personal_number ? '' : 'empty' }}">{{ $employee->personal_number ?: 'Not provided' }}</p></div>
                    <div class="detail"><span class="detail-label">Marital status</span><p class="detail-value {{ $employee->marital_status ? '' : 'empty' }}">{{ $employee->marital_status ? ucfirst(strtolower($employee->marital_status)) : 'Not provided' }}</p></div>
                    <div class="detail"><span class="detail-label">Gender</span><p class="detail-value {{ $employee->gender ? '' : 'empty' }}">{{ $employee->gender ? ucfirst(strtolower($employee->gender)) : 'Not provided' }}</p></div>
                    <div class="detail"><span class="detail-label">Religion</span><p class="detail-value {{ $employee->religion ? '' : 'empty' }}">{{ $employee->religion ?: 'Not provided' }}</p></div>
                    <div class="detail"><span class="detail-label">Date of birth</span><p class="detail-value {{ $birthDate ? '' : 'empty' }}">@if($birthDate){{ $birthDate->format('F d, Y') }} <span class="info-badge">{{ $age }} year{{ $age == 1 ? '' : 's' }} old</span>@else Not provided @endif</p></div>
                    <div class="detail"><span class="detail-label">Place of birth</span><p class="detail-value {{ $employee->birth_place ? '' : 'empty' }}">{{ $employee->birth_place ?: 'Not provided' }}</p></div>
                    <div class="detail full"><span class="detail-label">Address</span><div class="detail-value"><div class="address"><strong>Present</strong> {{ $employee->present_address ?: 'Not provided' }}</div><div class="address"><strong>Permanent</strong> {{ $employee->permanent_address ?: 'Not provided' }}</div></div></div>
                </div></div>
            </section>

            <section class="profile-section" id="employment">
                <div class="section-head"><span class="section-icon"><i class="fa fa-briefcase"></i></span><div><h2>Employment information</h2><p>Your organization and government record details</p></div></div>
                <div class="section-body"><div class="details-grid">
                    <div class="detail"><span class="detail-label">Work email</span><p class="detail-value">{{ auth()->user()->email ?: 'Not provided' }}</p></div>
                    <div class="detail"><span class="detail-label">Department</span><p class="detail-value {{ $employee->department ? '' : 'empty' }}">{{ $employee->department ? $employee->department->name : 'Not assigned' }}</p></div>
                    <div class="detail"><span class="detail-label">Classification</span><p class="detail-value {{ $employee->classification_info ? '' : 'empty' }}">{{ $employee->classification_info ? $employee->classification_info->name : 'Not assigned' }}</p></div>
                    <div class="detail"><span class="detail-label">Level</span><p class="detail-value {{ $employee->level_info ? '' : 'empty' }}">{{ $employee->level_info ? $employee->level_info->name : 'Not assigned' }}</p></div>
                    <div class="detail"><span class="detail-label">Work location</span><p class="detail-value {{ $employee->location ? '' : 'empty' }}">{{ $employee->location ?: 'Not assigned' }}</p></div>
                    <div class="detail"><span class="detail-label">Original date hired</span><p class="detail-value {{ $hireDate ? '' : 'empty' }}">@if($hireDate){{ $hireDate->format('M d, Y') }} @if($tenure)<span class="info-badge">{{ $tenure }}</span>@endif @else Not provided @endif</p></div>
                    <div class="detail"><span class="detail-label">SSS number</span><p class="detail-value {{ $employee->sss_number ? '' : 'empty' }}">{{ $employee->sss_number ?: 'Not provided' }}</p></div>
                    <div class="detail"><span class="detail-label">Pag-IBIG / HDMF number</span><p class="detail-value {{ $employee->hdmf_number ? '' : 'empty' }}">{{ $employee->hdmf_number ?: 'Not provided' }}</p></div>
                    <div class="detail"><span class="detail-label">PhilHealth number</span><p class="detail-value {{ $employee->phil_number ? '' : 'empty' }}">{{ $employee->phil_number ?: 'Not provided' }}</p></div>
                    <div class="detail"><span class="detail-label">TIN</span><p class="detail-value {{ $employee->tax_number ? '' : 'empty' }}">{{ $employee->tax_number ?: 'Not provided' }}</p></div>
                </div></div>
            </section>
            <section class="profile-section" id="schedule">
                <div class="section-head"><span class="section-icon"><i class="fa fa-calendar"></i></span><div><h2>Work schedule</h2><p>Your assigned time-in and time-out windows</p></div></div>
                <div class="section-body">
                    @if($employee->ScheduleData->count())
                    <div class="schedule-scroll"><table class="schedule-table"><thead><tr><th>Day</th><th>Start time</th><th>End time</th><th>Total hours</th></tr></thead><tbody>
                        @foreach($employee->ScheduleData as $schedule)<tr><td data-label="Day"><span class="day-pill">{{ $schedule->name }}</span></td><td data-label="Start time"><span class="time">{{ $schedule->time_in_from ?: 'Not set' }}</span><span class="time">until {{ $schedule->time_in_to ?: 'Not set' }}</span></td><td data-label="End time"><span class="time">{{ $schedule->time_out_from ?: 'Not set' }}</span><span class="time">until {{ $schedule->time_out_to ?: 'Not set' }}</span></td><td data-label="Total hours"><span class="hours">{{ number_format($schedule->working_hours,1) }} hrs</span></td></tr>@endforeach
                    </tbody></table></div>
                    @else <div class="empty-state"><i class="fa fa-calendar-o"></i>No work schedule has been assigned.</div> @endif
                </div>
            </section>

            <section class="profile-section" id="emergency">
                <div class="section-head"><span class="section-icon"><i class="fa fa-phone"></i></span><div><h2>Emergency contact</h2><p>The person to contact in case of an emergency</p></div></div>
                <div class="section-body"><div class="details-grid">
                    <div class="detail"><span class="detail-label">Contact person</span><p class="detail-value {{ $employee->contact_person && $employee->contact_person->name ? '' : 'empty' }}">{{ $employee->contact_person && $employee->contact_person->name ? $employee->contact_person->name : 'Not provided' }}</p></div>
                    <div class="detail"><span class="detail-label">Relationship</span><p class="detail-value {{ $employee->contact_person && $employee->contact_person->relation ? '' : 'empty' }}">{{ $employee->contact_person && $employee->contact_person->relation ? $employee->contact_person->relation : 'Not provided' }}</p></div>
                    <div class="detail full"><span class="detail-label">Contact number</span><p class="detail-value {{ $employee->contact_person && $employee->contact_person->contact_number ? '' : 'empty' }}">{{ $employee->contact_person && $employee->contact_person->contact_number ? $employee->contact_person->contact_number : 'Not provided' }}</p></div>
                </div></div>
            </section>

            <!-- beneficiaries -->
            <!-- <section class="profile-section" id="beneficiaries"> -->
            <!--     <div class="section-head"><span class="section-icon"><i class="fa fa-heart"></i></span><div><h2>Beneficiaries</h2><p>People listed as your beneficiaries</p></div></div> -->
            <!--     <div class="section-body"> -->
            <!--         @if($employee->beneficiaries->count())<div class="beneficiary-grid"> -->
            <!--             @foreach($employee->beneficiaries as $beneficiary) -->
            <!--                 @php $beneficiaryName = trim($beneficiary->first_name.' '.$beneficiary->last_name); $initials = strtoupper(substr($beneficiary->first_name,0,1).substr($beneficiary->last_name,0,1)); @endphp -->
            <!--                 <div class="beneficiary"><span class="beneficiary-initials">{{ $initials ?: 'B' }}</span><div><h3>{{ $beneficiaryName ?: 'Unnamed beneficiary' }}</h3><p>{{ $beneficiary->relation ?: 'Relationship not provided' }}</p></div></div> -->
            <!--             @endforeach -->
            <!--         </div>@else <div class="empty-state"><i class="fa fa-heart-o"></i>No beneficiaries are currently listed.</div>@endif -->
            <!--     </div> -->
            <!-- </section> -->
            @if($employee->payment_info)
            <section class="profile-section" id="payment">
                <div class="section-head"><span class="section-icon"><i class="fa fa-credit-card"></i></span><div><h2>Payment information</h2><p>Your payroll payment method and bank details</p></div></div>
                <div class="section-body"><div class="details-grid">
                    <div class="detail"><span class="detail-label">Payment period</span><p class="detail-value {{ $employee->payment_info->payment_period ? '' : 'empty' }}">{{ $employee->payment_info->payment_period ?: 'Not provided' }}</p></div>
                    <div class="detail"><span class="detail-label">Payment type</span><p class="detail-value {{ $employee->payment_info->payment_type ? '' : 'empty' }}">{{ $employee->payment_info->payment_type ?: 'Not provided' }}</p></div>
                    <div class="detail"><span class="detail-label">Bank name</span><p class="detail-value {{ $employee->payment_info->bank_name ? '' : 'empty' }}">{{ $employee->payment_info->bank_name ?: 'Not provided' }}</p></div>
                    <div class="detail"><span class="detail-label">Account number</span><p class="detail-value {{ $employee->payment_info->account_number ? '' : 'empty' }}">{{ $employee->payment_info->account_number ?: 'Not provided' }}</p></div>
                </div></div>
            </section>
            @endif
        </main>
    </div>
</div></div>

@include('users.upload_avatar')
@include('users.upload_signature')
@include('users.edit_info')
@include('users.edit_employee_info')
@include('users.edit_contact_info')
@include('users.edit_beneficiaries')
@include('users.view_salary')
@include('users.user_change_password')
@endsection
