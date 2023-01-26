<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // <!-- register -->
    public function register(Request $request)
    {
        $fields = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'nickname' => 'required|string',
            'branch' => 'required|string',
            'department' => 'required|string',
            'tel' => 'required|string',
            'email' => 'required|string',
            'line_displayname' => 'required|string',
            'line_usrid' => 'required|string',
            'line_usrphoto' => 'required|string',
            'notify_token' => 'required|string',
            'verify' => 'required|string',
            'status' => 'required|string',
            'datetime' => 'required|string',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::create([
            'first_name' => $fields['first_name'],
            'last_name' => $fields['last_name'],
            'nickname' => $fields['nickname'],
            'branch' => $fields['branch'],
            'department' => $fields['department'],
            'tel' => $fields['tel'],
            'email' => $fields['email'],
            'line_displayname' => $fields['line_displayname'],
            'line_usrid' => $fields['line_usrid'],
            'line_usrphoto' => $fields['line_usrphoto'],
            'notify_token' => $fields['notify_token'],
            'verify' => $fields['verify'],
            'status' => $fields['status'],
            'datetime' => $fields['datetime'],
            'password' => bcrypt($fields['password']),
        ]);

        $token = $user->createToken($request->userAgent(), [$fields['first_name']])->plainTextToken;
        $res = [
            'user' => $user,
            'token' => $token
        ];
        return response($res, 201);
    }

    // <-- login with line_usrid  -->
    public function login(Request $request)
    {
        $fields = $request->validate([
            'line_usrid' => 'required',
        ]);
        $user = User::where('line_usrid', $fields['line_usrid'])->first();
        if (!$user) {
            return response([
                'message' => 'User not found'
            ], 404);
        }
        //delete token
        $user->tokens()->delete();
        $token = $user->createToken($request->userAgent(), [$user->id])->plainTextToken;
        $res = [
            'user' => $user,
            'token' => $token,
        ];
        return response($res, 201);
    }

    // <!-- logout -->
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        //response
        return response([
            'message' => 'Logged out'
        ]);
    }
}