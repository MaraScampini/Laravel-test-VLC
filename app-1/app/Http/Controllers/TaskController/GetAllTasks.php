<?php

namespace App\Http\Controllers\TaskController;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class GetAllTasks extends Controller
{
    public function __invoke()
    {
        try {
            $tasks = Task::with('user')->get();
    
            return response()->json([
                'message' => 'Tasks retrieved',
                'data' => $tasks
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error getting tasks ' . $th->getMessage());
    
            return response()->json([
                'message' => 'Error retrieving tasks'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
