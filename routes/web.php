<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;

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

Route::get('/', [EmployeeController::class, 'index']);
Route::get('employee-form/{id}', [EmployeeController::class, 'EmployeeForm']);
Route::post('employee/save', [EmployeeController::class, 'EmployeeManage'])->name('employee.save');
Route::get('employee-delete/{id}', [EmployeeController::class, 'EmployeeDelete']);
Route::get('employee-status/{status}/{email}', [EmployeeController::class, 'EmployeeStatus']);
Route::get('employee-view/{id}', [EmployeeController::class, 'EmployeeView']);


