<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use App\User;

class ModalController extends Controller
{
    //
    public function editPersonalPhoto(){
        return view("modals.editPersonalPhoto");
    }

    public function startChat($id){
        $user = User::find($id);
        return view("modals.startChat",compact("user"));
    }

    public function addUser(){
        return view('modals.admin.addUser');
    }
    public function deleteUser($id){
        $user = User::find($id);
        return view('modals.admin.deleteUser',compact('user'));
    }
    public function editUser($id){
        $user = User::find($id);
        return view('modals.admin.editUser',compact('user'));
    }
    
    public function addUserAjax(Request $r){
        $user = User::create([
            'name' => $r->name,
            'email' => $r->email,
            'password' => Hash::make($r->password),
        ]);
        if($user != null){
            return response('New User Added Successfuly');
        }
        
        else{
            return response('There is some errors');
        }
    }
    
    public function updateUserAjax(Request $r){
        $user = User::whereId($r->id)->update([
            'name' => $r->name,
            'email' => $r->email
        ]);
        if($user){
            return response('Update Successfuly');
        }
        
        else{
            return response('There is some errors');
        }
    }

    
    public function removeUserAjax(Request $r){
        $user = User::whereId($r->id)->delete();
        if($user){
            return response('Deleting Done Successfuly');
        }
        
        else{
            return response('There is some errors');
        }
    }
}
