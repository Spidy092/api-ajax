<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public  function signup(Request $request){
            try {
                $validateUser = Validator::make(
                    $request->all(),
                    [
                        'name' => 'required',
                        'email'=> 'required|email|unique:users,email',
                        'password'=> 'required'
                    ]
                    );
    
                    if($validateUser->fails()){
                        return response()->json([
                            'status'=> false,
                            'message' => "validation Error",
                            "errors"=> $validateUser->errors()->all()
                        ],401);
                    };
    
                    $user = User::create([
                        'name'=> $request->name,
                        'email'=> $request->email,
                        'password'=> $request->password,
                    ]);
    
                    return response()->json([
                        "status"=> true,
                        "message"=> "user created succesfully",
                        'user' => $user,
                    ], 200);

            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'An unexpected error occurred.',
                    'error' => $e->getMessage(),
                ], 500);
            }
    }

    public  function login(Request $request){
            try {
                $validateUser = Validator::make(
                    $request->all(),
                    [
                        'email'=> 'required| email',
                        'password' => 'required',
                    ]
                    );
    
                    if($validateUser->fails()){
                        return response()->json([
                            'status'=> false,
                            'message'=> 'validation Error',
                            'errors' => $validateUser->errors()->all(),
                        ],400);
                    }
    
                    if(Auth::attempt(['email'=> $request->email,'password'=> $request->password])){
                        $authUser = Auth::user();
    
                        return response()->json([
                            'status'=> true,
                            "messagee"=> 'Autencated successfully',
                            'token'=> $authUser->createToken("api_token")->plainTextToken,
                            "token_type" => 'bearer',
    
                        ]);
                    }else {
                        return response()->json([
                            'status'=> false,
                            'message'=> 'Invalid email or password',
    
                        ],401);
                    }
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'An unexpected error occurred.',
                    'error' => $e->getMessage(),
                ], 500);
            }
            }
    

    public  function logout(Request $request){
        try {
            $user = $request->user();
            // $user->tokens()->delete();
            $user->CurrentAccessToken()->delete();

            return response()->json([
                'status'=> true,
                'message'=> 'You logged out successfully',
                'user'=> $user,
            ],200);
        
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
        }
    
    
}
    

