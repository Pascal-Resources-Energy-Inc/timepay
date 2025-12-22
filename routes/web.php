<?php


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
    use App\Http\Controllers\EmployeeObController;
    use App\HikAttLog2;
    Route::get('get-location','AttendanceController@getLocation');
    Auth::routes();
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
    Route::get('/upload-pay-reg', 'PayslipController@uploadpayreg');
    Route::post('/upload-pay-reg', 'PayslipController@postuploadpayreg');
    Route::get('get-devices','AttendanceController@devices');
    Route::group(['middleware' => 'auth'], function () {

    Route::post('timein-capture','AttendanceController@storeTimeIn');
    Route::post('timeout-capture','AttendanceController@storeTimeOut');

    Route::get('salary-history','EmployeeController@showsalary');
    //Users
    Route::get('account-setting', 'UserController@accountSetting');
    Route::post('upload-avatar', 'UserController@uploadAvatar');
    Route::post('upload-signature', 'UserController@uploadSignature');
    Route::get('get-salary', 'UserController@get_salary');
    Route::post('updateInfo/{id}', 'UserController@updateInfo');
    Route::post('updateEmpInfo/{id}', 'UserController@updateEmpInfo');
    Route::post('updateEmpContactInfo/{id}', 'UserController@updateEmpContactInfo');
    
    //employees
    Route::get('/dashboard', 'HomeController@index')->name('home');
    Route::get('/Admindashboard', 'HomeController@dashboardAdmin')->name('Admindashboard');
    Route::post('/edit-prob/{id}','HomeController@edit_prob');
    Route::get('', 'HomeController@index');
    Route::get('/', 'HomeController@index');
    Route::get('/home', 'HomeController@index')->name('home');
    Route::post('/upload-employee-image', [App\Http\Controllers\HomeController::class, 'uploadEmployeeImage'])->name('upload.employee.image');

    Route::post('/check-location-proximity', [App\Http\Controllers\HomeController::class, 'checkUserLocationProximity'])    
    ->name('check.location.proximity')
    ->middleware('auth');
    Route::post('/check-user-access', 'HomeController@checkUserAccess')->name('check.user.access');

    Route::get('/dashboard/get-employees', 'HomeController@getEmployees')->name('dashboard.getEmployees');
    Route::get('/dashboard/get-present-employees', 'HomeController@getPresentEmployees')->name('dashboard.get-present-employees');
    Route::get('/dashboard/get-absent-employees', 'HomeController@getAbsentEmployees')->name('dashboard.get-absent-employees');
    Route::get('/dashboard/get-late-employees', 'HomeController@getLateEmployees')->name('dashboard.get-late-employees');
   
    //approvers
    Route::get('/dashboard-manager', 'HomeController@managerDashboard');
    //admin
    Route::get('/dashboard/filter-by-location', [\App\Http\Controllers\HomeController::class, 'filterByLocation']);
    Route::get('/dashboard/absentees-pie', [\App\Http\Controllers\HomeController::class, 'absenteesPie']);
    Route::get('/dashboard/absentees-monthly-pie', [\App\Http\Controllers\HomeController::class, 'absenteesMonthlyPie']);
    Route::get('/dashboard/late-pie', [\App\Http\Controllers\HomeController::class, 'latePie']);


    Route::get('attendances', 'AttendanceController@index');
    Route::get('attendance-report', 'AttendanceController@reports')->name('reports');
    Route::post('/store_attendance', 'AttendanceController@storeAttendance')->name('attendance.store');
    Route::get('get-attendance-bio', 'AttendanceController@get_attendances');
    Route::post('sync_attendance','AttendanceController@syncAttendance');
    // Route::get('/fetch-log-dates/{company_id}', 'AttendanceController@checkLogDate');
    Route::get('/fetch-disabled-dates/{company_id}', 'AttendanceController@fetchDisabledDates');

    Route::get('attendance-per-company-export', 'AttendanceController@attendancePerCompanyExport');

    Route::get('seabased-attendances', 'AttendanceController@seabasedAttendances');
    Route::get('seabased-attendances-export', 'AttendanceController@attendanceSeabasedAttendnaceExport');
    Route::post('upload-seabased-attendance', 'AttendanceController@uploadSeabasedAttendance');

    Route::get('hik-attendances', 'AttendanceController@hikAttendances');
    Route::get('hik-attendances-export', 'AttendanceController@attendanceHikAttendnaceExport');
    Route::post('upload-hik-attendance', 'AttendanceController@uploadHikAttendance');

    //Leaves
    Route::get('file-leave', 'EmployeeLeaveController@leaveBalances');
    Route::post('new-leave','EmployeeLeaveController@new');
    Route::post('edit-leave/{id}', 'EmployeeLeaveController@edit_leave');
    Route::post('hr-edit-leave/{id}', 'EmployeeLeaveController@hr_edit_leave');
    Route::post('disable-leave/{id}', 'EmployeeLeaveController@disable_leave');
    Route::post('request-to-cancel-leave/{id}', 'EmployeeLeaveController@request_to_cancel');
    Route::get('void-to-cancel-leave/{id}', 'EmployeeLeaveController@void_request_to_cancel');
    Route::get('approve-request-to-cancel-leave/{id}', 'EmployeeLeaveController@approve_request_to_cancel');
    Route::get('decline-request-to-cancel-leave/{id}', 'EmployeeLeaveController@decline_request_to_cancel');
    Route::post('upload-attachment/{id}', 'EmployeeLeaveController@upload_attachment');

    Route::post('approve-leave-all','FormApprovalController@approveLeaveAll');
    Route::post('disapprove-leave-all','FormApprovalController@disapproveLeaveAll');

    Route::get('show','EmployeeObController@ob');

    //Purchase
    Route::get('purchase','PurchaseController@index')->name('purchase');
    Route::post('/purchases', 'PurchaseController@store')->name('purchases.store');
    Route::post('/purchases/{id}/approve', 'PurchaseController@approve')->name('purchases.approve');
    Route::get('products/get','PurchaseController@getProducts')->name('products.get');

    Route::get('/claim/{qr_code}', 'PurchaseController@claimPage')->name('purchase.claim');
    Route::post('/claim/process', 'PurchaseController@processClaim')->name('purchase.processClaim');

    Route::get('purchase-reports', 'PurchaseController@reports')->name('purchase.reports');
    Route::get('/purchase/export', 'PurchaseController@export')->name('purchase.export');

    //TDS
    Route::get('/tds', 'TDSController@index')->name('tds.index');
    Route::post('/tds/store', 'TDSController@store')->name('tds.store');
    Route::get('/tds/export', 'TDSController@export')->name('tds.export');
    Route::post('/tds/update-target', 'TDSController@updateSalesTarget')->name('tds.update-target');
    Route::get('/tds/get-employee-target', 'TDSController@getEmployeeTarget')->name('tds.get-employee-target');
    Route::get('/tds/get-all-users', 'TDSController@getAllUsers')->name('tds.get-all-users');
    Route::get('/tds/activity-logs', 'TDSController@getActivityLogs')->name('tds.activity-logs');

    Route::get('/tdsdashboard', 'TDSController@dashboard')->name('tds.dashboard');
    Route::get('/tds/employees/search', 'TDSController@getEmployees')->name('tds.employees.search');
    Route::get('/tds/dashboard/export', 'TDSController@dashboardExport')->name('tds.dashboard.export');

    Route::get('/tds/{id}', 'TDSController@show')->name('tds.show');
    Route::put('/tds/{id}', 'TDSController@update')->name('tds.update');
    Route::delete('/tds/{id}', 'TDSController@destroy')->name('tds.destroy');

    Route::get('overtime','EmployeeOvertimeController@overtime');
    //Overtime
    Route::get('overtime','EmployeeOvertimeController@overtime');
    Route::post('new-ot','EmployeeOvertimeController@new');
    Route::post('new-offset','EmployeeOvertimeController@newOffSet');
    Route::post('edit-overtime/{id}', 'EmployeeOvertimeController@edit_overtime');
    Route::get('disable-overtime/{id}', 'EmployeeOvertimeController@disable_overtime');    
    Route::get('check-valid-overtime', 'EmployeeOvertimeController@checkValidOvertime');
    Route::post('upload-overtime-attachments/{id}', 'EmployeeOvertimeController@uploadOvertimeAttachments');

    //Work-from-home
    Route::get('work-from-home', 'EmployeeWfhController@wfh');
    Route::post('new-wfh','EmployeeWfhController@new');
    Route::post('edit-wfh/{id}','EmployeeWfhController@edit_wfh');
    Route::get('disable-wfh/{id}','EmployeeWfhController@disable_wfh');
    // Route::post('approve-wfh-all','FormApprovalController@approveWfhAll');
    // Route::post('disapprove-wfh-all','FormApprovalController@disapproveWfhAll');

    //travel-order
    Route::get('travel-order', 'EmployeeTravelOrderController@to');
    Route::post('new-to','EmployeeTravelOrderController@new');
    Route::post('edit-to/{id}', 'EmployeeTravelOrderController@edit_to')->name('edit-to');
    Route::post('hr-edit-to/{id}', 'EmployeeTravelOrderController@hr_edit_to');
    Route::post('upload-to-file/{id}', 'EmployeeTravelOrderController@upload_obFile');
    Route::get('disable-to/{id}', 'EmployeeTravelOrderController@disable_to');  
 
    // Route::post('/sync-actual-arrival-time/{toId}', 'EmployeeTravelOrderController@syncActualArrivalTime')->name('sync.actual.arrival.time');

    //authority-deduct
    Route::get('authority-deduct', 'EmployeeAuthorityDeductionController@ad');
    Route::post('new-ad', 'EmployeeAuthorityDeductionController@new');
    Route::post('new-ad-per-employee', 'EmployeeAuthorityDeductionController@newperEmployee');
    Route::put('edit-ad/{id}', 'EmployeeAuthorityDeductionController@edit_ad')->name('edit-ad');
    Route::get('disable-ad/{id}', 'EmployeeAuthorityDeductionController@disable_ad');  

    //payroll-disbursement
    Route::get('payroll-disbursement', 'EmployeePayrollDisbursementController@pd');
    Route::post('new-pd', 'EmployeePayrollDisbursementController@new');
    Route::post('edit-pd/{id}', 'EmployeePayrollDisbursementController@edit_pd')->name('edit-pd');
    Route::get('disable-pd/{id}', 'EmployeePayrollDisbursementController@disable_pd');  


    //number-enrollment
    Route::get('number-enrollment', 'EmployeeNumberEnrollmentController@ne');
    Route::post('new-ne', 'EmployeeNumberEnrollmentController@new');
    Route::post('edit-ne/{id}', 'EmployeeNumberEnrollmentController@edit_ne')->name('edit-ne');
    Route::get('disable-ne/{id}', 'EmployeeNumberEnrollmentController@disable_ne');  

    //coe-request
    Route::get('coe-request', 'EmployeeCoeController@coe');
    Route::post('new-coe', 'EmployeeCoeController@new');
    Route::post('edit-coe/{id}', 'EmployeeCoeController@edit_coe')->name('edit-coe');
    Route::get('disable-coe/{id}', 'EmployeeCoeController@disable_coe');  
   
    //DTR Correction
    Route::get('dtr-correction', 'EmployeeDtrController@dtr');
    Route::post('new-dtr','EmployeeDtrController@new');
    Route::post('edit-dtr/{id}', 'EmployeeDtrController@edit_dtr');
    Route::get('disable-dtr/{id}', 'EmployeeDtrController@disable_dtr');     

    //Planning 
    Route::get('planning', 'EmployeePlanningController@index');
    Route::post('/planning/import', 'EmployeePlanningController@import')->name('planning.import');
    Route::post('/planning/upload-files', 'HomeController@uploadFiles')->name('planning.upload-files');
    Route::get('/planning/{id}/files', 'HomeController@getFiles')->name('planning.get-files');
    Route::get('/disable-planning/{id}', 'EmployeePlanningController@disablePlanning')->name('planning.disable');

    //Dar 
    Route::get('dar', 'DarController@index');


    //FOR APPROVAL
    Route::get('for-leave','FormApprovalController@form_leave_approval');
    Route::post('approve-leave/{id}','FormApprovalController@approveLeave');
    Route::post('decline-leave/{id}','FormApprovalController@declineLeave');

    Route::get('for-overtime','FormApprovalController@form_overtime_approval');
    Route::post('approve-ot-hrs/{employee_overtime}','FormApprovalController@approveOvertime');
    Route::post('timekeeper-approve-ot-hrs/{employee_overtime}','FormApprovalController@timekeeperApproveOvertime');
    Route::post('decline-overtime/{id}','FormApprovalController@declineOvertime');

    Route::get('for-work-from-home','FormApprovalController@form_wfh_approval');
    // Route::get('approve-wfh/{id}','FormApprovalController@approveWfh');
    Route::post('decline-wfh/{id}','FormApprovalController@declineWfh');
    Route::post('approve-wfh-percentage/{id}','FormApprovalController@approveWfh');

    //travel order Manager
    Route::get('travel-orderManager','FormApprovalController@form_to_approval');
    Route::post('approve-to/{id}','FormApprovalController@approveto');
    Route::post('decline-to/{id}','FormApprovalController@declineto');
    Route::post('approve-to-all','FormApprovalController@approveToAll');
    Route::post('disapprove-to-all','FormApprovalController@disapproveToAll');  

    //authority to deduct payroll handler
    Route::get('authority-deduction','FormApprovalController@form_ad_approval');
    Route::post('approve-ad/{id}','FormApprovalController@approvead');
    Route::post('decline-ad/{id}','FormApprovalController@declinead');
    Route::post('approve-ad-all', 'FormApprovalController@approveadAll');
    Route::post('disapprove-ad-all','FormApprovalController@disapproveadAll');

    //authority to deduct view on payroll
    Route::get('pds-approval','FormApprovalController@form_pd_approval');
    Route::post('approve-pd/{id}','FormApprovalController@approvepd');
    Route::post('decline-pd/{id}','FormApprovalController@declinepd');
    Route::post('approve-pd-all', 'FormApprovalController@approvepdAll');
    Route::post('disapprove-pd-all','FormApprovalController@disapprovepdAll');

    //ne request approval
    Route::get('nes-approval','FormApprovalController@form_ne_approval');
    Route::post('approve-ne/{id}', 'FormApprovalController@approveNe');
    Route::post('decline-ne/{id}', 'FormApprovalController@declineNe');
    Route::post('approve-ne-all', 'FormApprovalController@approveNeAll');
    Route::post('disapprove-ne-all', 'FormApprovalController@disapproveNeAll');

    //coe request approval
    Route::get('coe-approval','FormApprovalController@form_coe_approval');
    Route::post('approve-coe/{id}','FormApprovalController@approvecoe');
    Route::post('decline-coe/{id}','FormApprovalController@declinecoe');
    Route::post('approve-coe-all', 'FormApprovalController@approvecoeAll');
    Route::post('disapprove-coe-all','FormApprovalController@disapprovecoeAll');

    Route::get('for-dtr-correction','FormApprovalController@form_dtr_approval');
    Route::post('approve-dtr/{id}','FormApprovalController@approveDtr');
    Route::post('decline-dtr/{id}','FormApprovalController@declineDtr');
    Route::post('approve-dtr-all','FormApprovalController@approveDtrAll');
    Route::post('disapprove-dtr-all','FormApprovalController@disapproveDtrAll');

    //employees
    Route::get('employees', 'EmployeeController@view');
    Route::get('print-id/{id}','EmployeeController@print');
    Route::get('employees-export', 'EmployeeController@export');
    Route::get('employees-export-hr', 'EmployeeController@export_hr');
    Route::post('new-employee', 'EmployeeController@new');
    Route::get('account-setting-hr/{user}', 'EmployeeController@employeeSettingsHR');
    Route::post('account-setting-hr/updateInfoHR/{id}', 'EmployeeController@updateInfoHR');
    Route::post('account-setting-hr/updateEmpInfoHR/{id}', 'EmployeeController@updateEmpInfoHR');
    Route::post('account-setting-hr/updateEmpMovementHR/{id}', 'EmployeeController@updateEmpMovementHR');
    Route::post('account-setting-hr/updateEmpSalaryMovementHR/{id}', 'EmployeeController@updateEmpSalaryMovementHR');
    Route::post('account-setting-hr/updateEmpSalary/{id}', 'EmployeeController@updateEmpSalary');
    Route::post('account-setting-hr/updateContactInfoHR/{id}', 'EmployeeController@updateContactInfoHR');
    Route::post('account-setting-hr/updateBeneficiariesHR/{id}', 'EmployeeController@updateBeneficiariesHR');
    Route::get('account-setting-hr/getBeneficiariesHR/{id}', 'EmployeeController@getBeneficiariesHR');
    Route::post('account-setting-hr/uploadAvatarHr/{id}', 'EmployeeController@uploadAvatarHr');
    Route::post('account-setting-hr/uploadSignatureHr/{id}', 'EmployeeController@uploadSignatureHr');


    Route::get('associate-employees-export','EmployeeController@export_employee_associates');


    //Payslips
    Route::get('payslips', 'PayslipController@view');

    //handbooks
    Route::get('handbooks', 'HandbookController@view');
    Route::post('new-handbook', 'HandbookController@newhandbook');

    //Holidays
    Route::get('holidays', 'HolidayController@view');
    Route::post('new-holiday', 'HolidayController@new');
    Route::get('delete-holiday/{id}', 'HolidayController@delete_holiday');
    Route::post('edit-holiday/{id}', 'HolidayController@edit_holiday');

    //formsLeave
    Route::get('leavee-settings', 'LeaveController@leaveDetails');

    //Schedules
    Route::get('schedules', 'ScheduleController@schedules');
    Route::post('new-schedule', 'ScheduleController@newSchedule');


    //Announcement
    Route::get('announcements', 'AnnouncementController@view');
    Route::post('new-announcement', 'AnnouncementController@new');
    Route::get('delete-announcement/{id}', 'AnnouncementController@delete');

    //Logos
    Route::get('logos', 'SettingController@view');
    Route::post('upload-icon', 'SettingController@uploadIcon');
    Route::post('upload-logo', 'SettingController@uploadLogo');

    //Manager
    Route::get('subordinates', 'AttendanceController@subordinates');

    //Allowances
    Route::get('allowances', 'AllowanceController@viewAllowances');
    Route::post('new-allowance', 'AllowanceController@new');
    Route::get('disable-allowance/{id}', 'AllowanceController@disable_allowance');
    Route::get('activate-allowance/{id}', 'AllowanceController@activate_allowance');
    Route::post('edit-allowance/{id}', 'AllowanceController@edit_allowance');

    // Incentives
    Route::get('incentives', 'IncentiveController@index');
    Route::post('new-incentive', 'IncentiveController@store');
    Route::get('disable-incentive/{id}', 'IncentiveController@disable_incentive');
    Route::get('activate-incentive/{id}', 'IncentiveController@activate_incentive');
    Route::post('edit-incentive/{id}', 'IncentiveController@update');

    // Approval by Amount
    Route::get('approval-amount', 'ApprovalAmountController@index');
    Route::post('updateApprovalAmount', 'ApprovalAmountController@updateApprovalAmount');

    //Biometrics
    Route::get('get-biometrics', 'EmployeeController@employees_biotime');
    Route::post('new-biocode', 'EmployeeController@newBio');
    Route::post('update-biocode', 'EmployeeController@updatebiocode');
    Route::get('biologs-employee', 'EmployeeController@employee_attendance');
    Route::get('bio-per-location', 'EmployeeController@biologs_per_location');
    Route::get('bio-per-location-hik', 'EmployeeController@biologs_per_location_hik');
    Route::get('bio-per-location-export', 'EmployeeController@biologs_per_location_export');
    Route::get('pmi-local', 'EmployeeController@localbio');
    Route::get('biometrics-per-company', 'EmployeeController@perCompany');
    Route::get('sync-biometrics','EmployeeController@sync');
    Route::post('sync-bio','EmployeeController@syncBio');
    Route::get('sync-biometric-per-employee','EmployeeController@sync_per_employee');
    // Route::get('sync-biometric-per-employee-hik','EmployeeController@sync_per_employee_hik');
    Route::get('sync-biometric-per-employee-hik','EmployeeController@sync_per_employee_hik_with_upload');

    Route::get('biologs-employee-attendance-report', 'EmployeeController@employee_attendance_report');

    // Route::get('sync-per-employee','EmployeeController@sync_per_employee');
    Route::get('sync-hik-att-logs','EmployeeController@sync_hik_with_upload');

    //Payroll
    Route::get('pay-reg', 'PayslipController@payroll_datas');
    Route::post('payreg', 'PayslipController@postPayRoll');
    Route::post('importPayRegExcel', 'PayslipController@importPayRegExcel');
    Route::get('/generated-payroll','PayslipController@generatedPayroll');
    Route::get('/payslip','PayslipController@generatePayslip');
    Route::get('/payslip-employee','PayslipController@generatePayslipEmployee');

    Route::get('pay-instruction', 'PayslipController@payroll_instruction');
    Route::post('deletePayRegInstruction/{id}', 'PayslipController@deletePayRegInstruction');
    Route::post('importPayinstructionExcel', 'PayslipController@importPayInstructionExcel');
    Route::post('add-payroll-instruction','PayslipController@add_payroll_instruction');
    Route::get('export-intruction-template', 'PayslipController@export');

    
     
    Route::get('timekeeping', 'PayslipController@attendances');
    Route::get('generated-timekeeping', 'PayslipController@generatedAttendances');
    Route::post('pay-reg', 'PayslipController@import');
    Route::post('upload-attendance', 'PayslipController@upload_attendance');

     //Tax
     Route::get('tax', 'TaxController@tax');
     Route::post('new-tax','TaxController@new');
     Route::post('edit-tax/{id}', 'TaxController@edit_tax');
     Route::delete('delete-tax/{id}', 'TaxController@delete_tax');
     Route::get('compute_tax', 'TaxController@compute_tax');


    // Company
    Route::get('company', 'CompanyController@company_index');
    Route::post('newCompany', 'CompanyController@store_company');

    // Department
    Route::post('newDepartment', 'DepartmentController@store_department');
    Route::get('department', 'DepartmentController@department_index');
    Route::get('enable-department/{id}', 'DepartmentController@enable_department');
    Route::get('disable-department/{id}', 'DepartmentController@disable_department');
    Route::get('edit-deparment/{id}', 'DepartmentController@edit_department');
    Route::post('update-department/{id}', 'DepartmentController@update_department');

    // Location
    Route::post('store-location', 'LocationController@store');
    Route::get('location', 'LocationController@index');
    Route::get('edit-location/{id}', 'LocationController@edit');
    Route::post('update-location/{id}', 'LocationController@update');
    
    Route::post('store-location-time','LocationController@storeTime');

    // Project
    Route::post('store-project', 'ProjectController@store');
    Route::get('project', 'ProjectController@index');
    Route::get('edit-project/{id}', 'ProjectController@edit');
    Route::post('update-project/{id}', 'ProjectController@update');

    // Loan Type
    Route::get('loan-type', 'LoanTypeController@loanTypes_index');
    Route::post('newLoanType', 'LoanTypeController@store_loanType');
    Route::get('enable-loanType/{id}', 'LoanTypeController@enable_loanType');
    Route::get('disable-loanType/{id}', 'LoanTypeController@disable_loanType');

    // Employee Allowance
    Route::get('employee-allowance', 'EmployeeAllowanceController@index');
    Route::post('new-employee-allowance', 'EmployeeAllowanceController@store');
    Route::post('update-employee-allowance/{id}', 'EmployeeAllowanceController@update');
    Route::get('edit-employee-allowance/{id}', 'EmployeeAllowanceController@edit');
    Route::get('delete-employee-allowance/{id}', 'EmployeeAllowanceController@delete');
    Route::get('disableEmp-allowance/{id}', 'EmployeeAllowanceController@disable');

    // Employee Incentive
    Route::get('employee-incentive', 'EmployeeIncentiveController@index');
    Route::post('new-employee-incentive', 'EmployeeIncentiveController@store');
    Route::get('disableEmp-incentive/{id}', 'EmployeeIncentiveController@disable');

    // Employee Groups
    Route::get('employee-companies', 'EmployeeCompanyController@index');
    Route::post('new-employee-group', 'EmployeeCompanyController@store');
    Route::get('disableEmp-incentive/{id}', 'EmployeeCompanyController@disable');

    // Adjustments
    Route::get('salary-adjustment', 'AdjustmentController@index');
    Route::post('new-employee-adjustment', 'AdjustmentController@store');
    Route::get('disable-adjustment/{id}', 'AdjustmentController@disable');

    // Loans
    Route::get('loans', 'LoanController@index');
    Route::get('loan-reg', 'LoanController@loan_reg');
    Route::post('new-loan', 'LoanController@store_loanReg');
    Route::post('update-loan/{id}','LoanController@updateloanReg');


    // Reports
    Route::get('employee-report', 'EmployeeController@employee_report');
    Route::get('leave-report', 'LeaveController@leave_report');
    Route::get('leave-report-export', 'LeaveController@export');
    Route::get('/ne-report', 'NeController@ne_report');
    Route::get('totalExpense-report', 'PayrollController@totalExpense_report');
    Route::get('loan-report', 'LoanController@loan_report');
    Route::get('company-loan-report','LoanController@companyLoan');



    Route::get('government-report', 'PayrollController@government_reports');
    Route::get('payroll-report', 'PayrollController@payroll_report');
    Route::get('overtime-report', 'OvertimeController@overtime_report');
    Route::get('overtime-report-export', 'OvertimeController@export');
    Route::get('wfh-report', 'WorkfromhomeController@wfh_report');
    Route::get('wfh-report-export', 'WorkfromhomeController@export');
    Route::get('ob-report', 'OfficialbusinessController@ob_report');
    Route::get('ob-report-export', 'OfficialbusinessController@export');
    Route::get('dtr-report', 'DailytimerecordController@dtr_report');
    Route::get('dtr-report-export', 'DailytimerecordController@export');
    Route::get('ytd-report', 'PayslipController@ytd_report');


    //13th month
    Route::get('month-benefit', 'PayslipController@monthly_benefit');

    // Employee Leave Credits
    Route::get('employee-leave-credits', 'LeaveCreditsController@index');
    Route::post('new-employee-leave-credit', 'LeaveCreditsController@store');

    //Employee Leave Balances
    Route::get('employee-leave-balances', 'LeaveBalancesController@index');

    // Employee Earned Leaves
    Route::get('employee-earned-leaves', 'EmployeeEarnedLeaveController@index');
    Route::get('manual-employee-earned-leaves', 'EmployeeEarnedLeaveController@manual');
    Route::post('manual-employee-earned-leaves-store', 'EmployeeEarnedLeaveController@manual_store');
    Route::get('manual-employee-earned-leaves-delete', 'EmployeeEarnedLeaveController@manual_delete');

    //User
    Route::get('/users','UserController@index');
    Route::get('/edit-user-role/{user}','UserController@editUserRole');
    Route::get('/change-password/{user}','UserController@changePassword');
    Route::post('/update-user-role/{user}','UserController@updateUserRole');
    Route::post('/update-user-password/{user}','UserController@updateUserPassword');
    Route::post('/enable-mobile-attendance', 'UserController@enableMobileAttendance');
    Route::post('/disable-mobile-attendance', 'UserController@disableMobileAttendance');


    Route::get('users-export', 'UserController@export');

    //HR Approver Setting
    Route::get('/hr-approver-setting','HrApproverSettingController@index');
    Route::post('/save-hr-approver-setting','HrApproverSettingController@store');
    Route::get('/remove-hr-approver/{id}','HrApproverSettingController@remove'); 
    
    //Forms Approver Setting
    Route::get('/approver-setting','ApproverSettingController@index');
    Route::post('/save-approver-setting','ApproverSettingController@store');
    Route::get('/remove-approver/{id}','ApproverSettingController@removeApprover'); 

    //Timekeeping Dashboard
    

    Route::get('/timekeeping-dashboard','TimekeepingDashboardController@index');
    Route::get('/reset-leave/{id}','TimekeepingDashboardController@reset_leave');
    Route::get('/reset-ob/{id}','TimekeepingDashboardController@reset_ob');
    Route::get('/reset-wfh/{id}','TimekeepingDashboardController@reset_wfh');
    Route::get('/reset-ot/{id}','TimekeepingDashboardController@reset_ot');
    Route::get('/reset-dtr/{id}','TimekeepingDashboardController@reset_dtr');


    // Daily Schedule
    Route::get('/daily-schedule', 'DailyScheduleController@index');
    Route::get('/export-schedule-template', 'DailyScheduleController@exportTemplate');
    Route::get('/export-schedule', 'DailyScheduleController@export');   
    Route::post('/upload-schedule', 'DailyScheduleController@upload');
    Route::post('/update-schedule/{id}', 'DailyScheduleController@update');


    // HR Portal
    // NTE Files
    Route::get('/nte-upload', 'NteFileController@index');
    Route::post('/add-nte', 'NteFileController@store');
    Route::post('/update-nte/{id}', 'NteFileController@update');
    
    // 201 Files
    Route::get('/employee-documents', 'EmployeeDocumentController@index');
    Route::post('/upload-employee-document', 'EmployeeDocumentController@upload');
    
    // Training
    Route::get('/employee-training', 'EmployeeTrainingController@index');
    Route::post('/add-employee-training', 'EmployeeTrainingController@store');
    Route::post('/update-employee-training/{id}', 'EmployeeTrainingController@update');
    Route::post('/delete-employee-training/{id}', 'EmployeeTrainingController@delete');

    // Upload Module
    Route::get('/upload', 'UploadController@index');
    Route::post('/upload-ob', 'UploadController@upload');
    Route::post('/export-template', 'UploadController@export');

    // Payroll Setting
    // Tax Mapping
    Route::get('/tax-mapping', 'TaxMappingController@index');
    Route::post('/add-tax-mapping', 'TaxMappingController@addTaxMapping');
    Route::post('/update-tax-mapping/{id}', 'TaxMappingController@updateTaxMapping');
    Route::post('/delete-tax-mapping/{id}', 'TaxMappingController@deleteTaxMapping');


    Route::get('/employee-benefits', 'EmployeeBenefitsController@index');
    Route::post('/add-employee-benefits', 'EmployeeBenefitsController@store');
    Route::post('/update-employee-benefits/{id}', 'EmployeeBenefitsController@update');
    Route::post('/delete-employee-benefits/{id}', 'EmployeeBenefitsController@delete');

    // HR Side
    Route::get('/nte-reports', 'NteFileController@nteReports');
    Route::get('/employee-training-reports', 'EmployeeTrainingController@employeeTrainingReports');

    Route::post('/update-employee-code/{id}', 'EmployeeController@updateEmpNo');
    Route::post('/update-account-no/{id}', 'EmployeeController@updateAcctNo');
    Route::post('/reset-password', 'EmployeeController@resetPassword');

    // Payslip
    Route::get('/generate-payslip', 'PayslipController@generatePayslip');


    //Clearance
    Route::get('/my-clearance','ExitClearanceController@viewMyClearance');
    Route::get('view-comments/{id}','ExitClearanceController@viewComments')->name('Comments');
    Route::post('new-comment/{id}','ExitClearanceController@submitComment');
    Route::get('for-clearance','ExitClearanceController@forClearance')->name('For Clearance');
    Route::get('view-as-signatory/{id}','ExitClearanceController@viewAsSignatory')->name('Signatory');
    Route::post('change-status-checklist/{id}','ExitClearanceController@changestatus')->name('Change Status');
    Route::post('mark-as-cleared/{id}','ExitClearanceController@cleared')->name('Change Status');

    // Uploaded Leave Files
    Route::get('ob_files','UploadController@obFiles');

    // Leave Report Per Employee
    Route::get('leave-report-per-employee','LeaveReportPerEmployeeController@index');

    // SL Banks
    Route::get('sl_banks', 'SlBankController@index');
    Route::get('export_sl_bank_template', 'SlBankController@export');
    Route::post('store_sl_bank', 'SlBankController@store');

    // Perfect Attendance
    Route::get('perfect_attendance', 'PerfectAttendanceController@index');



    // Leave Calendar
    Route::get('leave_calendar', 'LeaveCalendarController@index');
    Route::post('store_plan_leave', 'LeaveCalendarController@store');
    Route::post('update_plan_leave/{id}', 'LeaveCalendarController@update');
    Route::post('delete_plan_leave/{id}', 'LeaveCalendarController@destroy');

    // Hub Location
    Route::get('hub_per_location', 'HubPerLocationController@index');
    Route::get('hub_per_location/data', 'HubPerLocationController@getData');
    Route::post('/create-user-for-hub', 'HubPerLocationController@createUserForHub')->name('create-user-for-hub');
    Route::post('/hub/remove-user-by-id', 'HubPerLocationController@removeUserFromHubById')->name('remove-user-from-hub-by-id');
    // Route::get('/hub-per-location/export', [HubPerLocationController::class, 'export'])->name('hub-per-location.export');
    Route::get('/hub-per-location/territories', [HubPerLocationController::class, 'getTerritoriesByRegion'])->name('hub-per-location.territories');
    Route::get('/hub-per-location/areas', [HubPerLocationController::class, 'getAreasByTerritory'])->name('hub-per-location.areas');

    Route::post('new-hub', 'HubPerLocationController@store');
    Route::post('edit-hub/{id}', 'HubPerLocationController@edit')->name('edit-hub');
        
});
Route::post('new-employee', 'EmployeeController@new');
Route::post('upload-employee', 'EmployeeController@upload');
Route::post('upload-employee-rate', 'EmployeeController@reverseRate');

Route::get('leave-credit-acc','EmployeeEarnedLeaveController@addLeave');

Route::get('hik-logs', function(){
    return HikAttLog2::orderBy('authDate')->get()->take(5);
});