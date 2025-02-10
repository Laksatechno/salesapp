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
        <h1>{{ __('Login') }}</h1>
    </div>
    <div class="section mb-5 p-2">
                @if(session('error'))
                <div class="alert alert-danger mt-3 text-center pb-1">
                    {{ session('error') }}
                </div>
                @endif 
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="card">
                        <div class="card-body pb-1">
                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label" for="email1">E-mail</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" placeholder="Masukan Email" value="{{ old('email') }}">
                                    <i class="clear-input">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M6.4 19L5 17.6l5.6-5.6L5 6.4L6.4 5l5.6 5.6L17.6 5L19 6.4L13.4 12l5.6 5.6l-1.4 1.4l-5.6-5.6z"/></svg>
                                    </i>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                            </div>

                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label" for="password1">Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" autocomplete="off"
                                        placeholder="Masukan Password">
                                    <i class="clear-input">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M6.4 19L5 17.6l5.6-5.6L5 6.4L6.4 5l5.6 5.6L17.6 5L19 6.4L13.4 12l5.6 5.6l-1.4 1.4l-5.6-5.6z"/></svg>
                                    </i>
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
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
                            <a href="register" style="text-decoration: underline;">Belum punya akun? </a>
                        </div>
                        <div><a href="forgot-password" class="text-muted">Lupa Password?</a></div>
                    </div>
                    <div class="form-button-group  transparent">
                        <button type="submit" class="btn btn-primary btn-block btn-lg">Log in</button>
                    </div>
                </form>
                
                

            {{-- <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body pb-1">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group basic">
                            <div class="input-wrapper">
                            <label for="email" class="form-label text-md-end">{{ __('Email Address') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>
                        </div>

                        <div class="form-group basic">
                            <div class="input-wrapper">
                            <label for="password" class="form-label text-md-end">{{ __('Password') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
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
                                <a href="register" style="text-decoration: underline;">Belum punya akun? </a>
                            </div>
                            <div><a href="forgot-password" class="text-muted">Lupa Password?</a></div>
                        </div>



                        <div class="form-boxed">
                            <div class="col-md-8 offset-md-4">

                                <div class="form-button-group transparent">
                                    <button type="submit" class="btn btn-primary btn-block"><ion-icon name="log-in-outline"></ion-icon> Masuk</button>
                                 </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div> --}}

    </div>
</div>
