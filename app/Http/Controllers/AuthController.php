<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
                'message' => 'Invalid Inputs',
                'errors' => $validator->errors()
            ], 422);
        }
        $data = $request->all();
        if(!Auth::attempt($data)) {
            return response()->json(['message' => 'Invalid E-mail or Password'], 401);
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
        ], 201);
    }

    /**
     * get password reset token
     */
    function getPasswordResetToken(Request $request) {
        $validator = Validator::make($request->all(),[
            'email' => ['required', 'email']
        ]);
        if($validator->fails()) {
            return response()->json([
                'message' => 'Invalid Inputs',
                'errors' => $validator->errors()
            ], 422);
        }
        $exists = User::where('email', $request->only('email'))->exists();
        if(!$exists) {
            return response()->json([
                'message' => 'Invalid Email Address',
            ], 422);
        }
        $status = Password::sendResetLink(
            $request->only('email')
        );
 
        return response()->json(['message' => 'Success'], 200);
    }

    /**
     * reset password
     */
    function resetPassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => ['required'],
            'token' => ['required'],
            'password' => ['required', 'min:6'],
            'password_confirmation' => ['required', 'min:6'],
        ]);
        if($validator->fails()) {
            return response()->json([
                'message' => 'Invalid Inputs',
                'errors' => $validator->errors()
            ], 422);
        }
        try {
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function (User $user, string $password) {
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ]);
                    $user->save();
                }
            );
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Inernal Error Occured',
            ], 500);
        }
        // dd($status);
        if($status !== Password::PasswordReset) {
            return response()->json([
                'message' => 'Password Reset Failed',
                'details' => __($status)
            ], 401);
        }
        return response()->json(['message' => 'Password Reset Successful'], 200);
    }
}
