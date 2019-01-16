@extends('layouts.auth')

@section('title')
SGC | Cambiar Contraseña
@endsection

@section('content')
<div class="login-box">
    <div class="login-logo">
        <a href="/"><b>SGC</b> Proyectos de título</a>
    </div>
    <div class="login-box-body">
        <p class="login-box-msg">Ingres su nueva contraseña</p>
        @if(session('message'))
            <div class="alert alert-{{ session('type') }} alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa {{ session('icon') }}"></i> {{ session('title') }}</h4>
                {{ session('message') }}
            </div>
        @endif
        <div class="row">
            <div class="col-xs-12">
                <form method="POST" action="/change">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" class="control-label">Nueva Contraseña</label>
                        <input id="password" type="password" class="form-control" name="password1" required>

                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <label for="password-confirm" class="control-label">Confirme Contraseña</label>
                        <input id="password-confirm" type="password" class="form-control" name="password2" required>

                        @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif
                    </div>
                    <input type="hidden" name="user_id" value="{{$user->id}}">
 
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block" >
                            Cambiar contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</div>
@endsection