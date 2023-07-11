<?php

use App\Http\Controllers\TaskController;
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

Route::get('/tasks', [TaskController::class, 'getAllTasks']);
Route::get('/tasks/{id}', [TaskController::class, 'getTasksByUser']);
Route::get('/tasks/description/{description}', [TaskController::class, 'getTasksByDescription']);
Route::post('/tasks', [TaskController::class, 'createTask']);
