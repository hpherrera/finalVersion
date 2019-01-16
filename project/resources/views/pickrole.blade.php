@extends('layouts.auth')

@section('content')

<div class="login-box">
	<div class="login-logo">
		<a href="/"><b>SGC</b> Proyectos de título</a>
	</div>
	<div class="login-box-body">
		<p class="login-box-msg">Selecciona tu rol para continuar.</p>

		<div class="row">
		@foreach($user->roles as $rol)
			<div class="col-xs-12">
				<form method="POST" action="/login_with_role">
					{{ csrf_field() }}
					<input type="hidden" name="user" value="{{ $user->id }}">
					<input type="hidden" name="rol" value="{{ $rol->id }}">
					<button type="submit" class="btn btn-primary btn-block"> Entrar como {{ $rol->nombre() }}</button>
					<br>
				</form>
			</div>
		@endforeach
		</div>
		
	</div>
</div>

@endsection