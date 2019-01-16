@extends('layouts.app')

@section('content')
<section class="content-header">
	<h1>
		Proyecto
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-home"></i> Home</a></li>
		<li class="active">Registrar</li>
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
	
	<div class="box box-success">
		<div class="box-header with-border">
			<h3 class="box-title">Crear Proyecto</h3>
		</div>
		<form method="POST" role="form" action="/proyecto">
			{{ csrf_field() }}
			<div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group has-feedback {{ $errors->has('titulo') ? 'has-error': '' }}">
							<label>Título</label>
							<input type="text" class="form-control" placeholder="EJ: Proyecto de ...." name="titulo" autocomplete="off">
							@if ($errors->has('titulo'))
							<span class="help-block">
								<strong>{{ $errors->first('titulo') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group has-feedback {{ $errors->has('estudiante_id') ? 'has-error': '' }}">
							<label>Estudiante</label>
							<select class="form-control" name="estudiante_id" id="estudiante_id">
								<option value="" disabled selected hidden style="color: gray">Seleccione Estudiante...</option>
								@foreach($personas as $persona)
								<option value="{{ $persona['id'] }}"> {{ $persona['nombres'] }} {{ $persona['apellidos'] }}</option>
								@endforeach
							</select>
							<input type="hidden" name="nombre_estudiante" id="nombre_estudiante">
							@if ($errors->has('estudiante_id'))
							<span class="help-block">
								<strong>{{ $errors->first('estudiante_id') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group has-feedback {{ $errors->has('Curso') ? 'has-error': '' }}">
						<label> Curso </label>
							<select class="form-control" name="curso_id">
								<option value="1">Formulación de Proyecto de Titulación</option>
								<option value="2">Proyecto de Tìtulación</option>
							</select>
						</div>
						<div class="form-group has-feedback {{ $errors->has('tipo_id') ? 'has-error': '' }}">
							<label>Tipo</label>
							<select class="form-control" name="tipo_id">
								@foreach($tipos as $tipo)
								<option value="{{ $tipo->id }}">{{ $tipo->nombre() }}</option>
								@endforeach
							</select>
							@if ($errors->has('tipo_id'))
							<span class="help-block">
								<strong>{{ $errors->first('tipo_id') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group has-feedback {{ $errors->has('estado_id') ? 'has-error': '' }}">
							<label>Estado Estudiante</label>
							<select class="form-control" name="estado_id">
								@foreach($estados as $estado)
								<option value="{{ $estado->id }}">{{ $estado->nombre() }}</option>
								@endforeach
							</select>
							@if ($errors->has('estado_id'))
							<span class="help-block">
								<strong>{{ $errors->first('estado_id') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group has-feedback {{ $errors->has('semestre') ? 'has-error': '' }}">
						<label> Semestre </label>
							<select class="form-control" name="semestre">
								<option value="1">Primero</option>
								<option value="2">Segundo</option>
							</select>
						</div>

						<div class="form-group has-feedback {{ $errors->has('estado_id') ? 'has-error': '' }}">
							<label> Año </label>
							<input id="date_ini" name="year" class="form-control" placeholder="Año" autocomplete="off">
							@if ($errors->has('year'))
							<span class="help-block">
								<strong>{{ $errors->first('year') }}</strong>
							</span>
							@endif
						</div>

						<div class="form-group has-feedback {{ $errors->has('area_id') ? 'has-error': '' }}">
							<label>Area</label>
							<select class="form-control" name="area_id">
								@foreach($areas as $area)
								<option value="{{ $area->id }}">{{ $area->nombre() }}</option>
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
				<button type="submit" class="btn btn-success btn-flat pull-right"><i class="fa fa-save"></i> Crear </button>
			</div>
		</form>
	</div>
</section>
@endsection

@section('script')
<script>
	$('#estudiante_id').on('change', function() {
  		var thisvalue = $(this).find("option:selected").text();
  		console.log(thisvalue);
  		$('#nombre_estudiante').val(thisvalue);
	})
</script>
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