@extends('layouts.app')

@section('content')

<section class="content-header">
	<h1>
		Promedio general de semestres que demora un alumno en completar su proyecto de título
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-home"></i> Home</a></li>
	</ol>
</section>

<section class="content">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">Estudiantes</h3>
		</div>
		<div class="box-body with-border">
			<span class="pull-left margin-right-5">
				<small class="label bg-purple pull-right"> <i class="fa fa-info padding-top-3"></i></small> 
			</span>
			<div class="note">
				<strong>Promedio General : {{ $promedio }} semestres.</strong>
			</div>
		</div>

		<div class="box-body">
			<table id="table" class="table table-striped">
				<thead>
					<tr>
						<th>Nombre</th>
						<th>Año/Semestre inicio</th>
						<th>Año/Semestre egreso</th>
						<th>Semestres de duración</th>
					</tr>
				</thead>
				<tbody>
					
					@foreach($estudiantes_egresados as $estudiante)
					<tr>
						<td> {{ $estudiante['nombre'] }}</td>
						<td> {{ $estudiante['asi'] }}</td>
						<td> {{ $estudiante['ast'] }}</td>
	              		<td> {{ $estudiante['contador'] }}</td>
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