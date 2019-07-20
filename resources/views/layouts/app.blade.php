<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="{{ asset('img/favicon.jpg') }}" type="image/x-icon" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/jquery.min.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <!-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css"> -->

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/nova.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Nova') }}
                </a>
                <div class="search">
                    <input type="search" name="search" placeholder="Search">
                    <span><i class="fa fa-search"></i></span>
                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                    
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            <li class="nav-item">
                                @if (Route::has('register'))
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                @endif
                            </li>
                        @else
                            <li class="nav-item ma-r-3x">
                                <a class="" href="{{ url('/home') }}">
                                    Home
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" style="padding: 0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <span class="caret"></span>   
                                    <div class="img-container"><img src="/img/personal/{{ Auth::user()->photo }}" alt="Avatar"></div> 
                                    {{ Auth::user()->name }} 
                                     
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    @if(Auth::user()->isAdmin())
                                    <a class="dropdown-item" href="/manage">
                                        Dashboard
                                    </a>
                                    @endif
                                    <a class="dropdown-item" href="{{ route('settings') }}">
                                        Settings
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
            <!--Modal-->
            <div id="modal" class="modal fade small" role="dialog">
                    <div class="modal-dialog">

                    </div>
                </div>
        </main>
    </div>
</body>
<script>
    function removeAlert(){
        $('.modal-alert').text('');
        $('.modal-alert').hide();
    }
    function loadModalContent(url){
        jQuery.get(url, function (data) {
            $("#modal > .modal-dialog").html(data);
        });
    }
    function imagePreview(input){
        if(input.files && input.files[0]){
            var reader = new FileReader();
            reader.onload = function(e){
                $('#profile_img').attr('src',e.target.result);
                $('#profile_img').show();
            }
            reader.readAsDataURL(input.files[0]);
            $('#add-photo-btn').removeAttr('disabled');
        }
    }

    function videoPreview(input){
        if(input.files && input.files[0]){
            var reader = new FileReader();
            reader.onload = function(e){
                $('#video_intro').attr('src',e.target.result);
                $('#video_intro').show();
            }
            reader.readAsDataURL(input.files[0]);
            $('#add-video-btn').removeAttr('disabled');
        }
    }

    /******************************************** */

        function addUser(e){
        removeAlert();
        var name = $('#name');
        var email = $('#email');
        var password = $('#password');
        var password_confirm = $('#password-confirm');
        $.ajax({
            method:'POST',
            url:'/add-user-ajax',
            dataType:'html',
            data:{
                '_token': "{{ csrf_token() }}",
                'name':name.val(),
                'email':email.val(),
                'password':password.val(),
                'password_confirmation':password_confirm.val()
            },
            success:function (data) {
                $('.modal-alert').show();
                $('.modal-alert').text(data);
            }
        });
    }    
        
    function deleteUser(id){
        removeAlert();
        $.ajax({
            method: 'POST',
            url: 'remove-user-skill/',
            dataType: 'html',
            data: {
                '_token': "{{ csrf_token() }}",
                'id': id
            },
            success: function(data){
                $('.modal-alert').show();
                $('.modal-alert').text(data);
//       $('.post' + $('.id').text()).remove();
            }
        });
    }    
        
    function updateUser(id, score){
        removeAlert();
        $.ajax({
            method: 'POST',
            url: 'update-user-skill/',
            dataType: 'html',
            data: {
                '_token': "{{ csrf_token() }}",
                'id': id,
                'score': score
            },
            success: function(data){
                $('.modal-alert').show();
                $('.modal-alert').text(data);
//       $('.post' + $('.id').text()).remove();
            }
        });
    }
        
        /*****************************************************/
</script>
</html>
