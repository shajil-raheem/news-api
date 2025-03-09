<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User; 
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required','string','max:250'],
            'email' => ['required', 'email', 'max:250', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6']
        ]);
        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $data = $request->all();
        try {
            $data['password'] = Hash::make($data['password']);
            $data['email'] = strtolower($data['email']);
            User::create($data)->save();
        } catch (Exception $e) {
            return response()->json(['errors' => 'Internal Error Occured'], 500);
        }
        return response()->json(['status' => 'success'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $user = Auth::user();
        return response()->json([
            'user' => [
                'email' => $user->email,
                'name' => $user->name,
            ]
        ]);
    }
}
