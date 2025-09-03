<?php

namespace App\Http\Controllers;

use App\Models\questionnaire;
use App\Models\userProfile;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function CreatUser(Request $request)
    {
        $request->validate([
            'firstName' => 'required|string|max:100',
            'lastName' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'inactive',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'user' => $user->makeHidden(['password']),
        ], 201);
    }

    public function UpdateProfilePhaseOne(Request $request)
    {
        $request->validate([
            'gender' => 'required|in:male,female,other',
            'dob' => 'required|date',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
        }

        $userProfile = userProfile::create([
            'user_ID' => $request->input('id'),
            'profileImage' => $path,
            'gender' => $request->input('gender'),
            'dob' => $request->input('dob')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'user' => $userProfile
        ], 201);
    }

    public function questionnaire(Request $request)
    {
        $request->validate([
            'user_ID' => 'required',
            'questions' => 'required|array',
            'questions.*.question' => 'required|string|max:300',
            'questions.*.answer' => 'required|string|max:300',
        ]);

        $questionsArray = $request['questions'];



        foreach ($questionsArray as $questionskeys) {
            questionnaire::create([
                'user_ID' => $request['user_ID'],
                'question' => $questionskeys['question'],
                'answer' => $questionskeys['answer']
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Question Attach To User',
        ], 201);
    }

    public function updateAfterProfile(Request $request)
    {
        $request->validate([
            'gender' => 'in:male,female,other',
            'dob' => 'date',
            'city' => 'string|max:100',
            'country' => 'string|max:100',
            'zipCode' => 'string|max:20',
            'address' => 'string|max:500',
        ]);

        $userProfile = userProfile::find($request['user_ID']);
        $userProfile->gender = $request['gender'] ?? $userProfile->gender;
        $userProfile->dob = $request['dob'] ?? $userProfile->dob;
        $userProfile->city = $request['city'] ?? $userProfile->city;
        $userProfile->country = $request['country'] ?? $userProfile->country;
        $userProfile->zipCode = $request['zipCode'] ?? $userProfile->zipCode;
        $userProfile->address = $request['address'] ?? $userProfile->address;
        $checkupdateStatus = $userProfile->save();

        if ($checkupdateStatus) {
            return response()->json([
                'success' => true,
                'message' => 'Profile Updated For Given User',
            ], 201);
        }
    }

    public function userForgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $token = Str::random(6);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => now()
            ]
        );

        // Send reset link (or token) via email
        // Example (you can customize later):
        Mail::raw("Your password reset token is: $token", function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Password Reset Request');
        });

        return response()->json([
            'success' => true,
            'message' => 'Password reset token sent to email.',
        ], 200);
    }

    public function userResetPasswotd(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'password' => 'required|string|min:6|confirmed', // password + password_confirmation
        ]);

        $reset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$reset) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token or email'
            ], 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete reset token (so it can't be reused)
        DB::table('password_resets')->where('email', $request->email)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password reset successful'
        ], 200);
    }
}
