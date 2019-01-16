@extends('layouts.app')

@section('content')
<section class="content-header">
	<h1>
		@if(count($historials))
			Historial
		@else
		Sin Historial
		@endif
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-home"></i> Home</a></li>
		<li class="active">Home</li>
	</ol>
</section>


<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Acciones</h3>
				</div>
				<div class="box-body">
					@if(count($historials))
					<table id="table" class="table table-striped">
						<thead>
							<tr>
								<th>Nombre</th>
								<th>Fecha y hora </th>
							</tr>
						</thead>
						<tbody>

							@foreach($historials as $historial)
							<tr>
								<td>{{ ucfirst($historial->texto) }}</td>
								<td>{{ ucfirst($historial->created_at) }}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					@else
					No existen acciones.
					@endif
				</div>
			</div>
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
