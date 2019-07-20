<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Chat;
use App\Message;

class AdminController extends Controller
{
    //
    // public function __construct()
    // {
    //     $this->middleware('admin');
    // }

    public function index(){
        $users = User::all();
        $chats = Chat::all();
        $messages = Message::all();
        return view('admin.index',compact('users','chats','messages'));
    }
}
