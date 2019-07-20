@extends('layouts.app')
<style>
    .clear-fix{
        clear: both;
        display: block;
    }
    .personal-img{
        width: 100%;
        display: inline-flex;
    }
    .personal-img .img-wraper{
        width: 100%;
        height: 50%;
        background: #111111b3;
        z-index: 999;
        display: block;
        position: absolute;
        bottom: 0;
        right: 0;
        font-size:1.2em;
        text-align: center;
        padding: 15px;
    }
    .personal-img .img-container{
        width: 50px;
        height: 50px;
        margin: 0px 0 0 auto;
        display: block;
        float: none;
        position:relative;
        overflow:hidden;
        
    }
    .personal-img .info{
        /* width: 50px; */
        height: 50px;
        margin: 0px auto 0 10px;
        display: block;
        float: none;
        position:relative;
        overflow:hidden;
        line-height: 50px;
        text-transform: uppercase;
    }
    
    .personal-img .img-wraper:hover{
        cursor:pointer;
        font-size:1.5em;
        padding: 11px;
    }
    .card{
        height: 84%;
    }
    .card .card-header{
        padding: 5px;
    }
    .card .card-body{
        overflow-y: auto;
    }
    .card .card-footer{
        padding-bottom: 0px;
    }
    .me{
        width: 100%;
        height: auto;
    }
    .me .covor{
        /* padding: 5px; */
        border-radius: 16px 0 0 16px;
        overflow: hidden;
        /* background: #E0E0E0; */
    }
    .me .img-covor{
        /* padding: 5px; */
        border-radius: 16px 16px 16px 0px;
        overflow: hidden;
        max-width: 300px;

        /* background: #E0E0E0; */
        /* width: 300px; */
        /* height: 300px; */
        /* display: inline-table; */
 
    }
    .me .img-covor .img > img,
    .you .img-covor .img > img,
    #profile_img > img{
        width: 100%;
        object-fit: cover;
        object-position: center;
    }
    .me .covor .item{
        padding: 5px 10px;
        border-radius: 0 16px 16px 0;
        background: #E0E0E0;
        margin-bottom: 1px;
        width: fit-content;
        overflow-wrap: break-word;
        max-width: 400px;
    }
    .me .covor .date,
    .me .img-covor .date{
        padding: 3px 5px;
        border-radius: 0 12px 12px 0;
        background: #efefef;
        margin-bottom: 7px;
        width: fit-content;
        overflow-wrap: break-word;
        max-width: 400px;
        font-size: 9px;
    }
    .you{
        width: 100%;
        display: inline-flex !important;
        float: right;
        position: relative;
        min-height: 60px;
        max-width: 60%;
    }
    .you .covor{
        border-radius: 0 16px 16px 0;
        overflow: hidden;
        position: absolute;
        right: 0;
        margin-bottom: 7px;
    }
    .you .img-covor{
        /* position: absolute; */
        /* right: 0; */
        border-radius: 16px 0 0 16px;
        overflow: hidden;
        width: 100%;
        max-height: 400px;
        float: right;
        margin-bottom: 7px;
    }
    .you .covor .item{
        border-radius: 16px 0 0 16px;
        text-align: right;
        /* direction: rtl; */
        padding: 5px 15px 5px 10px;
        background: #2196f3;
        color: #fff;
        margin-bottom: 1px;
        /* width: fit-content; */
        overflow-wrap: break-word;
        max-width: 400px;
    }
    .you .covor .date,.you .img-covor .date{
        padding: 3px 7px;
        text-align: right;
        border-radius: 12px 0 0 12px;
        background: #efefef;
        float: right;
        width: fit-content;
        overflow-wrap: break-word;
        max-width: 400px;
        font-size: 9px;
    }
    
    #textarea{
        resize: none;
        overflow-y: auto;
    }

    #profile_img{
        width:150px;
        margin-bottom:5px;
    }
</style>

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-2">
                <!-- <form method="POST" action="/msgs/dec"> -->
                    <!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"/> -->
                    <!-- <input type="hidden" name="chat_id" value="{{ $chat->id }}"/> -->
                    <input id="dec_btn" type="button" class="btn btn-success" value="Decrypt all" onclick="decAll();">
                <!-- </form> -->
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="personal-img">
                        <div class="img-container">
                            <img src="/img/personal/{{ $user->photo }}" />
                        </div>
                        <div class="info">{{ $user->name }}</div>
                    </div>
                    
                </div>

                <div class="card-body">
                    <div class="containermessages "> 
                    @if(count($messages) > 0)                           
                        @foreach($messages as $msg)
                        @if($msg->user_id == Auth::user()->id)
                            <div class="msg me row">
                                <div class="covor">
                                    <div class="item">{{$msg->body}}</div>
                                    <div class="date">{{$msg->created_at->diffForHumans()}}</div>
                                </div>
                            </div>
                            @else
                            
                            <div class="msg you row">
                                <div class="covor">
                                    <div class="item">{{$msg->body}}</div>
                                    <div class="date">{{$msg->created_at->diffForHumans()}}</div>
                                </div>
                            </div>
                            
                            @endif
                            @endforeach
                            @endif
                    </div>
                </div>
                <div class="card-footer">
                    <form>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <div id="profile_img" style="display:none;"><img src="" class="img"></div>
                        <div class="form-group row">
                            <div class="input-btn col-md-1">
                                <i class="fa fa-photo"></i>
                                <input type="file" name="img" id="img" onchange="imagePreview1(this);">
                            </div>
                            
                            <textarea id="textarea" class="col-md-9 " style="border-radius: 3px 0 0 3px; " placeholder="type message ..." onkeyup="checkText();" ></textarea>
                            <input type="button" id="send_btn" class="btn btn-primary col-md-2" name="update" value="Send" style="border-radius: 0 3px 3px 0; " onclick="send();" disabled>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
var load_url = '/test-load/enc';
var img = "";

function send(){
    var msg = $("#textarea").val();
    // alert(msg);
    if(msg != ""){
        $.ajax({
            method:'POST',
            url:'/msg/create',
            dataType:'html',
            data:{
                '_token': "{{ csrf_token() }}",
                'user_id':"{{ Auth::user()->id }}",
                'chat_id': "{{ $chat->id }}",
                'body':msg
            },
            success:function (data) {
                console.log(data);
                $(".containermessages").append(
                    '<div class="msg me row">'+
                        '<div class="covor">'+
                            '<div class="item">'+data+'</div>'+
                        '</div>'+
                    ' </div>');
                $("#textarea").val("");
            }
        });
    }

}

function sendImage(){
    // console.log(img);
    $.ajax({
        method:'POST',
        url:'/msg/img/create',
        dataType:'html',
        data:{
            '_token': "{{ csrf_token() }}",
            'user_id':"{{ Auth::user()->id }}",
            'chat_id': "{{ $chat->id }}",
            'img':img
        },
        success:function (data) {
            // console.log(data);
            $(".containermessages").append(
                '<div class="msg me row">'+
                    '<div class="img-covor">'+
                        '<div class="img"><img src="/img/msg/'+data+'.jpg"></img></div>'+
                    '</div>'+
                ' </div>');
                $('#profile_img').hide();
                $('#send_btn').attr("disabled",'disabled');
                $('#send_btn').attr('onclick','send();');
                
        }
    });

}

function imagePreview1(input){
    if(input.files && input.files[0]){
        var reader = new FileReader();
        reader.onload = function(e){
            $('#profile_img > .img').attr('src',e.target.result);
            $('#profile_img').show();
            img = reader.result;
            // console.log(img);
        }
        reader.readAsDataURL(input.files[0]);
        
        $('#send_btn').removeAttr('disabled');
        $('#send_btn').attr('onclick','sendImage();');
    }
}

function checkText(){
    var msg = $("#textarea").val();
    var btn = $("#send_btn");
    if(msg.trim() != ""){
        btn.removeAttr("disabled");
        $('#send_btn').attr('onclick','send();');
    }else{
        btn.attr("disabled",'disabled');
    }
}



function decAll(){
        
        $.ajax({
            method:'POST',
            url:'/test-load/dec',
            dataType:'html',
            data:{
                '_token': "{{ csrf_token() }}",
                'chat_id': "{{ $chat->id }}"
            },
            success:function (data) {
                $(".containermessages").html(data);
            }
        });
        load_url = '/test-load/dec';  
        $("#dec_btn").attr("onclick","encAll();");  
        $("#dec_btn").attr("class","btn btn-danger");
        $("#dec_btn").attr("value","Encrypt All");
    }

function encAll(){
    
    $.ajax({
        method:'POST',
        url:'/test-load/enc',
        dataType:'html',
        data:{
            '_token': "{{ csrf_token() }}",
            'chat_id': "{{ $chat->id }}"
        },
        success:function (data) {
            $(".containermessages").html(data);
        }
    });
    load_url = '/test-load/enc';  
    $("#dec_btn").attr("onclick","decAll();");  
    $("#dec_btn").attr("class","btn btn-success");
    $("#dec_btn").attr("value","Decrypt All");
}
setInterval(function(){
    $.ajax({
        method:'POST',
        url:load_url,
        dataType:'html',
        data:{
            '_token': "{{ csrf_token() }}",
            'chat_id': "{{ $chat->id }}"
        },
        success:function (data) {
            // console.log(data);
            $(".containermessages").html(data);
        }
    });
},4000);
</script>
@endsection
