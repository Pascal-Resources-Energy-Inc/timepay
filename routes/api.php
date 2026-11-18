<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('save-attendance','AttendanceController@store_logs');
Route::post('save-attendance-hk','AttendanceController@store_logs_hk');
Route::get('get-last-id/{company}','AttendanceController@getlastId');
Route::get('get-last-id-hk/{company}','AttendanceController@getlastIdHK');
Route::get('get-schedule', 'EmployeeController@getSchedule');

Route::middleware('auth:api')->group(function () {
    Route::get('get-resigned-employee', 'EmployeeController@resigned');
});

// LDR (50 reqs per minute)
Route::prefix('external')
    ->middleware(['ldr.api.key', 'throttle:50,1'])
    ->group(function () {
        Route::get(
            'layout-design-requests',
            'Api\LayoutDesignController@index'
        )->name('api.external.layout-design-requests.index');

        Route::get(
            'layout-design-requests/{id}',
            'Api\LayoutDesignController@show'
        )->name('api.external.layout-design-requests.show');

        Route::post(
            'layout-design-requests',
            'Api\LayoutDesignController@store'
        )->name('api.external.layout-design-requests.store');

        Route::patch(
            'layout-design-requests/{id}',
            'Api\LayoutDesignController@update'
        )->name('api.external.layout-design-requests.update');

        Route::patch(
            'layout-design-requests/{id}/cancel',
            'Api\LayoutDesignController@cancel'
        )->name('api.external.layout-design-requests.cancel');
    });

// BMC api (50 reqs per minute too)
Route::prefix('external')
    ->middleware(['ldr.api.key', 'throttle:50,1'])
    ->group(function () {
        Route::get('bmc', 'Api\BmcController@index');
        Route::post('bmc', 'Api\BmcController@store');
        Route::get('bmc/{id}', 'Api\BmcController@show');
        Route::patch('bmc/{id}', 'Api\BmcController@update');
        Route::patch('bmc/{id}/cancel', 'Api\BmcController@cancel');
        Route::post('bmc/{id}/payment', 'Api\BmcController@submitPayment');
        Route::get('bmc/{id}/payment-proof', 'Api\BmcController@paymentProof');
        Route::get('bmc/{id}/refund-proof', 'Api\BmcController@refundProof');
        Route::get('bmc/accountabilities/{id}', 'Api\BmcController@showAccountability');
        Route::get('bmc/accountabilities/{id}/payment-proof', 'Api\BmcController@accountabilityPaymentProof');
        Route::post('bmc/accountabilities/{id}/signature', 'Api\BmcController@saveSignature');
        Route::post('bmc/accountabilities/{id}/returns', 'Api\BmcController@requestReturn');
        Route::post('bmc/accountabilities/{id}/extensions', 'Api\BmcController@requestExtension');
    });
