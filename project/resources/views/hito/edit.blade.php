@extends('layouts.app')

@section('content')
<section class="content-header">
	<h1>
		Hito
		<small>Modificar</small>
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
			<h3 class="box-title">Registrar Hito</h3>
		</div>
		<form method="POST" role="form" action="/hito/update/{{ $hito->id }}">
			{{ csrf_field() }}
			<div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group has-feedback {{ $errors->has('titulo') ? 'has-error': '' }}">
							<label>Nombre</label>
							<input type="text" class="form-control" value="{{$hito->nombre}}" name="nombre" autocomplete="off">
							@if ($errors->has('nombre'))
							<span class="help-block">
								<strong>{{ $errors->first('nombre') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group has-feedback {{ $errors->has('fecha_rango') ? 'has-error': '' }}">
							<label>Fecha de inicio y termino</label>
							<div class="input-group date">
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</div>
								<input type="text" class="form-control pull-rigth" name="fecha_rango">
							</div>
							@if ($errors->has('fecha_rango'))
							<span class="help-block">
								<strong>{{ $errors->first('fecha_rango') }}</strong>
							</span>
							@endif
						</div>
						<input type="hidden" name="fecha_inicio" value="{{$fecha1}}">
						<input type="hidden" name="fecha_termino" value="{{$fecha1}}">
					</div>
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
<script>
$('input[name="fecha_rango"]').daterangepicker(
{
	"locale": {
        "format": "DD/MM/YYYY",
        "separator": " - ",
        "applyLabel": "Aplicar",
        "cancelLabel": "Cancelar",
        "fromLabel": "From",
        "toLabel": "To",
        "customRangeLabel": "Custom",
        "daysOfWeek": [
            "Do",
            "Lu",
            "Ma",
            "Mi",
            "Ju",
            "Vi",
            "Sa"
        ],
        "monthNames": [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre"
        ],
        "firstDay": 1
    }
}, 
function(start, end, label) {
	$('input[name="fecha_inicio"]').val(start.format('YYYY-MM-DD'));
	$('input[name="fecha_termino"]').val(end.format('YYYY-MM-DD'));
});

$('input[name="fecha_rango"]').data('daterangepicker').setStartDate("{{$hito->fecha_inicio}}");
$('input[name="fecha_rango"]').data('daterangepicker').setEndDate("{{$hito->fecha_termino}}");

</script>
@endsection