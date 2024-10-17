<?php

use App\User;
use App\Checkin;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

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

// auth route
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', 'DashboardController@index')->name('dashboard.index');
    Route::get('/update-password', 'UpdatePasswordController@index')->name('password.index');
    Route::get('/book-room', 'BookingController@index')->name('room.index');
    Route::get('/detail-room', 'BookingController@roomDetail')->name('room.detail');
    Route::get('/update-password/update', 'UpdatePasswordController@update')->name('password.update');

    // visitor (create,history)
    Route::get('/appointment', 'AppointmentController@index')->name('appointment.index');
    Route::post('/appointment/create-ticket', 'AppointmentController@create')->name('appointment.create');
    Route::get('/appointment/history', 'AppointmentController@history')->name('appointment.history');
    Route::get('/get-pic', 'AppointmentController@getPic')->name('appointment.getPic');
    Route::get('/get-room', 'AppointmentController@getRoom')->name('appointment.getRoom');

    // approver (approve, history)
    Route::get('/approval', 'ApprovalController@index')->name('ticket.index');
    Route::get('/approval/history', 'ApprovalController@history')->name('ticket.history');
    Route::post('/approval/approve/{ticket}', 'ApprovalController@ticketApproval')->name('ticket.approval');
    Route::post('/approval/reject/{ticket}', 'ApprovalController@ticketRejection')->name('ticket.rejection');

    // admin (scan qr)
    Route::post('/appointment/export-appointment', 'AppointmentController@export')->name('appointment.export');
    Route::get('/qrScanView', 'ApprovalController@qrScanView')->name('qrScanView.index');
    Route::post('/qrScan', 'ApprovalController@qrScan')->name('qrScan.index');

    // GA
    Route::get('/facility/history', 'ApprovalController@facilityHistory')->name('facility.history');
    Route::post('/facility-done/{facility}', 'ApprovalController@facilityDone')->name('facility.done');

    // logout
    Route::post('/logout-auth', 'Auth\LoginController@logout')->name('logout.auth');
});
