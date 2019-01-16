@extends('layouts.auth')


@section('title')
SGC | Recuperar Contraseña
@endsection

@section('content')
<div class="login-box">
    <div class="login-logo">
        <a href="/"><b>SGC</b> Proyectos de título</a>
    </div>
    <div class="login-box-body">
        <p class="login-box-msg">Cambiar contraseña</p>

         <form  method="POST" action="{{ route('password.request') }}">
            {{ csrf_field() }}

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" class="control-label">Email</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus>

                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif

            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="password" class="control-label">Nueva Contraseña</label>
                <input id="password" type="password" class="form-control" name="password" required>

                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif

            </div>

            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                <label for="password-confirm" class="control-label">Confirme Contraseña</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

                @if ($errors->has('password_confirmation'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                @endif

            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">
                    Cambiar Contraseña
                </button>
            </div>
        </form>
    </div>
</div>
@endsection