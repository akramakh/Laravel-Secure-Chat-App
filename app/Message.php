<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //
    protected $fillable = [
        'chat_id', 'user_id', 'body', 'is_photo'
    ];

    public function chat(){
        return $this->belongsTo("App\Chat");
    }
}
