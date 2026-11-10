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
Route::prefix('public')
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
Route::prefix('public')
    ->middleware(['ldr.api.key', 'throttle:50,1'])
    ->group(function () {
        Route::get('bmc', 'Api\MarketingCollateralBorrowingController@index');
        Route::post('bmc', 'Api\MarketingCollateralBorrowingController@store');
        Route::get('bmc/{id}', 'Api\MarketingCollateralBorrowingController@show');
        Route::patch('bmc/{id}', 'Api\MarketingCollateralBorrowingController@update');
        Route::patch('bmc/{id}/cancel', 'Api\MarketingCollateralBorrowingController@cancel');
        Route::post('bmc/accountabilities/{id}/signature', 'Api\MarketingCollateralBorrowingController@saveSignature');
    });
