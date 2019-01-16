@extends('layouts.auth')


@section('title')
SGC | Error 404
@endsection

@section('content')
<div class="login-box">
	<div class="login-logo">
		<a href="/"><b>SGC</b> Proyectos de título</a>
	</div>
	<div class="login-box-body">
		<span class="pull-left margin-right-5">
				<small class="label bg-purple pull-right"> <i class="fa fa-info padding-top-3"></i></small> 
			</span>
		<div class="note">
			@if(\Auth::user()->Invitado())
				<strong>Nota:</strong> Existen problemas para cargar el proyecto asociado a usted,contactece con su profesor guía del proyecto.
			@else
				<strong>Nota:</strong> Existen problemas para cargar su proyecto,contactece con su profesor guía.
			@endif
			
		</div>
		<p></p>
		<form id="logout-form" action="{{ route('logout') }}" method="POST">
            {{ csrf_field() }}
            <div class="form-group">
				<button type="submit" class="btn btn-primary btn-block">
					Cerrar sesión
				</button>
			</div>
        </form>
		
	</div>
</div>
@endsection
