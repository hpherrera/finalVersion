@extends('layouts.app')

@section('content')
<section class="content-header">
	<h1>
    @if(\Auth::user()->ProfesorGuia())
		Estudiante
    @else
        Usuario
    @endif
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-home"></i> Home</a></li>
		<li class="active">Editar</li>
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
	
	<div class="box box-warning">
		<div class="box-header with-border">
			<h3 class="box-title">
                @if(\Auth::user()->ProfesorGuia())
                    Editar Estudiante
                @else
                    Editar Usuario
                @endif         
            </h3>
		</div>
		<div class="panel-body">
            <form class="form-horizontal" method="POST" action="/persona/update/{{ $persona->email }}">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
                    <label for="nombre" class="col-md-4 control-label">Nombre</label>

                    <div class="col-md-6">
                        <input id="nombre" type="text" class="form-control" name="nombre" value="{{ $persona->nombres }}" required autocomplete="off">

                        @if ($errors->has('nombre'))
                            <span class="help-block">
                                <strong>{{ $errors->first('nombre') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('apellidos') ? ' has-error' : '' }}">
                    <label for="apellidos" class="col-md-4 control-label">Apellidos</label>

                    <div class="col-md-6">
                        <input id="apellidos" type="text" class="form-control" name="apellidos" value="{{ $persona->apellidos}}" required autocomplete="off">

                        @if ($errors->has('apellidos'))
                            <span class="help-block">
                                <strong>{{ $errors->first('apellidos') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="col-md-4 control-label">E-Mail</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control" name="email" value="{{ $persona->email}}" required autocomplete="off">
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('rol_id') ? 'has-error': '' }}">
					<label for="rol" class="col-md-4 control-label">Rol</label>
					<div class="col-md-6">
						<select class="form-control" name="rol_id[]" id="select-rol" multiple required>
							@foreach($roles as $rol)
                                @if($persona->user->roles->contains('id', $rol->id))
								<option value="{{ $rol->id }}" selected >{{ $rol->nombre() }}</option>
                                @else
                                <option value="{{ $rol->id }}">{{ $rol->nombre() }}</option>
                                @endif
							@endforeach
						</select>
						@if ($errors->has('rol_id'))
							<span class="help-block">
								<strong>{{ $errors->first('rol_id') }}</strong>
							</span>
						@endif
					</div>
				</div>
                @foreach($roles_persona as $rol)
                    @if($rol->id == 5)
        				<div id="matricula" class="form-group{{ $errors->has('matricula') ? ' has-error' : '' }}">
                            <label for="nombre" class="col-md-4 control-label">Matricula</label>
                            <div class="col-md-6">
                                <input id="matricula" type="number" class="form-control" name="matricula" value="{{ $matricula }}">
                                @if ($errors->has('matricula'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('matricula') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>	
                    @endif
                @endforeach
                @foreach($roles_persona as $rol)
                    @if($rol->id == 4)
                    <div id="nombre_curso" class="form-group{{ $errors->has('curso_id') ? 'has-error': '' }}" >
                        <label for="curso" class="col-md-4 control-label">Curso</label>
                        <div class="col-md-6">
                            <select class="form-control" name="curso_id" id="select-curso" ">
                                @foreach($cursos as $curso)
                                    @if($curso_id->id == $curso->id)
                                         <option value="{{ $curso->id }}" selected="">{{ $curso->nombre }}</option>
                                    @else
                                        @if($curso->id != 3)
                                            <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                                         @endif
                                    @endif 
                                   
                                @endforeach
                            </select>
                            @if ($errors->has('curso_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('curso_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    @else
                    <div id="nombre_curso" class="form-group{{ $errors->has('curso_id') ? 'has-error': '' }}" hidden>
                        <label for="curso" class="col-md-4 control-label">Curso</label>
                        <div class="col-md-6">
                            <select class="form-control" name="curso_id" id="select-curso" ">
                                @foreach($cursos as $curso)
                                    <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('curso_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('curso_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    @endif
                @endforeach

                @foreach($roles_persona as $rol)
                    @if($rol->id == 6)
                        <div id="invitado" class="form-group{{ $errors->has('tipo_id') ? 'has-error': '' }}">
        					<label for="tipo" class="col-md-4 control-label">Tipo</label>
        					<div class="col-md-6">
        						<select class="form-control" name="tipo_id" id="select-tipo-invitado" ">
        							@foreach($tipos as $tipo)
                                        @if($tipoInvitado ==  $tipo->id && $tipoInvitado != -1) 
                                            <option value="{{ $tipo->id }}" selected>{{ $tipo->nombre() }}</option>
                                        @else
                                            <option value="{{ $tipo->id }}">{{ $tipo->nombre() }}</option>
                                        @endif
        							@endforeach
        						</select>
        						@if ($errors->has('tipo_id'))
        							<span class="help-block">
        								<strong>{{ $errors->first('tipo_id') }}</strong>
        							</span>
        						@endif
        					</div>
        				</div>
                    @endif  
                @endforeach

                @if($tipoInvitado !=  null && $tipoInvitado == 2 )
                    <div id="nombre_Empresa" class="form-group{{ $errors->has('empresa') ? ' has-error' : '' }}">
                        <label for="empresa" class="col-md-4 control-label">Empresa</label>

                        <div class="col-md-6">
                            <input id="empresa" type="text" class="form-control" name="empresa" value="{{ $nombreInvitado }}">
                            @if ($errors->has('empresa'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('empresa') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div id="carrera_nombre" class="form-group{{ $errors->has('carrera') ? ' has-error' : '' }}" hidden>
                        <label for="carrera" class="col-md-4 control-label">Carrera</label>
                        <div class="col-md-6">
                            <input id="carrera" type="text" class="form-control" name="carrera">
                             @if ($errors->has('carrera'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('carrera') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>  
                @endif 
                    @if($tipoInvitado !=  null && $tipoInvitado == 1 )
                    <div id="nombre_Empresa" class="form-group{{ $errors->has('empresa') ? ' has-error' : '' }}" hidden>
                        <label for="empresa" class="col-md-4 control-label">Empresa</label>

                        <div class="col-md-6">
                            <input id="empresa" type="text" class="form-control" name="empresa">
                            @if ($errors->has('empresa'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('empresa') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div id="carrera_nombre" class="form-group{{ $errors->has('carrera') ? ' has-error' : '' }}">
                        <label for="carrera" class="col-md-4 control-label">Carrera</label>
                        <div class="col-md-6">
                            <input id="carrera" type="text" class="form-control" name="carrera" value="{{$nombreInvitado}}">
                             @if ($errors->has('carrera'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('carrera') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>  
                    @endif 
                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-warning pull-right">
                            Editar
                        </button>
                    </div>
                </div>
            </form>
        </div>
	</div>
</section>
@endsection

@section('script')

<script>

	$('#select-rol').on('change', function() {
        var ids = $(this).val();
        if(ids.length == 0)
        {
            $("#matricula .form-control").removeAttr('required');
            $("#invitado .form-control").removeAttr('required');
            $("#carrera .form-control").removeAttr('required');
            $('#matricula').hide();
            $('#invitado').hide();
            $('#carrera').hide();
            $('#curso').hide();
        }

        $.each(ids, function (index, id) {
            console.log(id);
            if(id == 4)
            {
                $('#nombre_curso').show();
                $('#invitado').hide();
                $('#carrera').hide();
                $('#matricula').hide();
            }
            else if(id == 5)
            {
                $("#invitado .form-control").removeAttr('required');
                $("#carrera .form-control").removeAttr('required');
                $("#matricula .form-control").attr('required','required');
                $('#matricula').show();
                $('#invitado').hide();
                $('#carrera').hide();
                $('#nombre_curso').hide();
            }
            else if(id == 6)
            {
                $("#invitado .form-control").attr('required','required');
                $("#carrera .form-control").attr('required','required');
                $('#invitado').show();
                $('#carrera').show();
                $("#matricula .form-control").removeAttr('required');
                $('#matricula').hide();
                $('#nombre_curso').hide();
            }
        });
    });

	$('#select-tipo-invitado').on('change',function(){

		var id = $(this).val();
		if(id == 2){
			$('#nombre_Empresa').show();
			$('#carrera_nombre').hide();
		}
		else if( id == 1){
			$('#carrera_nombre').show();
            $("#nombre_Empresa .form-control").removeAttr('required');
			$('#nombre_Empresa').hide();

		}
		else
		{
            $("#nombre_Empresa .form-control").removeAttr('required');
			$('#nombre_Empresa').hide();
			$('#carrera').hide();
		}
	});
</script>

<script>
    $(document).ready(function(){
        $('#select-rol').select2();
    });
</script>
@endsection

