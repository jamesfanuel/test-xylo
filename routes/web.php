<?php

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

Auth::routes();

Route::get('/', [App\Http\Controllers\AuthController::class, 'showFormLogin']);
Route::get('login', [App\Http\Controllers\AuthController::class, 'showFormLogin'])->name('login');
Route::post('login', [App\Http\Controllers\AuthController::class, 'login']);
Route::get('register', [App\Http\Controllers\AuthController::class, 'showFormRegister'])->name('register');
Route::post('register', [App\Http\Controllers\AuthController::class, 'register']);

Route::group(['middleware' => ['auth']], function() {
    Route::resource('users', App\Http\Controllers\AuthController::class);

    Route::post('admin/assignAgent', [App\Http\Controllers\AdminController::class, 'assign'])->name('admin.assignAgent');
    Route::resource('admin', App\Http\Controllers\AdminController::class);

    Route::resource('agent', App\Http\Controllers\AgentController::class);

    Route::get('customer/followUpHistory', [App\Http\Controllers\CustomerController::class, 'followUpHistory'])->name('customer.followUpHistory');
    Route::post('customer/updateRemarks', [App\Http\Controllers\CustomerController::class, 'updateRemarks'])->name('customer.updateRemarks');
    Route::post('customer/updateStatus', [App\Http\Controllers\CustomerController::class, 'updateStatus'])->name('customer.updateStatus');
    Route::resource('customer', App\Http\Controllers\CustomerController::class);
});