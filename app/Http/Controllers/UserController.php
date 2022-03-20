<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6',
            'name' => 'required|min:3'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        try {
            $user = User::create(array_merge(
                $validator->validated(),
                ['password' => bcrypt($request->input('password'))]
            ));

            $response = [
                "message" => "Transaction created",
                "data" => $user,
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }



        return response()->json(['message' => 'Data added successfully'], 201);
    }
}
