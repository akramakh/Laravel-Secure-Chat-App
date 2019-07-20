<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    //
protected $fillable = [
    'creator_id', 'member_id', 'dim', 'key', 'iv'
];

    public function user(){
        return $this->belongsTo("App\User");
    }

    public function messages(){
        return $this->hasMany("App\Message");
    }
}
