<?php

namespace App\Auth\Models;

use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
    protected $table = 'api_tokens';

    protected $fillable = [
        'user_id',
        'token',
        'expires_at',
    ];

    public $timestamps = false;
}

