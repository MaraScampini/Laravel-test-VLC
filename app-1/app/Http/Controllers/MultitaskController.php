<?php

namespace App\Http\Controllers;

use App\Models\Multitask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class MultitaskController extends Controller
{
    public function createMultitask(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'description' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $validData = $validator->validated();

            $userId = auth()->user()->id;

            $multitask = Multitask::create([
                'description' => $validData['description']
            ]);

            $multitask->user()->attach($userId, ['owner' => true]);

            return response()->json([
                'message' => 'Task created',
                'data' => $multitask
            ]);
        } catch (\Throwable $th) {
            Log::error('Error creating task ' . $th->getMessage());

            return response()->json([
                'message' => 'Error creating task'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function joinMultitask(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $validData = $validator->validated();

            $userId = auth()->user()->id;

            $multitask = Multitask::find($validData['id']);

            $multitask->user()->attach($userId, ['owner' => false]);

            return response()->json([
                'message' => 'Task joined',
                'data' => $multitask
            ]);
        } catch (\Throwable $th) {
            Log::error('Error joining task ' . $th->getMessage());

            return response()->json([
                'message' => 'Error joining task'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function leaveMultitask(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $validData = $validator->validated();

            $userId = auth()->user()->id;

            $multitask = Multitask::find($validData['id']);

            $isActive = $multitask->user()->find($userId);
            $isOwner = $multitask->user()->wherePivot('owner', true)->find($userId);

            if ($isActive and !$isOwner) {
                $multitask->user()->detach($userId);

                return response()->json([
                    'message' => 'Bien'
                ]);
            } else if ($isOwner) {
                return response()->json([
                    'message' => 'Eres el dueño, no te puedes ir'
                ]);
            } else {
                return response()->json([
                    'message' => 'No estás en esa tarea'
                ]);
            }
        } catch (\Throwable $th) {
            Log::error('Error leaving task ' . $th->getMessage());

            return response()->json([
                'message' => 'Error leaving task'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
