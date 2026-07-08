<?php

namespace App\Chat\Models;


use Illuminate\Database\Eloquent\Model;


class Message extends Model
{

    protected $table = 'messages';


    protected $fillable = [
        'conversation_id',
        'sender',
        'message'
    ];


    public $timestamps = false;

}