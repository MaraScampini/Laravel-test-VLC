<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function deleteMyAccount(){
        try {
            $user = auth()->user();
            // $userFound = User::find($user->id);
            // $userFound->delete();

            User::destroy($user->id);

            return response()->json([
                'message'=>'User deleted'
            ], Response::HTTP_OK);

        } catch (\Throwable $th) {
            Log::error('Error deleting user ' . $th->getMessage());

            return response()->json([
                'message' => 'Error deleting user'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function restoreAccount($id){
        try {
            User::withTrashed()->where('id',$id)->restore();

            return response()->json([
                'message'=>'User restored'
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error restoring user ' . $th->getMessage());

            return response()->json([
                'message' => 'Error restoring user'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
