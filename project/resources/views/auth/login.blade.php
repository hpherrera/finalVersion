@extends('layouts.auth')


@section('title')
SGC | Iniciar sesión
@endsection

@section('content')
<body background="{{asset('img/campus1.jpg')}}" bgcolor="FFCECB"> 
<div class="login-box">
	<div class="login-logo">
		<a href="/"><b>SGC</b> Proyectos de título</a>
	</div>
	<div class="login-box-body">
		<p class="login-box-msg">Ingresa tus datos para iniciar sesión.</p>

		<form method="POST" action="{{ route('login') }}">
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

			<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
				<label for="password" class="control-label">Contraseña</label>
				<input id="password" type="password" class="form-control" name="password" required>
				@if ($errors->has('password'))
				<span class="help-block">
					<strong>{{ $errors->first('password') }}</strong>
				</span>
				@endif
			</div>

			<div class="form-group">
				<button type="submit" class="btn btn-primary btn-block">
					Iniciar sesión
				</button>
			</div>

			<div class="form-group">
				<a class="btn btn-link" href="{{ route('password.request') }}">Olvidó su contraseña?</a>
			</div>
		</form>
	</div>
</div>
</body>
@endsection
