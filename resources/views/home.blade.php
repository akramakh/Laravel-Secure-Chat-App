@extends('layouts.app')

@section('content')
<style>
    
    }
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">My Chats ( {{count($chats["user"])}} )</div>
                <div class="card-body">
                    @for($i=0; $i < count($chats["user"]); $i++)
                    
                    <div class="row" style="border-bottom: 1px solid #ccc;">
                        <div class="col-md-6">
                            <div class="chat-item">
                                <div class="img-cont">
                                    <img src="img/personal/{{ $chats['user'][$i]->photo }}" />
                                </div>
                                <div class="info">
                                    <div class="name">
                                        {{$chats["user"][$i]->name}}
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3" style="padding:0">
                            <div class="opt">
                                <span style="font-size:9px;">{{$chats['chat'][$i]->created_at->diffForHumans()}}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="opt">
                                <a href="/chat/{{$chats['chat'][$i]->id}}"><button class="btn btn-success btn-block" >Open</button></a>
                            </div>
                        </div>
                    </div>
                    
                    @endfor
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ count($users) }} Active Users</div>

                <div class="card-body">
                   <div class="row">
                       @foreach($users as $user)
                       @if($user->id != Auth::id())
                   <div class="col-md-3">
                            <div class="user">
                                <div class="img-cont">
                                    <img src="img/personal/{{ $user->photo }}" />
                                </div>
                                <div class="info">
                                    <div class="name">
                                        {{ $user->name }}
                                    </div>
                                    <div class="opt">
                                        <button class="btn btn-success btn-block" data-toggle="modal" data-target="#modal" onclick="loadModalContent('/modals/start-chat/{{$user->id}}')">Start Chat</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                   
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
