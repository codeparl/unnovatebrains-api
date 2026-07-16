<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    
 use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    public $timestamps = false;

    public static function findByEmail(string $email): ?self
    {
        $user = self::where('email', $email)->first();
        return $user ?: null;
    }

    public static function findById(int $id): ?self
    {
        $user = self::find($id);
        return $user ?: null;
    }
}

