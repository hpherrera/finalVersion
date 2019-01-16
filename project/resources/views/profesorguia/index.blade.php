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
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Todos los Proyectos</h3>
				</div>
				<div class="box-body">
					<table id="table" class="table table-striped">
						<thead>
							<tr>
								<th>Título</th>
								<th>Estudiante</th>
								<th>Estado Estudiante</th>
								<th class="no-sort"></th>
							</tr>
						</thead>
						<tbody>
							@foreach($proyectos as $proyecto)
							<tr>
								<td><a href="/proyecto/{{ $proyecto->id }}/info" data-toggle="tooltip" title="ver información">{{ ucfirst($proyecto->titulo) }}</a></td>
								<td>
								@if($proyecto->estudiante_id == 0)
									Sin Estudiante
								@else
									{{$proyecto->persona->nombre()}}
								@endif

								<td>{{ $proyecto->estado->nombre() }}</td>
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
					                        <li><a onclick="addInvitado('{{ $proyecto->id }}')"><i class="fa fa-plus"></i> Agregar Invitado </a></li>
					                        <li><a onclick="updateInvitado('{{ $proyecto->id }}')"><i class="fa fa-pencil"></i> Editar Invitado </a></li>
					                        <li><a onclick="deleteInvitado('{{ $proyecto->id }}')"><i class="fa fa-remove"></i> Eliminar Invitado </a></li>
					                     </ul>
                   					</div> 
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection

@section('modal')
<!-- Modal Elimnar-->
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
				<p> Desea eliminar el proyecto?</p>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-danger pull-left" >Si, eliminar</button>
				<button type="button" class="btn btn-default pull-rigth" data-dismiss="modal">No, Cancelar</button>
			</div>
			</form>
		</div>

	</div>
</div>
<!-- Modal Agregar Invitado-->
<div class="modal fade" id="AddinvitadoModal" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Agregar Invitado</h4>
			</div>
			<form id="form-add" method="POST" role="form" >
			{{ csrf_field() }}
			<div class="modal-body">
				<div class="form-group has-feedback {{ $errors->has('invitado_id') ? 'has-error': '' }}">
                    <label>Invitados</label>
                    <select class="form-control" name="invitado_id" required>
                    	<option value="" disabled selected hidden style="color: gray">Seleccione Invitado...
                        @foreach($invitados as $invitado)
                        <option value="{{ $invitado->id}}"> {{ $invitado->persona->nombres}} {{ $invitado->persona->apellidos }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('invitado_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('invitado_id') }}</strong>
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
<!-- Modal Editar Invitado-->
<div class="modal fade" id="UpdateinvitadoModal" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Editar Invitado</h4>
			</div>
			<form id="form-update" method="POST" role="form" action="/editinvitado">
			{{ csrf_field() }}
			<div class="modal-body">
				<div class="form-group has-feedback {{ $errors->has('invitado_id') ? 'has-error': '' }}">
                    <label>Invitados</label>
                    <select class="form-control" name="invitado_id" id="invitados">
                    	<option value="" disabled selected hidden style="color: gray">Seleccione Invitado...
                        @foreach($invitados as $invitado)
                        <option value="{{ $invitado->id}}"> {{ $invitado->persona->nombres}} {{ $invitado->persona->apellidos }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('invitado_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('invitado_id') }}</strong>
                    </span>
                    @endif
                </div>
			</div>
			<input type="hidden" name="proyecto_id" id="proyecto_id">
			<div class="modal-footer">
                <button type="submit" class="btn btn-success pull-left" >Si, Editar</button>
                <button type="button" class="btn btn-default pull-rigth" data-dismiss="modal">No, Cancelar</button>
			</div>
			</form>
		</div>

	</div>
</div>
<!-- Modal Eliminar Invitado-->
<div class="modal fade" id="RemoveinvitadoModal" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Eliminar Invitado</h4>
			</div>
			<form id="form-remove" method="POST" role="form" >
			{{ csrf_field() }}
			<div class="modal-body">
				<p> Desea eliminar el invitado de su proyecto?</p>
			</div>
			<div class="modal-footer">
                <button type="submit" class="btn btn-success pull-left" >Si, Eliminar</button>
                <button type="button" class="btn btn-default pull-rigth" data-dismiss="modal">No, Cancelar</button>
			</div>
			</form>
		</div>

	</div>
</div>
@endsection


@section('style')
<style type="text/css">
  hr { 
    border: 1px solid #BDBDBD; 
    border-radius: 200px /8px; 
    height: 0px; 
    text-align: center; 
  } 

  .vl {
    border-left: 0.1px solid #BDBDBD;
    height: 700px;
  }
</style>
<style>
  a,
  a label {
    cursor: pointer;
  }
</style>
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

	function addInvitado(id){
		$('#form-add').attr('action', '/addinvitado/'+id);
		$('#AddinvitadoModal').modal('toggle');
	};

	function updateInvitado(id){
		$.ajax({
            type: 'POST',
            url:'/updateinvitado',
            data: {
            '_token':"{{ csrf_token() }}",
            'id':id
            },
            success: function(data) {
                console.log(data);
                //Editar valores
               	$('select[name=invitado_id]').val(data.persona_id).trigger('change');
               	$('#proyecto_id').val(data.proyecto_id);
                //Abrir modal Editar no mas
                $('#UpdateinvitadoModal').modal('show');
            },
            error: function (result) {
                
            }
        });
	};

	function deleteInvitado(id){
		$('#form-remove').attr('action', '/removeinvitado/'+id);
		$('#RemoveinvitadoModal').modal('toggle');
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

<script>
  $('#calendario').fullCalendar({
        height: 300
  });
</script>
@endsection
