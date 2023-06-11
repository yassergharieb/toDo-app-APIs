<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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







Route::group(['prefix' => 'user' ] , function () {
  Route::post('/register' , [AuthController::class , 'register']); //uplodae profile photo while register
  Route::post('/login' , [AuthController::class , 'Userlogin']);
  Route::post('/logout' , [AuthController::class , 'Userlogout']);
  Route::post('/UserShow' , [AuthController::class , 'UserShow']);

});


Route::controller(TaskController::class )->prefix('task')->group(function() {
    Route::get('/' , 'index');
    Route::post('create' , 'create');
    Route::patch('update/{id}' , 'update');
    Route::delete('delete' , 'destroy');
    Route::patch('changeStatus/{id}' , 'changeStatus');
    Route::patch('changePriority/{id}' , 'changePriority');
    Route::delete('deleteCompleteTasks',  'deleteCompleteTasks');

});


