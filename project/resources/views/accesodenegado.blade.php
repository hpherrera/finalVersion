@extends('layouts.auth')

@section('title')
SGC | Error Acceso Denegado
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
			@if($tipo == 1)
				<strong>Nota:</strong> Esta tratando de acceder a un hito que no se encuentra en sus registros.
			@elseif($tipo == 2)
				<strong>Nota:</strong> Esta tratando de acceder a una tarea que no se encuentra en sus registros.
			@elseif($tipo == 3)
				<strong>Nota:</strong> Esta tratando de acceder a una acción que no esta permitida para usted.
			@else
				<strong>Nota:</strong> Esta tratando de acceder a un entregable que no se encuentra en sus registros.
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