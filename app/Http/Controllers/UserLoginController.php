<?php

namespace App\Http\Controllers;

use App\Http\Controllers\UserController;
use App\Models\questionnaire;
use App\Models\userProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;



class UserLoginController extends Controller
{
    public function Userloing(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password',
            ], 401);
        }

        // Generate random token
        $user->api_token = Str::random(60);
        $userProfile = userProfile::where('user_ID', $user->id)->first();
        $userData = [
            'Data' => [
                'userBasic' => $user,
                'userProfile' => $userProfile,
            ]
        ];
        $user->status = "active";
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'user' => $userData,
        ], 200);
    }

    public function userLogout(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $user = User::where('api_token', $request->token)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token',
            ], 401);
        }

        // Remove token
        $user->api_token = null;
        $user->status = "inactive";
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ], 200);
    }

    
}
