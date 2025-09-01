<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class userProfile extends Model
{
    protected $fillable = [
        'user_ID',
        'profileImage',
        'gender',
        'dob',
        'userType',
        'city',
        'country',
        'zipCode',
        'address',
    ];
    protected $table = 'user_profile';
}
