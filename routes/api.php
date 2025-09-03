<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\UserLoginController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

Route::post('/user/create', [UserController::class, 'index']);
Route::post('/user/update/profile', [UserController::class, 'UpdateProfilePhaseOne']);
Route::post('/user/questionnaire', [UserController::class, 'questionnaire']);
Route::put('/user/update/afterprofile', [UserController::class, 'updateAfterProfile']);
Route::post('/user/login', [UserLoginController::class, 'Userloing']);
Route::post('/user/logout', [UserLoginController::class, 'userLogout']);
Route::post('/user/forget-password', [UserLoginController::class,'userForgetPassword']);
Route::post('/user/reset-password', [UserLoginController::class,'userResetPasswotd']);
