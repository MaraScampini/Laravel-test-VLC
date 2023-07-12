<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'surname' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => ['required', Password::min(8)->mixedCase()->numbers()]
            ]);

            if($validator->fails()){
                return response()->json($validator->errors(), 400);
            }

            $validData = $validator->validated();

            $newUser = User::create([
                'name'=>$validData['name'],
                'surname'=>$validData['surname'],
                'email'=>$validData['email'],
                'password'=>bcrypt($validData['password']),
                'role_id'=>2
            ]);

            $token = $newUser->createToken('apiToken')->plainTextToken;

            return response()->json([
                'message' => 'User registered',
                'data' => $newUser,
                'token' => $token
            ], Response::HTTP_CREATED);

        } catch (\Throwable $th) {
            Log::error('Error registering user ' . $th->getMessage());

            return response()->json([
                'message' => 'Error registering user'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function login(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ], [
                'email'=> 'Email or password are invalid',
                'password' => 'Email or password are invalid'
            ]);

            if($validator->fails()){
                return response()->json($validator->errors(), 400);
            }

            $validData = $validator->validated();

            $user = User::where('email', $validData['email'])->first();

            if(!$user){
                return response()->json([
                    'message' => 'Email or password are invalid'
                ], Response::HTTP_FORBIDDEN);
            }

            if(!Hash::check($validData['password'], $user->password)){
                return response()->json([
                    'message' => 'Email or password are invalid'
                ], Response::HTTP_FORBIDDEN);
            }

            $token = $user->createToken('apiToken')->plainTextToken;

            return response()->json([
                'message' => 'User registered',
                'data' => $user,
                'token' => $token
            ], Response::HTTP_CREATED);

        } catch (\Throwable $th) {
            Log::error('Error logging user in ' . $th->getMessage());

            return response()->json([
                'message' => 'Error logging user in'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function profile(){
        try {
            $user = auth()->user();

            
            return response()->json([
                'message' => 'User found',
                'data' => $user,
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error retrieving user ' . $th->getMessage());

            return response()->json([
                'message' => 'Error retrieving user'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
