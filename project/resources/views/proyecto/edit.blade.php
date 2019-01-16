 @extends('layouts.app')

@section('content')
<section class="content-header">
	<h1>
		Proyecto
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-home"></i> Home</a></li>
		<li class="active">Editar</li>
	</ol>
</section>

<section class="content">
	@if(session('message'))
	<div class="alert alert-{{ session('type') }} alert-dismissible">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<h4><i class="icon fa {{ session('icon') }}"></i> {{ session('title') }}</h4>
		{{ session('message') }}
	</div>
	@endif

	<div class="box box-warning">
		<div class="box-header with-border">
			<h3 class="box-title">Editar Proyecto</h3>
		</div>
		<form method="POST" role="form" action="/proyecto/update/{{ $proyecto->id }}">
			{{ csrf_field() }}
			<div class="box-body">
				<div class="row">
					<div class="col-md-12">

						<!-- Titulo -->
						<div class="form-group has-feedback {{ $errors->has('titulo') ? 'has-error': '' }}">
							<label>Título</label>
							<input type="text" class="form-control" name="titulo" value="{{ $proyecto->titulo }}" autocomplete="off">
							@if ($errors->has('titulo'))
							<span class="help-block">
								<strong>{{ $errors->first('titulo') }}</strong>
							</span>
							@endif
						</div>

						<!-- Estudiante-->
						<div class="form-group has-feedback {{ $errors->has('estudiante_id') ? 'has-error': '' }}">
							<label>Estudiante</label>
							<select class="form-control" name="estudiante_id">
								@foreach($estudiantes as $estudiante)
									@if($estudiante->id == $proyecto->estudiante_id)
										<option value="{{ $estudiante->id }}" selected="">{{ $estudiante->persona->nombres }}</option>
									@else
										<option value="{{ $estudiante->id }}">{{ $estudiante->persona->nombres }}</option>
									@endif
								@endforeach
							</select>
							@if ($errors->has('estudiante_id'))
							<span class="help-block">
								<strong>{{ $errors->first('estudiante_id') }}</strong>
							</span>
							@endif
						</div>

						<!-- Curso-->
						<div class="form-group has-feedback {{ $errors->has('Curso') ? 'has-error': '' }}">
							<label> Curso </label>
							<select class="form-control" name="curso_id">
							@foreach($cursos as $curso)
								@if($proyecto->curso_id == $curso->id)
									<option value="{{$curso->id}}" selected="">{{$curso->nombre}}</option>
								@else
									<option value="{{$curso->id}}" >{{$curso->nombre}}</option>
								@endif
							@endforeach
							</select>
						</div>

						<!-- Tipo -->
						<div class="form-group has-feedback {{ $errors->has('tipo_id') ? 'has-error': '' }}">
							<label>Tipo</label>
							<select class="form-control" name="tipo_id">
								@foreach($tipos as $tipo)
									@if($tipo->id == $proyecto->tipo_id)
										<option value="{{ $tipo->id }}" selected="">{{ $tipo->nombre() }}</option>
									@else
										<option value="{{ $tipo->id }}">{{ $tipo->nombre() }}</option>
									@endif
								@endforeach
							</select>
							@if ($errors->has('tipo_id'))
							<span class="help-block">
								<strong>{{ $errors->first('tipo_id') }}</strong>
							</span>
							@endif
						</div>

						<!-- Estado -->
						<div class="form-group has-feedback {{ $errors->has('estado_id') ? 'has-error': '' }}">
							<label>Estado Estudiante</label>
							<select class="form-control" name="estado_id">
								@foreach($estados as $estado)
									@if($estado->id == $proyecto->estado_id)
										<option value="{{ $estado->id }}" selected="">{{ $estado->nombre() }}</option>
									@else
										<option value="{{ $estado->id }}">{{ $estado->nombre() }}</option>
									@endif
								@endforeach
							</select>
							@if ($errors->has('estado_id'))
							<span class="help-block">
								<strong>{{ $errors->first('estado_id') }}</strong>
							</span>
							@endif
						</div>

						<!-- Semestre-->
						<div class="form-group has-feedback {{ $errors->has('semestre') ? 'has-error': '' }}">
							<label> Semestre </label>
							<select class="form-control" name="semestre">
								@if($proyecto->semestre == 1)
									<option value="1" selected="">Primero</option>
									<option value="2" >Segundo</option>
								@else
									<option value="1" >Primero</option>	
									<option value="2" selected="">Segundo</option>
								@endif
							</select>
						</div>

						<!-- Año -->
						<div class="form-group has-feedback {{ $errors->has('year_id') ? 'has-error': '' }}">
							<label> Año </label>
							<input id="date_ini" name="year" class="form-control" placeholder="Año" value="{{$proyecto->year}}" autocomplete="off">
							@if ($errors->has('year'))
							<span class="help-block">
								<strong>{{ $errors->first('year') }}</strong>
							</span>
							@endif
						</div>

						<!-- Area -->
						<div class="form-group has-feedback {{ $errors->has('area_id') ? 'has-error': '' }}">
							<label>Area</label>
							<select class="form-control" name="area_id">
								@foreach($areas as $area)
									@if($area->id == $proyecto->area_id)
										<option value="{{ $area->id }}" selected="">{{ $area->nombre() }}</option>
									@else
										<option value="{{ $area->id }}">{{ $area->nombre() }}</option>
									@endif
								@endforeach
							</select>
							@if ($errors->has('area_id'))
							<span class="help-block">
								<strong>{{ $errors->first('area_id') }}</strong>
							</span>
							@endif
						</div>


					</div>
				</div>
			</div>
			<div class="box-footer">
				<button type="submit" class="btn btn-warning btn-flat pull-right"><i class="fa fa-pencil"></i>Editar</button>
			</div>
		</form>
	</div>
</section>
@endsection

@section('script')
<script>
	$('#date_ini').datepicker({
		startView: 2,
		autoclose: true,
		language: 'es',
		format: 'yyyy',
		viewMode: 'years', 
    	minViewMode: 'years',
		orientation: 'bottom',
	});
</script>
@endsection