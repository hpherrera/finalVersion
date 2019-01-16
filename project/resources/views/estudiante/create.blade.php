@extends('layouts.app')

@section('content')
<section class="content-header">
	<h1>
		Estudiante
		<small>Registrar</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-home"></i> Home</a></li>
		<li class="active">Registrar</li>
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
	
	<div class="box box-success">
		<div class="box-header with-border">
			<h3 class="box-title">Registrar Estudiante</h3>
		</div>
		<div class="panel-body">
            <form class="form-horizontal" method="POST" action="/persona">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
                    <label for="nombre" class="col-md-4 control-label">Nombre</label>

                    <div class="col-md-6">
                        <input id="nombre" type="text" class="form-control" name="nombre" value="{{ old('nombre') }}" required autofocus autocomplete="off">

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
                        <input id="apellidos" type="text" class="form-control" name="apellidos" value="{{ old('apellidos') }}" required autofocus autocomplete="off">

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
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="off">

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

				<div id="matricula" class="form-group{{ $errors->has('matricula') ? ' has-error' : '' }}">
                    <label for="nombre" class="col-md-4 control-label">Matricula</label>

                    <div class="col-md-6">
                        <input id="matricula" type="number" class="form-control" name="matricula" value="{{ old('matricula') }}" autocomplete="off" required>

                        @if ($errors->has('matricula'))
                            <span class="help-block">
                                <strong>{{ $errors->first('matricula') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <input type="hidden" name="rol_id[]" value="5">
                @if(\Auth::user()->ProfesorGuia())
                    <input type="hidden" name="profesor" value="1">
                @elseif(\Auth::user()->Profesorcurso())
                    <input type="hidden" name="profesor" value="2">
                @endif
                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-success pull-right">
                            Registrar
                        </button>
                    </div>
                </div>
            </form>
        </div>
	</div>
</section>
@endsection

@section('script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script>
    $(document).ready(function(){
        $('#select-rol').select2();
    });
</script>
@endsection


@section('style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endsection('style')
