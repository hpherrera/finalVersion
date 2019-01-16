@extends('layouts.app')

@section('content')

<section class="content-header">
	<h1>
		Repositorio
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
			<h3 class="box-title">Todos los Documentos</h3>
			<!--<a class="btn btn-success btn-sm pull-right"><i class="fa fa-plus"></i> Agregar Documento</a>-->
		</div>
		<div class="box-body">
			<table id="table" class="table table-striped">
				<thead>
					<tr>
						<th>Nombre</th>
						<th>Fecha</th>
						<th>Tarea</th>
						<th>Estado</th>
						<th>Tipo</th>
						<th class="no-sort"></th>
					</tr>
				</thead>
				<tbody>
					@foreach($entregables as $entregable)
					<tr>
						<td>{{ ucfirst($entregable->nombre) }}</td>
						<td>{{ ucfirst($entregable->fecha)  }}</td>
						<td>{{ $entregable->tarea->nombre}}</td>
                  		<td>{{ $entregable->estado->nombre  }}</td>
                  		<td>
                  			@if($entregable->tipo == 1)
                  				Entregable
                  			@else
                  				Documento Final
                  			@endif
                  		</td>
						<td>
							<a href="/entregable/{{ $entregable->id }}/Descargar" class="btn btn-info btn-sm pull-right"><i class="fa fa-cloud-download"></i> Descargar</a>
						</td>

					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</section>
@endsection


@section('style')
<link rel="stylesheet" href="{{ asset('plugins/datatables/datatables.min.css') }}"/>
@endsection('style')

@section('script')

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