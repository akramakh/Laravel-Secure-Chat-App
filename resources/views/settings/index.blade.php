@extends('layouts.app')
<style>
    .personal-img{
        width: 100%;
        display: inline-block;
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
        width: 200px;
        height: 200px;
        margin: 10px auto;
        display: block;
        float: none;
        position:relative;
        overflow:hidden;
        
    }
    
    .personal-img .img-wraper:hover{
        cursor:pointer;
        font-size:1.5em;
        padding: 11px;
    }
</style>

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Settings</div>

                <div class="card-body">
                    <div id="containerAccountSettings ">
                    <h4 class=""><strong>Personal Photo</strong></h4>
                            <div class="personal-img">
                                <div class="img-container">
                                    <div class="img-wraper" data-toggle="modal" data-target="#modal" onclick="loadModalContent('/modals/edit-personal-photo')">
                                        <i class="fa fa-camera"></i>
                                    </div>
                                    <img src="img/personal/{{ Auth::user()->photo }}" />
                                </div>
                            </div>
                        <form method="post" action="update-user">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                            <!-- <div class="alert alert-success">{{ isset($msg) }}</div> -->
                            <!-- <h4 class=""><strong>Personal Information</strong></h4> -->
                                <div class="form-group row">
                                    <label for="address" class="col-md-3">Address</label>
                                    <input type="text" class="col-md-8" id="address" name="name" value="{{$user->name}}">
                                </div>
                                <div class="form-group row">
                                    <label for="address" class="col-md-3">Address</label>
                                    <input type="text" class="col-md-8" id="address" name="email" value="{{$user->email}}">
                                </div>
                                
                                <input type="submit" class="btn btn-primary" name="update" value="Update">
                        </form>
                    
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
