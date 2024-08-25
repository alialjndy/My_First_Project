<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class ApiController extends Controller
{
    public function register(Request $request){
        try{
            // Validate the incoming request data
            $validationData = Validator::make($request->all(),[
                'name'=> 'required',
                'email'=>'required|email|unique:users,email',
                'password'=>'required',
            ]);
            // If validation fails, return validation errors with 422 status code
            if($validationData->fails()){
                return response()->json([
                    'status'=>false ,
                    'message'=>'validation error',
                    'errors'=> $validationData->errors(),
                ], 422);
            }
            // Create a new user with the provided data and hash the password
            $user = User::create([
                'name'=> $request->name ,
                'email'=>$request->email ,
                'password' => Hash::make($request->password),
            ]);
            // Return a successful response with a newly created API token
            return response()->json([
                'status'=>true,
                'message'=>'user created successfully',
                'token'=>$user->createToken("API TOKEN")->plainTextToken // Create a token for the user
            ],200);

        }catch(\Exception $e){
            // Return an error response if something goes wrong
            return response()->json([
                'status'=>false,
                'message'=>$e->getMessage(),
            ], 500);
        }
    }
    // Method for user login
    public function login(Request $request){
        try{
            // Validate the login request data
            $validationData = Validator::make($request->all(),[
                'email'=>'required|email',
                'password'=>'required',
            ]);
            // If validation fails, return validation errors with 422 status code
            if($validationData->fails()){
                return response()->json([
                    'status'=>false ,
                    'message'=>'validation error',
                    'errors'=> $validationData->errors(),
                ], 422);
            }
            // Attempt to authenticate the user with the provided credentials
            if(!Auth::attempt($request->only('email', 'password'))){
               return response()->json([
                    'status'=>false ,
                    'message'=>'email & passowrd does not match with our record',
                ], 422);
            }

            // Retrieve the authenticated user based on their email
            $user = User::where('email', $request->email)->first();

            // Return a successful response with a new API token
            return response()->json([
                'status'=>true ,
                'message'=>'user logged in successfully',
                'token'=>$user->createToken("API TOKEN")->plainTextToken,
            ],200);
        }catch(\Exception $e){
            return response()->json([
                'status'=>false,
                'message'=>$e->getMessage(),// Send the exception message
            ], 500);
        }
    }
    public function logout(Request $request){
        // Check if the user is authenticated
        if (Auth::check()) {
            // delete all token related with the user
            $request->user()->tokens()->delete();

            // Return a successful logout response
            return response()->json([
                'status' => true,
                'message' => 'Logged out successfully',
            ], 200);
        }
        // If the user is not authenticated , return a 401 unauthorized response
        return response()->json([
            'status' => false,
            'message' => 'User is not authenticated',
        ], 401);
    }
}
