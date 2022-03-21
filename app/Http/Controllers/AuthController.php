<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        $validator = Validator::make(
            array_merge($credentials, ['role' => $request->input('role')]),
            [
                'email' => 'required',
                'password' => 'required',
                'role' => 'required|in:owner,regular,premium',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Invalid Email / Password'], 401);
        }

        $userRole = auth()->user()->roles()->first();
        if ($userRole && $userRole->name == $validator->validated()['role']) {
            return response()->json(['error' => 'Invalid User Role'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
        ]);
    }
}
