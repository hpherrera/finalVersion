@extends('layouts.app')

@section('content')
<section class="content-header">
	<h1>
		Hitos
		<small>Todos</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-home"></i> Home</a></li>
		<li class="active">Todos</li>
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
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">Todos los Hitos</h3>
		</div>
		<div class="box-body">
			<table id="table" class="table table-striped">
				<thead>
					<tr>
						<th>Nombre</th>
						<th>Fecha Inicio</th>
						<th>Fecha Termino</th>
						<th>Cantidad de tareas</th>
						<th>Estado</th>
						<th class="no-sort"></th>
					</tr>
				</thead>
				<tbody>
					@foreach($hitos as $hito)
					<tr>
						<td>{{ ucfirst($hito->nombre) }}</td>
						<td>{{ Carbon\Carbon::parse(ucfirst(ucfirst($hito->fecha_inicio)))->format('d-m-Y') }}</td>
						<td>{{ Carbon\Carbon::parse(ucfirst(ucfirst($hito->fecha_termino)))->format('d-m-Y') }}</td>
						<td>{{ count($hito->tareas) }}</td>
						<td>
							@if($hito->progreso == 0)
								{{$hito->progreso}}%
							@else
								<div class="progress">
	  								<div class="progress-bar progress-bar-striped" role="progressbar" style="width: {{$hito->progreso}}%" aria-valuenow="{{$hito->progreso}}" aria-valuemin="0" aria-valuemax="100">{{$hito->progreso}}%</div>
								</div>
							@endif
						</td>
						<td>
							<div class="btn-group">
				                <button type="button" class="btn btn-info btn-xs">Acciones</button>
				                <button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown">
				                  <span class="caret"></span>
				                  <span class="sr-only">Toggle Dropdown</span>
				                </button>
				                <ul class="dropdown-menu" role="menu">
				                  <li><a href="/hito/{{ $hito->id }}/edit"><i class="fa fa-pencil"></i> Editar </a></li>
				                  <li> <a onclick="Eliminar('{{ $hito->id }}')"><i class="fa fa-remove"></i>Eliminar</a></li>
				                  <li><a href="/hito/{{ $hito->id }}/info"><i class="fa fa-eye"></i> ver </a></li>
				                  @if($hito->fecha_termino > Carbon\Carbon::parse(now())->format('Y-m-d'))
				                  	<li><a onclick="addTarea('{{ $hito->id }}')"> <i class="fa fa-save"></i>Agregar Tarea</a></li>
				                  @endif
				                </ul>
				              </div> 
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</section>
@endsection

@section('modal')
<!-- Modal -->
<div class="modal fade" id="DeleteModal" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Eliminar Hito</h4>
			</div>
			<form id="form-delete" method="POST" role="form">
			{{ csrf_field() }}
			<div class="modal-body">
				<p> Desea eliminar el hito?</p>
				<p> Recuerde que al eliminar el hito se eliminaran las tareas, entregables, comentarios y notificaciones asociadas a este.</p>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-danger pull-left" >Si, eliminar</button>
				<button type="button" class="btn btn-default pull-rigth" data-dismiss="modal">No, Cancelar</button>
			</div>
			</form>
		</div>

	</div>
</div>
<!-- Agregar Tarea-->
<div class="modal fade" id="addTareaModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Agregar Tarea</h4>
			</div>
			<form id="form-addTarea" method="POST" role="form">
				{{ csrf_field() }}
				<div class="modal-body">
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
						<label id="label-fecha"></label>
						<div class="input-group date">
						  <div class="input-group-addon">
						    <i class="fa fa-calendar"></i>
						  </div>
						  <input type="text" class="form-control pull-right" name="fecha_limite" id="fecha_limite" autocomplete="off">
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
				<div class="modal-footer">
					<button type="submit" class="btn btn-success pull-left" >Si, Agregar</button>
					<button type="button" class="btn btn-default pull-rigth" data-dismiss="modal">No, Cancelar</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection


@section('style')
<link rel="stylesheet" href="{{ asset('plugins/datatables/datatables.min.css') }}"/>
<style type="text/css">
  hr { 
    border: 1px solid #BDBDBD; 
    border-radius: 200px /8px; 
    height: 0px; 
    text-align: center; 
  } 

  .vl {
    border-left: 0.1px solid #BDBDBD;
    height: 600px;
  }

</style>
<style>
  a,
  a label {
    cursor: pointer;
  }
</style>
@endsection('style')

@section('script')

<script>
	function Eliminar(id){
		$('#form-delete').attr('action', '/hito/delete/'+id);
		$('#DeleteModal').modal('toggle');
	};

	function addTarea(id)
	{
		$('#form-addTarea').attr('action', '/tarea/create/'+id);
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
		$('#addTareaModal').modal('toggle');
	}
</script>

<script src="{{ asset('plugins/datatables/datatables.min.js') }}"></script>	
<script>
	var table;

	$(document).ready(function () {
		table = $("#table").DataTable({
			"responsive": true,
			"order": [0, 'asc'],
			"paging": true,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"columnDefs": [
			{ targets: 'no-sort', orderable: false }
			], 
			"language": {
				"sProcessing":     "Procesando...",
				"sLengthMenu":     "Mostrar _MENU_ registros",
				"sZeroRecords":    "No se encontraron resultados",
				"sEmptyTable":     "Ningún dato disponible en esta tabla",
				"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
				"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
				"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
				"sInfoPostFix":    "",
				"sSearch":         "Buscar:",
				"sUrl":            "",
				"sInfoThousands":  ",",
				"sLoadingRecords": "Cargando...",
				"oPaginate": {
					"sFirst":    "Primero",
					"sLast":     "Último",
					"sNext":     "Siguiente",
					"sPrevious": "Anterior"
				}, 
				"oAria": {
					"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
					"sSortDescending": ": Activar para ordenar la columna de manera descendente"
				}
			},
		});
	});
</script>
@endsection