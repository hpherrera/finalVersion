@extends('layouts.app')

@section('content')

<section class="content-header">
	<h1>
		Entregable
		<small>Editar</b></small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-home"></i> Home</a></li>
		<li class="active">Crear</li>
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
			<h3 class="box-title">Crear Entregable </h3>
		</div>
		<form form method="POST" role="form" action="/entregable/update/{{ $entregable->id }}" enctype="multipart/form-data">
			{{ csrf_field() }}
			<div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group has-feedback {{ $errors->has('tarea_id') ? 'has-error': '' }}">
							<label>Tarea</label>
							<select class="form-control" name="tarea_id">
								@foreach($tareas as $tarea)
								<option value="{{ $tarea->id }}">{{ $tarea->nombre}}</option>
								@endforeach
							</select>
							@if ($errors->has('tarea_id'))
							<span class="help-block">
								<strong>{{ $errors->first('tarea_id') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group has-feedback {{ $errors->has('nombre') ? 'has-error': '' }}">
							<label>Nombre</label>
							<input type="text" class="form-control" value="{{$entregable->nombre}}" name="nombre" autocomplete="off">
							@if ($errors->has('nombre'))
							<span class="help-block">
								<strong>{{ $errors->first('nombre') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group">
				          	<label for="exampleInputFile">Archivo a subir (Formato PDF)</label>
				          	<input type="file" id="archivo" name="archivo" required>
				          	<p class="help-block">Archivo subido : {{$entregable->nombre}}</p>
						</div>
					</div>
					<input type="hidden" name="tarea" value="{{$entregable->tarea_id}}">
    				<input type="hidden" name="hito" value="{{$entregable->tarea->hito->id}}">
				</div>
			</div>
			<div class="box-footer">
				<button type="submit" class="btn btn-warning btn-flat pull-right"><i class="fa fa-pencil"></i> Editar </button>
			</div>
		</form>
	</div>
</section>

@endsection
@section('script')
@endsection