<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;

use App\User;
use App\Photo;
use App\Chat;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $chats = array("chat"=> array(),
                       "user"=> array());
        $user = Auth::user();
        $users = User::all();
        
        $chat = Chat::where('creator_id',$user->id)->get();
        foreach($chat as $c){
            $uid = $c->member_id;
            $u = User::whereId($uid)->first();
            array_push($chats["chat"],$c);
            array_push($chats["user"],$u);
        }
        // 
        $chat = Chat::where('member_id',$user->id)->get();
        foreach($chat as $c){
            $uid = $c->creator_id;
            $u = User::whereId($uid)->first();
            array_push($chats["chat"],$c);
            array_push($chats["user"],$u);
        }
        // return $chats;
        return view('home',compact('chats','users'));
    }

    public function updateUser(Request $req){
        Auth()->user()->update([
            'name' => $req->name,
            'email' => $req->email,
        ]);
        return redirect('/settings');
    }

    public function updatePersonalPhoto(Request $request){
        if($request->isMethod('post')) {
            $user = Auth::user();
            $file = $request->file('personal_photo');
            $filename = $user->id . '/personal.jpg';
            if ($file) {
                Storage::disk('personal')->put($filename, File::get($file));
                $user->update([
                    'photo' => $filename
                ]);
            }
            return redirect('/settings');
        }
    }

    public function testPhoto64(Request $request)
    {
        if($request->isMethod('post')) {
            $user = Auth::user();
            $file = $request->file('personal_photo');
            // $filename = $user->id . '/personal.jpg';
            if ($file) {
                // Storage::disk('personal')->put($filename, File::get($file));
                // $user->update([
                //     'photo' => $filename
                // ]);
                $base = base64_encode(File::get($file));
            }
            return response($base);
        }
    }

    public function img64(Request $r)
    {
   
        $enc_img = "";
        $cont = explode('/', explode(',', $r->cont)[1]);
        for($i = 2 ; $i < count($cont) ; $i++){
            // echo $cont[$i].'<br>';
            $enc_img .= '/'.base64_encode($cont[$i]);
            // $enc_img .= '/'.$cont[$i];
        }

        $img = Image::make('/9j'.$enc_img);
        return $img->response('jpg');
    }

}
