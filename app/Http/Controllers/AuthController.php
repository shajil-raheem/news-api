<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * get token
     */
    function auth(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);
        if($validator->fails()) {
            return response()->json([
                'message' => 'Invalid Credentials',
                'errors' => $validator->errors()
            ], 400);
        }
        $data = $request->all();
        if(!Auth::attempt($data)) {
            return response()->json(['message' => 'Invalid Credentials'], 401);
        }
        $user = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json([
            'message' => 'Authentication Success',
            'token' => $token,
            'user' => [
                'email' => $user->email,
                'name' => $user->name,
            ]
        ]);
    }
}
