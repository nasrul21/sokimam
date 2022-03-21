<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $userRequest = $request->only('email', 'password', 'name');
        $userRole = $request->input('role');
        $validator = Validator::make(
            array_merge($userRequest, ['role' => $userRole]),
            [
                'email' => 'required|unique:users|email',
                'password' => 'required|min:6',
                'name' => 'required|min:3',
                'role' => 'required|in:owner,regular,premium',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        try {
            $user = User::create(array_merge(
                $userRequest,
                ['password' => bcrypt($request->input('password'))]
            ));

            $user->assignRole($userRole);

            $response = [
                "message" => "Register Completed",
                "data" => [
                    'id' => $user->id
                ],
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
