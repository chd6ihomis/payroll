<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name') }}</title>
  <link rel="icon" href="{!! asset('/image/doh.ico') !!}"/>
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('css/plugin/fontawesome-free/css/all.min.css') }}">   
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/plugin/adminlte.css') }}">
  
</head>
<body class="hold-transition login-page">
<div class="login-box">
	<br>
	<br>
  <div class="login-logo">
  <img src="{{ asset('image/doh.png') }}" alt="DOH Logo" class="brand-image img-circle elevation-3 mt-2" style="opacity: .8; width:70px;height:70px">
    <a href="#">{{ 'Western Visayas CHD' }}</a>
	<!-- <img class="mb-2 mt-2" src="{{ asset('image/banner.png') }}" alt="" style="width: 100%;"> -->
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body card-purple card-outline">
      <p class="login-box-msg h5">{{ config('app.name') }}</p>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label text-sm-right">{{ __('Username') }}</label>

                <div class="col-md-8">
                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                    @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="password" class="col-md-4 col-form-label text-sm-right">{{ __('Password') }}</label>

                <div class="col-md-8">
                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required autocomplete="current-password">

                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6 offset-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                        <label class="form-check-label" for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-8 offset-md-4">
                    <button type="submit" class="btn btn-success btn-sm">
                        {{ __('Login') }}
                    </button>
                </div>
            </div>

        </form>
		
		
		</div>
	</div>
	 
	
</div>
<!-- /.login-box -->

    <!-- CORE JS -->
    <script src="{{ URL::asset('js/jquery-3.3.1.js') }}"></script>
     <script src="{{ URL::asset('js/bootstrap.min.js') }}"></script>
     
    <!-- AdminLTE App -->
    <script src="{{ asset('js/adminlte.js') }}"></script>
</body>
</html>
