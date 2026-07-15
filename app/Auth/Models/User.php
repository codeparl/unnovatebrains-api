<?php

namespace App\Auth\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
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

