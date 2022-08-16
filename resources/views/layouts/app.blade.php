<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Date Picker -->
    <!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">   -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">  
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
    <script src="//cdn.ckeditor.com/4.15.1/basic/ckeditor.js"></script>

    <script src="https://www.google.com/jsapi"></script>

    <style>
        .pie-chart {
            width: 600px;
            height: 400px;
            margin: 0 auto;
        }
        .text-center{
            text-align: center;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    INSHARP TECHNOLOGIES 
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <marquee width="20%" direction="Left" style="color: red;">
                <h6>
                @if(Auth::check())
                    {{ $note }}
                @endif
                </h6>
                </marquee>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <!-- <ul class="navbar-nav mr-auto">

                    </ul> -->

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <!-- <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li> -->
                            @endif
                        @else
                            <!-- <li class="nav-item">
                                <a class="nav-link" href="{{ url('/tasks') }}" role="button" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Daily Tasks
                                </a>
                            </li> -->
                            
                            @if(Auth::user()->email == "hr@insharptechnologies.com" || 
                            Auth::user()->email == "hr-mgr@insharptechnologies.com" || Auth::user()->email == "sithu.sithumi@gmail.com" || Auth::user()->email == "supunr@insharptechnologies.com")
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/users') }}" role="button" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Users
                                </a>
                            </li>
                            @endif

                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/tasks') }}" role="button" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Tasks
                                </a>
                            </li>
                            
                            <!-- <li class="nav-item">
                                <a class="nav-link" href="{{ url('/timesheets') }}" role="button" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Time Sheets
                                </a>
                            </li> -->
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/track') }}" role="button" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Time Sheets
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/velocity') }}" role="button" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <span style="font-weight: bold;color: red">Velocity</span>
                                </a>
                            </li>
                            @if(Auth::user()->email == "hr@insharptechnologies.com" || 
                            Auth::user()->email == "hr-mgr@insharptechnologies.com" || Auth::user()->email == "sithu.sithumi@gmail.com" || Auth::user()->email == "supunr@insharptechnologies.com")
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/leaves') }}" role="button" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Leaves
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->email == "supunr@insharptechnologies.com")
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/works') }}" role="button" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Works
                                </a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link" href="{{ url('/track') }}" role="button" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Track
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/tracktotal') }}" role="button" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Track Total
                                </a>
                            </li> -->
                            @endif
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/freeusers') }}" role="button" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Free Developers
                                </a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link" href="{{ url('/workhours') }}" role="button" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Work Hours
                                </a>
                            </li> -->
                            <!-- <li class="nav-item">
                                <a class="nav-link" href="{{ url('/samplechart') }}" role="button" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Sample Charts
                                </a>
                            </li> -->
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4" style="margin: 20px;">
            @yield('content')
        </main>
    </div>
    @yield('scripts')
</body>
<script type="text/javascript">
    $(document).ready(function () {
        
        $('.datepicker').datepicker({
            autoclose: true,  
            format: "dd/mm/yyyy",
            orientation: 'auto bottom',
        }); 

        $('.clear').click(
        function(){
            $('#active_date').val('');
        }); 
    
    });
</script>
</html>
