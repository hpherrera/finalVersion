@extends('layouts.app')

@section('content')
<section class="content-header">
	<h1>
		Proyecto
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
			<h3 class="box-title">Todos los Proyectos</h3>
		</div>
		<div class="box-body">
			<table id="table" class="table table-striped">
				<thead>
					<tr>
						<th>Título</th>
						<th>Alumno</th>
						<th>Tipo</th>
						<th>Estado</th>
						<th>Progreso %</th>
						<th>Area</th>
						<th class="no-sort"></th>
					</tr>
				</thead>
				<tbody>
					@foreach($proyectos as $proyecto)
					<tr>
						<td>{{ ucfirst($proyecto->titulo) }}</td>
						<td>{{ $proyecto->persona->nombre() }}
						</td>
						<td>
							@if($proyecto->estudiante_id == 0)
								Sin Estudiante
							@else
								{{$proyecto->persona->nombre()}}
							@endif
						</td>
						<td>{{ $proyecto->estado->nombre() }}</td>
						<td>{{ ucfirst($proyecto->progreso) }}</td>
						<td>{{ $proyecto->area->nombre()}}</td>
						<td>
							<div class="btn-group">
	                          <button type="button" class="btn btn-info btn-xs">Acciones</button>
	                          <button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown">
	                            <span class="caret"></span>
	                            <span class="sr-only">Toggle Dropdown</span>
	                          </button>
	                          <ul class="dropdown-menu dropdown-menu-right" role="menu">
	                            <li><a href="/proyecto/{{ $proyecto->id }}/edit"><i class="fa fa-pencil"></i> Editar </a></li>
	                            <li> <a onclick="Eliminar('{{ $proyecto->id }}')"><i class="fa fa-remove"></i>Eliminar</a></li>
	                            <li><a href="/proyecto/{{ $proyecto->id }}/info"><i class="fa fa-eye"></i> ver </a></li>
	                          </ul>
                       		</div> 
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		<div class="box-footer">
			<a href="/" class="btn btn-default btn-flat">Volver</a>
		</div>
	</div>
</section>
@section('modal')
<!-- Modal -->
<div class="modal fade" id="DeleteModal" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Eliminar Proyecto</h4>
			</div>
			<form id="form-delete" method="POST" role="form">
			{{ csrf_field() }}
			<div class="modal-body">
				<p> Desea eliminar el proyecto x?</p>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-danger pull-left" >Si, eliminar</button>
				<button type="button" class="btn btn-default pull-rigth" data-dismiss="modal">No, Cancelar</button>
			</div>
			</form>
		</div>

	</div>
</div>
@endsection
@endsection


@section('style')
<link rel="stylesheet" href="{{ asset('plugins/datatables/datatables.min.css') }}"/>
@endsection('style')

@section('script')

<script>
  $(function () {
      $('[data-toggle="tooltip"]').tooltip()
  })
</script>

<script>
	function Eliminar(id){
		$('#form-delete').attr('action', '/proyecto/delete/'+id);
		$('#DeleteModal').modal('toggle');
	};
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