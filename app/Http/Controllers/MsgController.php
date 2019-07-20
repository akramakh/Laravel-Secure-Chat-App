<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Message;
class MsgController extends Controller
{
    //
    public function index(Request $r)
    {
        
        $msg = Message::create([
            "user_id" => $r->user_id,
            "chat_id" => $r->chat_id,
            "body" => $r->body
        ]);
        return response($msg->body);
        // if($msg != null)
        //     return response("Creating Done Successfuly");
        // else
        //     return response("Creating Failed");
    }
}
