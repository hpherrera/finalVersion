@extends('layouts.app')

@section('content')

<section class="content-header">
	<h1>
		Tarea
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
			<h3 class="box-title">Editar Tarea </h3>
		</div>
		<form method="POST" role="form" action="/tarea/update/{{ $tarea->id }}">
			{{ csrf_field() }}
			<div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group has-feedback {{ $errors->has('hito_id') ? 'has-error': '' }}">
							<label>Hito</label>
							<select class="form-control" name="hito_id" id="hitos">
								@foreach($hitos as $hito)
									@if($hito->id == $tarea->hito_id)
										<option value="{{ $hito->id }}" selected="">{{ $hito->nombre }}</option>
									@else
										<option value="{{ $hito->id }}">{{ $hito->nombre}}</option>
									@endif
								@endforeach
							</select>
							@if ($errors->has('hito_id'))
							<span class="help-block">
								<strong>{{ $errors->first('hito_id') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group has-feedback {{ $errors->has('nombre') ? 'has-error': '' }}">
							<label>Nombre</label>
							<input type="text" class="form-control" value="{{$tarea->nombre}}" name="nombre" autocomplete="off">
							@if ($errors->has('nombre'))
							<span class="help-block">
								<strong>{{ $errors->first('nombre') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group has-feedback {{ $errors->has('fecha_limite') ? 'has-error': '' }}">
							<label id="label-fecha">Fecha Termino : debe estar entre {{$fecha_inicio}} y {{$fecha_termino}}</label>
							<div class="input-group date">
							  <div class="input-group-addon">
							    <i class="fa fa-calendar"></i>
							  </div>
							  <input type="text" class="form-control pull-right" name="fecha_limite" value="{{$tarea->fecha_limite}}">
							</div>
							@if ($errors->has('fecha_limite'))
							<span class="help-block">
								<strong>{{ $errors->first('fecha_limite') }}</strong>
							</span>
							@endif
							<div class="input-group-btn"></div>
						</div>
						<div class="form-group has-feedback {{ $errors->has('comentario') ? 'has-error': '' }}">
							<label>Comentario</label>
							<input type="text" class="form-control" value="{{$tarea->comentario}}" name="comentario" autocomplete="off">
							</textarea>
							@if ($errors->has('comentario'))
							<span class="help-block">
								<strong>{{ $errors->first('comentario') }}</strong>
							</span>
							@endif
						</div>
					</div>
				</div>
				<input type="hidden" name="fecha2" value="{{$fecha2}}" id="fecha2">
				<input type="hidden" name="fechaT2" value="{{$fechaT2}}" id="fechaT2">
			</div>
			<div class="box-footer">
				<button type="submit" class="btn btn-warning btn-flat pull-right"><i class="fa fa-pencil"></i> Editar </button>
			</div>
		</form>
	</div>
</section>

@endsection

@section('script')
<script>
	$('input[name=fecha_limite]').datepicker({
		format: 'dd-mm-yyyy',
		language: 'es',
		orientation: 'bottom',
		startDate: $('#fecha2').val(),
		endDate: $('#fechaT2').val(),
		autoclose:true
	});

	$("select[id^='hito']").change(function () {
		var id = $(this).val();
		//funciona ajax que setee los valor del datapicker para el rango de las tareas...
		console.log(id);
		$.ajax({
            type: 'POST',
            url: "/fechas",
            data: {
                '_token':"{{ csrf_token() }}",
                'hito_id':id 
            },
            success: function(data) {
            	console.log(data);
            	$('#label-fecha').text("Fecha Termino : debe estar entre el "+data.fechaI+" y "+data.fechaT);
            	//cambiar las fechas
            	$('input[name=fecha_limite]').datepicker('setStartDate', data.fecha_inicio);
				$('input[name=fecha_limite]').datepicker('setEndDate', data.fecha_termino);

            	if(data.fechaT < $('#fechaT2').val())
            		console.log("sii");
            		$('input[name=fecha_limite]').val('').datepicker('update');
            },
		    error: function (result) {
		    }
        });
	});
</script>
@endsection