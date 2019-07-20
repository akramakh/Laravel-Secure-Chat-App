<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Support\Facades\DB;
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'photo'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isAdmin(){
        $d = DB::select('select * from admin where user_id = ?',[$this->id]);
        if($d)
            return true;
        else
            return false;
    }

    public function chats(){
        return $this->hasMany("App\Chat");
    }
    
    public function messages(){
        return $this->hasMany("App\Message");
    }

    public function getName($id){
        return DB::select('select name from users where id = ?',$id);
        
    }
}
