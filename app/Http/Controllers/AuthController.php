<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        // Remove the middleware on the login and register routes
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function respondWithToken($token)
    {
        // Return a token response to the user
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => 3600,
        ], 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $credentials = $request->only(['username', 'password']);

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    public function logout()
    {
        // TODO: Add the token to a blacklist here
        return response()->json(['message' => 'Goodbye!'], 200);

    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|unique:users',
            'password' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(),400);
        }

        $hashPass = app('hash')->make($request->password);

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' =>  $hashPass]
        ));

        return response()->json([
            'message' => 'User successfully created',
            'user' => $user
        ], 201);
    }


    function bcrypt($value, $options = [])
    {
        return app(‘hash’)->make($value, $options);
    }


}
