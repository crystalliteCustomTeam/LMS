<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class questionnaire extends Model
{
    protected $fillable = [
        'user_ID',
        'question',
        'answer',
    ];
    protected $table = 'user_questions';
}
