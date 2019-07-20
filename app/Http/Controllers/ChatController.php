<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Chat;
use App\User;
use App\Message;

class ChatController extends Controller
{

    // function to create a Chat between two different users
    public function create(Request $r){
        if($r->dim == 3){ // to check the key matrix dimension of the new chat
            $key = array(array($r->key[0],$r->key[2],$r->key[4]),
                        array($r->key[6],$r->key[8],$r->key[10]),
                        array($r->key[12],$r->key[14],$r->key[16]));
            $iv = strlen($r->iv) == 3 ? $r->iv : "xyz";
          }else{
            $key = array(array($r->key[0],$r->key[2]),
                  array($r->key[4],$r->key[6]));
            $iv = strlen($r->iv) == 2 ? $r->iv : "xy";
          }
        if($this->invertable($key, $r->dim) ){ //to check the key matrix of the new chat is invertable or not
            $tc = DB::select('select * from chats where (creator_id = ? AND member_id = ?) OR (creator_id = ? AND member_id = ?)',[$r->creator_id, $r->member_id, $r->member_id, $r->creator_id]);
            if(!$tc && ($r->creator_id != $r->member_id)){ // to avoid duplication of chats
                $chat = Chat::create([ // to create a new chate and insert into the database
                    "creator_id" => $r->creator_id, // first user
                    "member_id" => $r->member_id, // second user
                    "dim" => $r->dim, // key dimension
                    "key" => $r->key, // key matrix
                    "iv" => $iv // initial vector
                ]);
                if($chat != null) 
                    return response("Creating Done Successfuly"); // check if the chat created or not
                else  
                    return response("Creating Failed");
            }
            else{ 
                return response("This Chat is Already Created"); 
            }
        }
        else{ 
            return response("sorry! you can not use this Key"); 
        } 
    }

    // function to check if the key is invertable or not
    public function invertable($key, $dim){
        $det = $this->det($key, $dim);
        $gcd = $this->gcd($det, 89);
        return $det != 0 && $gcd == 1;
    }

    // function to compute the Grator Common Divider
    public function gcd($a, $b){
        $tmpa=$a; 
        $tmpb=$b; 
        while ($tmpb > 0){
            $r = $tmpa % $tmpb;
            $tmpa = $tmpb;
            $tmpb = $r;
        } 
        return $tmpa;
    }

    // function to compute the determenant of the key matrix
    public function det($array, $dim){
        $determinant = 0;
        if($dim == 2){
            $determinant = $array[0][0] * $array[1][1] -
            $array[0][1] * $array[1][0];
        }else{
            $leftElement = $array[0][0] * ($array[1][1] * $array[2][2] -
                  $array[1][2] * $array[2][1]);
            $middleElement = $array[0][1] * ($array[1][0] * $array[2][2] -
                  $array[1][2] * $array[2][0]);
            $rightElement = $array[0][2] * ($array[1][0] * $array[2][1] -
                  $array[1][1] * $array[2][0]);
      
            $determinant = $leftElement - $middleElement + $rightElement;
        }
        return $determinant; 
    }

    public function show($chat)
    {
        $chat = Chat::whereId($chat)->first();
        $uid = $chat->creator_id == Auth::user()->id ? $chat->member_id : $chat->creator_id;
        $user = User::whereId($uid)->first();
        $messages = Message::where('chat_id',$chat->id)->get();
        if($chat->dim == 3){
            $key = array(array($chat->key[0],$chat->key[2],$chat->key[4]),
                        array($chat->key[6],$chat->key[8],$chat->key[10]),
                        array($chat->key[12],$chat->key[14],$chat->key[16]));
          }else{
            $key = array(array($chat->key[0],$chat->key[2]),
                  array($chat->key[4],$chat->key[6]));
          }
        
        return view("chat.index",compact("user","messages","chat"));
    }
}
