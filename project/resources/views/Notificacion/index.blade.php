@extends('layouts.app')

@section('content')
<section class="content-header">
	<h1>
		Notificaciones
		<small>Todas</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-home"></i> Home</a></li>
		<li class="active">Todas</li>
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
			<h3 class="box-title">Todas las Notificaciones</h3>
		</div>
		<div class="box-body">
			<table id="table" class="table table-striped">
				<thead>
					<tr>
						<th>Información</th>
						<th>Tipo</th>
						<th class="no-sort">Estado</th>
					</tr>
				</thead>
				<tbody>
					@foreach($notifications as $notification)
					<tr>
						<td>{{ ucfirst($notification->texto) }}</td>
						<td>
						@if($notification->tipo_notificacion_id == 1)
							Entrega Entregable
						@elseif($notification->tipo_notificacion_id == 2)
							Retraso Entregable
						@elseif($notification->tipo_notificacion_id == 5)
							Reunión
						@else
							Alerta Retraso Entregable
						@endif
						</td>
						@if($notification->leido == 0)
							<td id="noview-{{$notification->id}}"> No Leída <a id = "button_view-{{$notification->id}}"  class="btn btn-info btn-xs" onclick="viewed('{{ $notification->id }}')">Marcar Leído</a></td>
						@else
							<td> Leída </td>
						@endif
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

<script>
	function viewed(id){
		console.log(id);
		$.ajax({
            type: 'POST',
            url: "/viewNotificaction",
            data: {
                '_token':"{{ csrf_token() }}",
                'notification_id':id
            },
            success: function(data) {
            	if(data.true == true)
            	{
            		$('#button_view-'+id).hide();
            		$('#noview-'+id).text("Leída");
            	}
            },
		    error: function (result) {
		        $('#modalinfo .modal-body #parrafo').text("Existieron algunos errores.");
        		$('#modalinfo').modal('show');
		    }
        });
	};
</script>
@endsection