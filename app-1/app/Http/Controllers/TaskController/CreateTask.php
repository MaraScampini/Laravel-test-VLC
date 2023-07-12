<?php

namespace App\Http\Controllers\TaskController;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CreateTask extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'description' => 'required|string',
                'user_id' => 'required'
            ], [
                'description.required' => '¡Hace falta una descripción!',
                'description.string' => 'La descripción debe contener un texto',
                'user_id' => 'Hay un error con la clave de usuario'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $validData = $validator->validated();

            $task = Task::create([
                'description' => $validData['description'],
                'user_id' => $validData['user_id']
            ]);

            // DB::table('tasks')->insert(['description' => $request->input('description'), 'user_id'=>$request->input('user_id')]);

            // $task = new Task;
            // $task->description = $request->input('description');
            // $task->user_id = $request->input('user_id');
            // $task->save();

            return response()->json([
                'message' => 'Task created',
                'data' => $task
            ], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            Log::error('Error creating task ' . $th->getMessage());

            return response()->json([
                'message' => 'Error creating task'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
