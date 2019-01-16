@extends('layouts.app')
 
@section('content')
<section class="content-header">
    <h1>
        Planificación Reuniones
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li class="active">Home</li>
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
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ReunionModal" class="btn btn-info btn-xs">Agregar Reunión</button>
        </div>
    </div>
    <div class="box-body with-border">
        <span class="pull-left margin-right-5">
            <small class="label bg-purple pull-right"> <i class="fa fa-info padding-top-3"></i></small> 
        </span>
        <div class="note">
            <strong>Nota:</strong> Para ver más información y realizar acciones en la reunión hacer click en el día que ha sido planificada.
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body no-padding">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('modal')
<!-- Modal -->
<div class="modal fade" id="ReunionModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Agregar Reunión</h4>
            </div>
            <form id="form-add" method="POST" role="form" action="/createreunion">
            {{ csrf_field() }}
            <div class="modal-body">
                <div class="form-group has-feedback {{ $errors->has('estudiante_id') ? 'has-error': '' }}">
                    <label>Estudiante</label>
                    <select class="form-control" name="estudiante_id">
                        @foreach($personas as $persona)
                        <option value="{{ $persona['id'] }}"> {{ $persona['nombres'] }} {{ $persona['apellidos'] }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('estudiante_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('estudiante_id') }}</strong>
                    </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('fecha') ? 'has-error': '' }}">
                    <label>Fecha</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right" name="fecha" id="fecha" autocomplete="off">
                    </div>
                    @if ($errors->has('fecha'))
                    <span class="help-block">
                        <strong>{{ $errors->first('fecha') }}</strong>
                    </span>
                    @endif
                    <div class="input-group-btn"></div>
                </div>
                <div class="form-group has-feedback {{ $errors->has('hora') ? 'has-error': '' }}">
                    <label>Hora</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-clock-o"></i>
                      </div>
                      <input  type="time" class="form-control pull-right" name="hora" id="hora" autocomplete="off">
                    </div>
                    @if ($errors->has('hora'))
                    <span class="help-block">
                        <strong>{{ $errors->first('hora') }}</strong>
                    </span>
                    @endif
                    <div class="input-group-btn"></div>
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

<!-- Modal Editar Reunión -->
<div class="modal fade" id="ReunionModalEdit" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Editar Reunión</h4>
            </div>
            <form id="form-add" method="POST" role="form" action="/reunion/editar">
            {{ csrf_field() }}
            <div class="modal-body">
                <div class="form-group has-feedback {{ $errors->has('estudiante_id') ? 'has-error': '' }}">
                    <label>Estudiante</label>
                    <select class="form-control" name="estudiante_idE">
                        @foreach($personas as $persona)
                        <option value="{{ $persona['id'] }}"> {{ $persona['nombres'] }} {{ $persona['apellidos'] }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('estudiante_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('estudiante_id') }}</strong>
                    </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('fecha') ? 'has-error': '' }}">
                    <label>Fecha</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right" name="fechaE" id="fechaE" autocomplete="off">
                    </div>
                    @if ($errors->has('fecha'))
                    <span class="help-block">
                        <strong>{{ $errors->first('fecha') }}</strong>
                    </span>
                    @endif
                    <div class="input-group-btn"></div>
                </div>
                <div class="form-group has-feedback {{ $errors->has('hora') ? 'has-error': '' }}">
                    <label>Hora</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-clock-o"></i>
                      </div>
                      <input  type="time" class="form-control pull-right" name="horaE" id="horaE" autocomplete="off">
                    </div>
                    @if ($errors->has('hora'))
                    <span class="help-block">
                        <strong>{{ $errors->first('hora') }}</strong>
                    </span>
                    @endif
                    <div class="input-group-btn"></div>
                </div>
            </div>
            <input type="hidden" name="reunion_id">
            <div class="modal-footer">
                <button type="submit" class="btn btn-success pull-left" >Si, Editar</button>
                <button type="button" class="btn btn-default pull-rigth" data-dismiss="modal">No, Cancelar</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Eliminar Reunión-->
<div class="modal fade" id="ElimnarReunion" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Eliminar Reunión</h4>
            </div>
            <form id="form-delete" method="POST" role="form">
            {{ csrf_field() }}
            <div class="modal-body">
                <p> Desea eliminar la reunión?</p>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success pull-left" >Si, eliminar</button>
                <button type="button" class="btn btn-default pull-rigth" data-dismiss="modal">No, Cancelar</button>
            </div>
            </form>
        </div>

    </div>
</div>
@endsection


@section('script')
<script>
 
    $(document).ready(function() { 
        $('#calendar').fullCalendar({
            eventStartEditable: false,
            eventRender: function(eventObj, $el) {
                $el.popover({
                    title: eventObj.title,
                    content: eventObj.description,
                    trigger: 'hover',
                    placement: 'top',
                    container: 'body'
                });
            },
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,basicDay'
            },
            allDayText: false,
            allDaySlot: false,
            defaultTimedEventDuration: '01:00:00',
            slotLabelFormat: 'h(:mm)a',
            navLinks: true, // can click day/week names to navigate views
            editable: true,
            eventLimit: true, // allow "more" link when too many events
            events: {
                url: '/tarea/reuniones',
                type: 'POST',
                data:{
                    "_token": "{{ csrf_token() }}"
                },
                error: function() {
                    alert('Error al cargar las fechas');
                }
            },
            eventRender: function(event, element) {
                var currentDate = new Date();
                var day = currentDate.getDate();
                var month = currentDate.getMonth()+1;
                var year = currentDate.getFullYear();
                var anio = moment(event.start).format("YYYY");
                var mes = moment(event.start).format("M");
                var dia = moment(event.start).format("DD");
                //console.log(year,month,day);
                console.log(anio,mes,dia);

                if(anio>=year && mes>=month && dia>=day)
                {
                    element.html(event.title + '<span class="glyphicon glyphicon-edit pull-right" onclick=Editar('+event.id+') data-toggle="tooltip" title="Editar"></span><span class="glyphicon glyphicon-remove pull-right" onclick=Eliminar('+event.id+') data-toggle="tooltip" title="Eliminar"></span>');
                    element.css("padding", "4px");
                }
                else
                {
                    element.html(event.title);
                    element.css("padding", "4px");
                }
                
            }   
        });
    });
</script>
<script>
    function Editar(idreunion)
    {
        $.ajax({
            type: 'POST',
            url:'/reunion/edit',
            data: {
            '_token':"{{ csrf_token() }}",
            'id':idreunion
            },
            success: function(data) {
                console.log(data);
                //Editar valores
                $('input[name=reunion_id]').val(idreunion);
                $('input[name=fechaE]').datepicker('setDate', data.fecha);
                $('input[name=horaE]').val(data.hora);
                $('select[name=estudiante_idE]').val(data.estudiante_id).trigger('change');
                //Abrir modal Editar no mas
                $('#ReunionModalEdit').modal('show');
            },
            error: function (result) {
                
            }
        });
    }

    function Eliminar(idreunion)
    {
        $('#form-delete').attr('action', '/reunion/delete/'+idreunion);
        $('#ElimnarReunion').modal('toggle');
    }
</script>
<script>
    $('input[name=fecha]').datepicker({
        format: 'dd-mm-yyyy',
        language: 'es',
        orientation: 'bottom',
        autoclose:true,
        startDate: 0
    });

    $('input[name=fechaE]').datepicker({
        format: 'dd-mm-yyyy',
        language: 'es',
        orientation: 'bottom',
        autoclose:true
    });
</script>
@endsection
 
@section('style')
<style>
 
    body {
        margin: 0 0;
        padding: 0;
        font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
        font-size: 14px;
    }
 
    #calendar {
        max-width: 900px;
        margin: 0 auto;
    }

    .fc-time{
        display : none;
    }
 
</style>
@endsection('style')