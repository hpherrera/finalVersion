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
	
	<div class="box box-success">
		<div class="box-header with-border">
			<h3 class="box-title">Crear Tarea </h3>
		</div>
		<form method="POST" role="form" action="/tarea">
			{{ csrf_field() }}
			<div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group has-feedback {{ $errors->has('hito_id') ? 'has-error': '' }}">
							<label>Hito</label>
							<select class="form-control" name="hito_id" id="hitos">
								<option value="" disabled selected hidden style="color: gray">Seleccione hito...</option>
								@foreach($hitos as $hito)
								<option value="{{ $hito->id }}">{{ $hito->nombre}}</option>
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
							<input type="text" class="form-control" placeholder="EJ: diagrama diseño..." name="nombre" autocomplete="off">
							@if ($errors->has('nombre'))
							<span class="help-block">
								<strong>{{ $errors->first('nombre') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group has-feedback {{ $errors->has('fecha_limite') ? 'has-error': '' }}">
							<label id="label-fecha">Fecha Termino (debe seleccionar un hito)</label>
							<div class="input-group date">
							  <div class="input-group-addon">
							    <i class="fa fa-calendar"></i>
							  </div>
							  <input type="text" class="form-control pull-right" name="fecha_limite" id="fecha_limite" disabled autocomplete="off">
							</div>
							@if ($errors->has('fecha_limite'))
							<span class="help-block">
								<strong>{{ $errors->first('fecha_limite') }}</strong>
							</span>
							@endif
							<div class="input-group-btn"></div>
						</div>
						<br>
						<div class="form-group has-feedback {{ $errors->has('comentario') ? 'has-error': '' }}">
							<label>Comentario</label>
							<input type="text" class="form-control" placeholder="EJ: entrevista 1 con pepito" name="comentario" autocomplete="off">
							</textarea>
							@if ($errors->has('comentario'))
							<span class="help-block">
								<strong>{{ $errors->first('comentario') }}</strong>
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
	$("select[id^='hito']").change(function () {
		var id = $(this).val();
		//funciona ajax que setee los valor del datapicker para el rango de las tareas...
		$.ajax({
            type: 'POST',
            url: "/fechas",
            data: {
                '_token':"{{ csrf_token() }}",
                'hito_id':id 
            },
            success: function(data) {
            	$('#fecha_limite').attr("disabled",false);
            	$('#label-fecha').text("Fecha Termino : debe estar entre el "+data.fechaI+" y "+data.fechaT);
            	$('input[name=fecha_limite]').datepicker({
            		format: 'dd-mm-yyyy',
					language: 'es',
					orientation: 'bottom',
					startDate: data.fecha_inicio,
			    	endDate: data.fecha_termino,
			    	autoclose:true,
				});
            },
		    error: function (result) {
		    }
        });
	});
</script>

@endsection