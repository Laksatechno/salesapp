<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover">
    <title>{{ config('app.name') }}</title>
    <meta name="theme-color" content="#630051">
    <meta name="msapplication-navbutton-color" content="#630051">
    <meta name="apple-mobile-web-app-status-bar-style" content="#630051">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicons -->
    <link rel="shortcut icon" href="{{ url('/content/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ url('/content/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ url('/ontent/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ url('content/favicon.png') }}">

    <meta name="robots" content="index, follow">
    <meta name="description" content="{{ config('app.meta_description') }}">
    <meta name="keywords" content="{{ config('app.meta_keywords') }}">
    <meta name="author" content="{{ config('app.name') }}">
    <meta http-equiv="Copyright" content="{{ config('app.name') }}">
    <meta name="copyright" content="{{ config('app.name') }}">
    <meta itemprop="image" content="content/meta-tag.jpg">

    <link rel="stylesheet" href="{{ url('/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ url('/assets/css/custom.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    
    @if(request()->is('history'))
        <link rel="stylesheet" href="{{ url('/assets/js/plugins/datepicker/datepicker3.css') }}">
        <link rel="stylesheet" href="{{ url('/assets/js/plugins/datatables/dataTables.bootstrap.css') }}">
        <link rel="stylesheet" href="{{ url('/assets/js/plugins/magnific-popup/magnific-popup.css') }}">
    @endif
    <link rel="manifest" href="__manifest.json"> 
</head>

<body>
    <div class="loading">
        <div class="spinner-border text-primary" role="status"></div>
    </div>
<div id="appCapsule">

    <div class="section mt-2 text-center">
        <h1>Masuk</h1>
        <h4>Isi formulir untuk masuk</h4>
    </div>
    <div class="section mb-5 p-2">
                @if(session('error'))
                <div class="alert alert-danger mt-3 text-center pb-1">
                    {{ session('error') }}
                </div>
                @endif 
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body pb-1">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group basic">
                            <div class="input-wrapper">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        </div>

                        <div class="form-group basic">
                            <div class="input-wrapper">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        </div>

                        <div class="form-basic">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-links mt-2">
                            <div>
                                <a href="register">Mendaftar</a>
                            </div>
                            <div><a href="forgot-password" class="text-muted">Lupa Password?</a></div>
                        </div>



                        <div class="form-boxed">
                            <div class="col-md-8 offset-md-4">
                                {{-- <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button> --}}

                                <div class="form-button-group transparent">
                                    <button type="submit" class="btn btn-primary btn-block"><ion-icon name="log-in-outline"></ion-icon> Masuk</button>
                                 </div>

                                {{-- @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif --}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>

    </div>
</div>
