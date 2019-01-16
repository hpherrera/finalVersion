@extends('layouts.app')

@section('content')

    <section class="content-header">
      <h1>{{$hito->nombre}}</h1>
      <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li class="active">Hito/{{$hito->id}}</li>
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
              <h3 class="box-title">Tareas del Hito</h3>
            </div>
            <div class="box-body">
              <table id="table" class="table table-striped">
                <thead>
                  <tr>
                    <th>Nombre</th>
                    <th>Fecha Termino</th>
                    <th>Comentario</th>
                    @if(Auth::user()->rol_id == 5)
                    <th class="no-sort"></th>
                    @endif
                  </tr>
                </thead>
                <tbody>
                  @foreach($tareas as $tarea)
                    <tr>
                      <td>
                      @if(Auth::user()->rol_id == 3 || Auth::user()->rol_id == 6)
                        <a href="/tarea/{{ $tarea->id }}/info" data-toggle="tooltip" title="ver información">{{ ucfirst($tarea->nombre) }}</a>
                      @elseif(Auth::user()->rol_id == 5)
                        {{ ucfirst($tarea->nombre) }}
                      @endif
                      </td>
                      <td>{{ Carbon\Carbon::parse(ucfirst($tarea->fecha_limite))->format('d-m-Y')  }}</td>
                      <td>{{ ucfirst($tarea->comentario)  }}</td>
                      @if(Auth::user()->rol_id == 5)
                      <td>
                      <div class="btn-group">
                            <button type="button" class="btn btn-info btn-xs">Acciones</button>
                            <button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown">
                              <span class="caret"></span>
                              <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                              <li><a href="/tarea/{{ $tarea->id }}/edit"><i class="fa fa-pencil"></i>Editar</a></li>
                              <li><a onclick="Eliminar('{{ $tarea->id }}')"><i class="fa fa-remove"></i>Eliminar</a></li>
                              <li><a href="/tarea/{{ $tarea->id }}/info"><i class="fa fa-eye"></i>Ver</a></li>
                              @if($tarea->fecha_limite > Carbon\Carbon::parse(now())->format('Y-m-d'))
                              <li><a href="/entregable/{{$tarea->id}}/create"><i class="fa fa-save"></i>Agregar Entregable</a></li>
                              @endif
                            </ul>
                        </div> 
                      </td>
                      @endif
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
        <h4 class="modal-title">Eliminar Tarea</h4>
      </div>
      <form id="form-delete" method="POST" role="form">
      {{ csrf_field() }}
      <div class="modal-body">
        <p> Desea eliminar la tarea?</p>
        <p> Recuerde que al eliminar la tarea se eliminaran los entregables asociadas a esta.</p>
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

@section('style')
<link rel="stylesheet" href="{{ asset('plugins/datatables/datatables.min.css') }}"/>
<style>
  a,
  a label {
    cursor: pointer;
  }
</style>
@endsection

@section('script')
<script src="{{ asset('plugins/datatables/datatables.min.js') }}"></script> 
<script>
  function Eliminar(id){
    $('#form-delete').attr('action', '/tarea/delete/'+id);
    $('#DeleteModal').modal('toggle');
  };
</script>
<script>
  $(function () {
      $('[data-toggle="tooltip"]').tooltip()
  })
</script>
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