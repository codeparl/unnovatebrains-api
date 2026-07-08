<?php

namespace App\Chat\Models;


use Illuminate\Database\Eloquent\Model;


class Conversation extends Model
{

    protected $table = 'conversations';


    protected $fillable = [
        'visitor_id',
        'status'
    ];


    public $timestamps = false;

}