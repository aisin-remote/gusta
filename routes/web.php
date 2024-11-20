<?php

use App\User;
use App\Checkin;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\UserController;

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

// default route
Route::get('/', function () {
    return view('pages.user-pages.login');
})->middleware(['guest']);

Route::post('/logout-auth', 'Auth\LoginController@logout')->name('logout.auth');

// guest route
Route::middleware(['guest'])->group(function () {
    Route::get('/register', 'Auth\RegisterController@index')->name('register.index');
    Route::post('/register-store', 'Auth\RegisterController@store')->name('register.store');

    Route::get('/login', 'Auth\LoginController@index')->name('login');
    Route::post('/login-auth', 'Auth\LoginController@authenticate')->name('login.auth');
});

// Routes accessible without authentication
Route::get('/delivery', 'DeliveryController@index')->name('delivery.index');
Route::post('/delivery/create-ticket', 'DeliveryController@create')->name('delivery.create');
Route::get('/delivery/history', 'DeliveryController@history')->name('delivery.history');

// verifiy email
Route::get('/verify-email/{token}', 'Auth\RegisterController@verifyEmail')->name('verify.email');

// forgot password
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.hardReset');

// auth route
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', 'DashboardController@index')
        ->name('dashboard.index')
        ->middleware('check.role.session');

    // booking
    Route::get('/book-room', 'BookingController@index')->name('room.index');
    Route::get('/detail-room', 'BookingController@roomDetail')->name('room.detail');

    Route::get('/appointment/modal/{id}', 'AppointmentController@show');

    // visitor (create, history)
    Route::middleware(['visitor'])->group(function () {
        Route::get('/appointment', 'AppointmentController@index')->name('appointment.index');
        Route::post('/appointment/create-ticket', 'AppointmentController@create')->name('appointment.create');
        Route::get('/appointment/{id}/edit', 'AppointmentController@edit')->name('appointment.edit');
        Route::post('/appointment/{id}', 'AppointmentController@update')->name('appointment.update');
        Route::post('/appointment/{id}/destroy', 'AppointmentController@destroy')->name('appointment.destroy');

        Route::get('/appointment/history', 'AppointmentController@history')->name('appointment.history');
        Route::get('/get-pic', 'AppointmentController@getPic')->name('appointment.getPic');
        Route::get('/get-room', 'AppointmentController@getRoom')->name('appointment.getRoom');

        // update password
        Route::get('/update-password', 'UpdatePasswordController@index')->name('password.index');
        Route::post('/update-password/update', 'UpdatePasswordController@update')->name('password.update');  // Changed to POST

        // Portal
        Route::get('/portal', function () {
            return view('pages.user-pages.portal');
        })->name('portal');

        Route::post('/set-company', function (Request $request) {
            $request->validate(['company' => 'required']);
            session(['company' => $request->input('company')]);

            return redirect('/category');
        })->name('setCompany');

        Route::post('/remove-company', function () {
            session()->forget('company'); // Remove the 'company' session key
            return response()->json(['success' => true]);
        })->name('removeCompany');

        Route::get('/category', function () {
            return view('pages.user-pages.categories');
        })->middleware('checkCompanyType');

        Route::post('/set-category', function (Request $request) {
            $request->validate(['category' => 'required']);
            session(['category' => $request->input('category')]);

            return redirect('/appointment');
        })->name('setCategory');
    });

    // approver (approve, history)
    Route::middleware(['approver'])->group(function () {
        Route::get('/approval', 'ApprovalController@index')->name('ticket.index');
        Route::get('/approval/history', 'ApprovalController@history')->name('ticket.history');
        Route::post('/approval/approve/{ticket}', 'ApprovalController@ticketApproval')->name('ticket.approval');
        Route::post('/approval/reject/{ticket}', 'ApprovalController@ticketRejection')->name('ticket.rejection');
    });

    // admin (scan qr)
    Route::middleware(['admin'])->group(function () {
        Route::post('/appointment/export-appointment', 'AppointmentController@export')->name('appointment.export');
        Route::post('/delivery/export-delivery', 'DeliveryController@export')->name('delivery.export');
        Route::get('/qrScanView', 'ApprovalController@qrScanView')->name('qrScanView.index');
        Route::post('/qrScan', 'ApprovalController@qrScan')->name('qrScan.validate');
        Route::get('/cardScan', 'ApprovalController@cardScan')->name('cardScan.validate');

        Route::get('/card', 'DashboardController@card')->name('card.index');
        Route::get('/card/{id}', 'DashboardController@show')->name('cards.show');
    });

    // GA
    Route::get('/facility/history', 'ApprovalController@facilityHistory')->name('facility.history');
    Route::post('/facility-done/{facility}', 'ApprovalController@facilityDone')->name('facility.done');


    // logout
    Route::post('/logout-auth', 'Auth\LoginController@logout')->name('logout.auth');
});

Route::prefix('admin')->middleware(['superadmin'])->name('admin.')->group(function () {
    // User Management Routes
    Route::get('/user', 'UserController@index')->name('user.index');
    Route::get('/user/create', 'UserController@create')->name('user.create');
    Route::post('/user/store', 'UserController@store')->name('user.store');
    Route::get('/user/edit/{id}', 'UserController@edit')->name('user.edit');
    Route::post('/user/update/{id}', 'UserController@update')->name('user.update');
    Route::delete('/user/delete/{id}', 'UserController@destroy')->name('user.destroy');

    // Department Management Routes
    Route::get('/department', 'DepartmentController@index')->name('department.index');
    Route::get('/department/create', 'DepartmentController@create')->name('department.create');
    Route::post('/department/store', 'DepartmentController@store')->name('department.store');
    Route::get('/department/edit/{id}', 'DepartmentController@edit')->name('department.edit');
    Route::post('/department/update/{id}', 'DepartmentController@update')->name('department.update');
    Route::delete('/department/delete/{id}', 'DepartmentController@destroy')->name('department.destroy');

    // Approval Management Routes
    // Route::get('/approval', 'ApprovalController@index')->name('ticket.index');
    // Route::get('/approval/history', 'ApprovalController@history')->name('ticket.history');
    // Route::post('/approval/approve/{ticket}', 'ApprovalController@ticketApproval')->name('ticket.approval');
    // Route::post('/approval/reject/{ticket}', 'ApprovalController@ticketRejection')->name('ticket.rejection');
});
