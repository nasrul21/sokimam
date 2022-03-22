<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class KostController extends Controller
{
    public function index(Request $request)
    {
        $per_page = $request->query('per_page', 10);
        $kosts = Kost::query()->paginate($per_page);
        return response()->json($kosts, Response::HTTP_OK);
    }

    public function me(Request $request)
    {
        $userID = auth()->user()->id;
        $per_page = $request->query('per_page', 10);
        $kosts = Kost::where('owner_id', $userID)->paginate($per_page);
        return response()->json($kosts, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'type' => 'required|in:putri,putra,campur',
                'address' => 'required|min:10',
                'description' => 'required|min:30',
                'price' => 'required|numeric|min:0',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $userID = auth()->user()->id;

        try {
            $kost = Kost::create(array_merge(
                $validator->validated(),
                ['owner_id' => $userID],
            ));

            $response = [
                "message" => "Kost Created",
                "data" => $kost,
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function show($id)
    {
        $kost = Kost::find($id);

        if ($kost == null) {
            return response()->json(['message' => 'Kost not found'], 404);
        }

        return response()->json([
            'data' => $kost,
        ], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'type' => 'required|in:putri,putra,campur',
                'address' => 'required|min:10',
                'description' => 'required|min:30',
                'price' => 'required|numeric|min:0',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        try {
            $userID = auth()->user()->id;

            $kost = Kost::where('owner_id', $userID)->find($id);
            if ($kost == null) {
                return response()->json(['message' => 'Kost not found'], 404);
            }
            $kost->update($validator->validated());

            $response = [
                'message' => 'Kost updated',
                'data' => $kost,
            ];
            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function destroy($id)
    {
        $userID = auth()->user()->id;

        try {
            $kost = Kost::where('owner_id', $userID)->find($id);

            if ($kost == null) {
                return response()->json(['message' => 'Kost not found'], 404);
            }

            $kost->destroy($id);

            $response = [
                'message' => 'Kost deleted',
                'data' => $kost,
            ];
            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return Kost::where('owner_id', $userID)->find($id);
    }
}
