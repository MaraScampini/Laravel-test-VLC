<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController\GetAllTasks;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskController\CreateTask;
use App\Http\Controllers\TaskController\GetTaskByUser;
use App\Http\Controllers\TaskController\getTasksByDescription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/user/{id}', function($id){

    // $user = DB::table('users')->where('id', $id)->get();
    // $user = User::where('id', $id)->get();

    $user = User::find($id);
    $userFirst = User::where('id', $id)->first();

    return $user;
});


// CONTROLADORES DE TASKS

 Route::get('/tasks', GetAllTasks::class);
Route::get('/tasks/{id}', GetTaskByUser::class);
Route::get('/tasks/description/{description}', getTasksByDescription::class);
Route::post('/tasks', CreateTask::class);

// CONTROLADORES DE AUTENTICACIÓN

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/profile', [AuthController::class, 'profile'])->middleware('auth:sanctum');
