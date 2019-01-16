@extends('layouts.app')

@section('content')
<section class="content-header">
	<h1>
		Estudiantes
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
			<h3 class="box-title">Todos los Estudiantes </h3>
		</div>
		<div class="box-body">
			<table id="table" class="table table-striped">
				<thead>
					<tr>
						<th>Nombre</th>
						<th>Email</th>
						<th>Proyecto</th>
						<th>Curso</th>
						<th class="no-sort">Acciones</th>
					</tr>
				</thead>
				<tbody>
					@foreach($proyectos as $proyecto)
					@if($proyecto->estudiante_id != 0)
					<tr>
						<td>{{ $proyecto->persona->nombre()}}</td>
						<td>{{ $proyecto->persona->email}}</td>
						<td>{{ $proyecto->titulo}}</td>
						<td>{{ $proyecto->curso->nombre}}</td>
						<td>
						<div class="btn-group">
		                        <button type="button" class="btn btn-info btn-xs">Acciones</button>
		                        <button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown">
		                        <span class="caret"></span>
		                        <span class="sr-only">Toggle Dropdown</span></button>
							<ul class="dropdown-menu dropdown-menu-right" role="menu">
							<li><a onclick="EditEstudiante('{{ $proyecto->persona->id }}')"><i class="fa fa-pencil"></i> Editar Estado </a></li>
                        	<li><a href="/persona/{{ $proyecto->persona->id }}/edit"><i class="fa fa-pencil"></i> Editar </a></li>
                        	<li> <a onclick="Eliminar('{{ $proyecto->persona->id }}')"><i class="fa fa-remove"></i>Eliminar</a></li>
                        	</ul>
                        	</div>
						</td>
					</tr>
					@endif
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
				<h4 class="modal-title">Eliminar Usuario</h4>
			</div>
			<form id="form-delete" method="POST" role="form">
			{{ csrf_field() }}
			<div class="modal-body">
				<p> Desea eliminar el Usuario y Proyecto?</p>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-danger pull-left" >Si, eliminar</button>
				<button type="button" class="btn btn-default pull-rigth" data-dismiss="modal">No, Cancelar</button>
			</div>
			</form>
		</div>

	</div>
</div>

<!-- Modal Edit Estudiante-->
<div class="modal fade" id="EditEstudianteModal" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Editar Estado Estudiante</h4>
			</div>
			<form id="form-edit" method="POST" role="form">
				{{ csrf_field() }}
				<div class="modal-body">
					<!-- Curso -->
					<div class="form-group has-feedback {{ $errors->has('Curso') ? 'has-error': '' }}">
					<label> Curso </label>
						<select class="form-control" name="curso_id">
							<option value="1">Formulación de Proyecto de Titulación</option>
							<option value="2">Proyecto de Tìtulación</option>
							<option value="2">Egresado</option>
						</select>
					</div>

					<!-- Estado -->
					<div class="form-group has-feedback {{ $errors->has('estado_id') ? 'has-error': '' }}">
						<label>Estado</label>
						<select class="form-control" name="estado_id">
							<option value="1">Formulación</option>
							<option value="2">Proyecto de Tìtulación</option>
							<option value="3">Egresado</option>
							<option value="4">Congelado</option>
						</select>
						@if ($errors->has('estado_id'))
						<span class="help-block">
							<strong>{{ $errors->first('estado_id') }}</strong>
						</span>
						@endif
					</div>

					<!-- Semestre -->
					<div class="form-group has-feedback {{ $errors->has('semestre') ? 'has-error': '' }}">
						<label> Semestre </label>
						<select class="form-control" name="semestre">
							<option value="1">Primero</option>
							<option value="2">Segundo</option>
						</select>
					</div>

					<!-- año -->
					<div class="form-group has-feedback {{ $errors->has('estado_id') ? 'has-error': '' }}">
						<label> Año </label>
						<input id="date_ini" name="year" class="form-control" placeholder="Año" autocomplete="off">
						@if ($errors->has('year'))
						<span class="help-block">
							<strong>{{ $errors->first('year') }}</strong>
						</span>
						@endif
					</div>
				</div>
				<input type="hidden" name="estudiante_id">
				<div class="modal-footer">
					<button type="submit" class="btn btn-success pull-left" >Si, editar</button>
					<button type="button" class="btn btn-default pull-rigth" data-dismiss="modal">No, Cancelar</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection


@section('style')
<link rel="stylesheet" href="{{ asset('plugins/datatables/datatables.min.css') }}"/>
<style>
  a,
  a label {
    cursor: pointer;
  }
</style>
@endsection('style')

@section('script')
<script>
	function EditEstudiante(id){
		console.log(id);

		//funcion Ajax

		$.ajax({
	        type: 'POST',
	        url:'/datosEstudianteProyecto',
	        data: {
	        '_token':"{{ csrf_token() }}",
	        'estudiante_id':id
	    	},
	        success: function(data) {
	        	console.log(data);
	        	$('select[name=curso_id]').val(data.curso_id).trigger('change');
	        	$('select[name=estado_id]').val(data.estado_id).trigger('change');
	        	$('select[name=semestre]').val(data.semestre).trigger('change');
	        	$('input[name=year]').val(data.year).trigger('change');
	        	$('input[name=estudiante_id]').val(id).trigger('change');
	        },
		    error: function (result) {
		        
		    }
	    });

		$('#form-edit').attr('action', '/estudianteedit');
		$('#EditEstudianteModal').modal('toggle');
	}
</script>

<script>
	function Eliminar(id){
		$('#form-delete').attr('action', '/persona/delete/'+id);
		$('#DeleteModal').modal('toggle');
	};
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