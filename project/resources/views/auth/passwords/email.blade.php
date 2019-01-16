@extends('layouts.auth')


@section('title')
SGC | Recuperar Contraseña
@endsection

@section('content')
<div class="login-box">
    <div class="login-logo">
        <a href="/"><b>SGC</b> Proyectos de título</a>
    </div>
    @if(session('message'))
    <div class="alert alert-{{ session('type') }} alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa {{ session('icon') }}"></i> {{ session('title') }}</h4>
        {{ session('message') }}
    </div>
    @endif
    <div class="login-box-body">
        <p class="login-box-msg">Ingrese su email para recuperar contraseña</p>

        <form method="POST" action="{{ route('password.email') }}">
            {{ csrf_field() }}

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" class="control-label">Email</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">
                    Recuperar contraseña
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
