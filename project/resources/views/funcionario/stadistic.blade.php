@extends('layouts.app')

@section('content')
<section class="content-header">
	<h1>
		Estadisticas
		<small></small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-home"></i> Home</a></li>
		<li class="active">Todos</li>
	</ol>
</section>

<section class="content">
	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title">Filtros</h3>
		</div>
		<div class="box-body with-border">
			<span class="pull-left margin-right-5">
				<small class="label bg-purple pull-right"> <i class="fa fa-info padding-top-3"></i></small> 
			</span>
			<div class="note">
				<strong>Nota:</strong> Se considera estudiante inactivo cuando posee estado de Egresado o Congelado.
			</div>
		</div>
		<div class="box-body with-border">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label> Módulo </label>
						<select id="select_modules" name="modulo" class="form-control" style="width: 100%;" required multiple="multiple">
						</select>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label> Estado </label>
						<select id="select_states" name="estado" class="form-control" style="width: 100%;" required multiple="multiple">
						</select>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label> Profesor Guía </label>
						<select id="select_professors" name="profesor" class="form-control" style="width: 100%;" required multiple="multiple">
						</select>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label> Tipo </label>
						<select id="select_types" name="tipo" class="form-control" style="width: 100%;" required multiple="multiple">
						</select>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label> Año </label>
						<input id="date_ini" name="year" class="form-control">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label> Semestre </label>
						<select id="select_semesters" class="form-control" name="statuses" style="width: 100%;" required multiple="multiple" autocomplete="off">
							<option value="1">Primero</option>
							<option value="2">Segundo</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="box-body">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>Graficar proyectos según</label>
						<select id="plot_by" class="form-control">
							<option value="1">Módulo</option>
							<option value="2">Estado</option>
							<option value="3">Profesor guía</option>
							<option value="4">Tipo</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="box-footer">
			<div class="row">
				<div class="col-md-12">
					<button id="btn_filters_submit" class="btn btn-primary">Graficar</button>
				</div>
			</div>
		</div>
	</div>

	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title">Gráfico</h3>
			<div class="pull-right box-tools">
				<a id="btn_download" download="Gráfico.jpg" class="btn btn-default btn-xs"><i class="fa fa-download"></i> Descargar gráfico</a>
			</div>
		</div>
		<div class="box-body with-border">
			<div class="chart">
				<canvas id="chart" height="350" width="400" style="display: block; width: 400px; height: 350px;" ></canvas>
			</div>
		</div>
	</div>
</section>
@endsection


@section('style')
@endsection

@section('script')
<script>
	$('#select_modules').select2({
		minimumInputLength: 0,
		language: 'es',
		ajax: {
			url: '/chart/searchModule',
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					term: params.term
				};
			},
			processResults: function (data, params) {
				return {
					results: $.map(data, function (item) {
						return {
							text: item.name,
							id: item.id
						}
					})
				};
			},
			cache: true,
		},
	});

	$('#select_types').select2({
		minimumInputLength: 0,
		language: 'es',
		ajax: {
			url: '/chart/searchType',
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					term: params.term
				};
			},
			processResults: function (data, params) {
				return {
					results: $.map(data, function (item) {
						return {
							text: item.name,
							id: item.id
						}
					})
				};
			},
			cache: true,
		},
	});

	$('#select_states').select2({
		minimumInputLength: 0,
		language: 'es',
		ajax: {
			url: '/chart/searchState',
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					term: params.term
				};
			},
			processResults: function (data, params) {
				return {
					results: $.map(data, function (item) {
						return {
							text: item.name,
							id: item.id
						}
					})
				};
			},
			cache: true,
		},
	});

	$('#select_professors').select2({
		minimumInputLength: 0,
		language: 'es',
		ajax: {
			url: '/chart/searchProfessor',
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					term: params.term
				};
			},
			processResults: function (data, params) {
				return {
					results: $.map(data, function (item) {
						return {
							text: item.name,
							id: item.id
						}
					})
				};
			},
			cache: true,
		},
	});

	$('#date_ini').datepicker({
		startView: 2,
		autoclose: true,
		language: 'es',
		format: 'yyyy',
		viewMode: 'years', 
    	minViewMode: 'years',
		orientation: 'bottom',
	});

	$('#select_semesters').select2();

	$('#btn_filters_submit').click(function(){
		$(this).text('');
		$(this).append('<i class="fa fa-load fa-spin"></i> ');
		$(this).append('Cargando...');

		$.ajax({
			type: 'POST',
			url: '/chart',
			data: {
				_token: '{{ csrf_token() }}',
				modules: $('#select_modules').select2('val'),
				states: $('#select_states').select2('val'),
				types: $('#select_types').select2('val'),
				semesters: $('#select_semesters').select2('val'),
				professors: $('#select_professors').select2('val'),
				date_ini: $('#date_ini').val(),
				plot_by: $('#plot_by').val()
			},
			success: function(data)
			{
				drawChart(data);
			}
		}).done(function()
		{
			$('#btn_filters_submit').text('Graficar');
		});
	})
</script>

<script>
	var colors = [
	    {
	        borderColor: 'rgba(18, 151, 147, 1)',
	        backgroundColor : 'rgba(18, 151, 147, 0.3)'
	    },
	    {
	        borderColor: 'rgba(172, 62, 62, 1)',
	        backgroundColor: 'rgba(172, 62, 62, 0.3)',
	    },
	    {
	        borderColor: 'rgba(90, 80, 80, 1)',
	        backgroundColor: 'rgba(90, 80, 80, 0.3)',
	    },
	    {
	        borderColor: 'rgba(130, 84, 120, 1)',
	        backgroundColor: 'rgba(130, 84, 120, 0.3)', 
	    },
	    {
	        borderColor: 'rgba(18, 151, 59, 1)',
	        backgroundColor: 'rgba(18, 151, 59, 0.3)',
	    },
	    {
	        borderColor: 'rgba(18, 47, 151, 1)',
	        backgroundColor: 'rgba(18, 47, 151, 0.3)',
	    },
	    {
	        borderColor: 'rgba(151, 18, 110, 1)',
	        backgroundColor: 'rgba(151, 18, 110, 0.3)',
	    },
	    {
	        borderColor: 'rgba(179, 174, 26, 1)',
	        backgroundColor: 'rgba(179, 174, 26, 0.3)',
	    },
	    {
	        borderColor: 'rgba(208, 122, 34, 1)',
	        backgroundColor: 'rgba(208, 122, 34, 0.3)',
	    },
	    {
	        borderColor: 'rgba(242, 213, 63, 1)',
	        backgroundColor: 'rgba(242, 213, 63, 0.3)'
	    },
	    {
	        borderColor: 'rgba(147, 214, 14, 1)',
	        backgroundColor: 'rgba(147, 214, 14, 0.3)'
	    },
	    {
	        borderColor: 'rgba(16, 164, 238, 1)',
	        backgroundColor: 'rgba(16, 164, 238, 0.3)'
	    },
	    {
	        borderColor: 'rgba(239, 112, 248, 1)',
	        backgroundColor: 'rgba(239, 112, 248, 0.3)'
	    }
	];

	var labels = ['Semestre 1', 'Semestre 2'];

	Chart.defaults.global.responsive = true;
	Chart.defaults.global.maintainAspectRatio = false;
	Chart.defaults.global.elements.line.tension = 0;
	Chart.defaults.global.tooltips.titleSpacing = 10;
	Chart.defaults.global.tooltips.bodySpacing = 5;
	Chart.defaults.global.tooltips.bodySpacing = 5;
	Chart.defaults.global.legend.labels.boxWidth = 15;
	Chart.defaults.global.animation.duration = 1500;
	Chart.defaults.global.elements.point.radius = 4;
	Chart.defaults.global.elements.point.borderColor = 'rgba(255,255,255,1)';
	Chart.defaults.global.elements.point.borderWidth = 1;

	var chartLabels = [];
	var chartDatasets = [];
	var chartConfig = [];
	var chartCanvas;
	var chart;
	var borderWidth = 2;

	createChart();

	function BarChartObject(label, backgroundColor, borderColor, borderWidth)
    {
        this.label = label;
        this.backgroundColor = backgroundColor;
        this.borderColor = borderColor;
        this.borderWidth = borderWidth;
        this.data = [];
    }

    BarChartObject.prototype.addData = function(index, data) 
    {
        this.data[index] = data;
    }

	function drawChart(data)
	{
		chart.destroy();

		chartLabels = labels;

		jQuery.each(data, function(i, val) 
		{
			var bcObject = new BarChartObject(val.label, colors[i].backgroundColor, colors[i].borderColor, borderWidth);

			jQuery.each(val.data, function(i, val){
				bcObject.addData(i, val);
			});

			chartDatasets.push(bcObject);
		});

		createChart();
        resetVars();
	}

	function createChart()
    {
        chartCanvas = $('#chart').get(0).getContext('2d');
        chart = new Chart(chartCanvas, {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: chartDatasets
        },
        options: {
            responsive: true,
            title:{
                display:false,
                text:'Chart.js Line Chart'
            },
            tooltips: {
                mode: 'index',
                intersect: false,
                callbacks: {
                    label: function(tooltipItems, data) 
                    { 
                        if(tooltipItems.yLabel > 0)
                        {
                            return data.datasets[tooltipItems.datasetIndex].label+': '+tooltipItems.yLabel;
                        }
                    }
                }
            },
            hover: {
                mode: 'nearest',
                intersect: true
            },
            scales: {
                xAxes: [{
                    display: true,
                    stacked: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Semestres',
                    }
                }],
                yAxes: [{
                    display: true,
                    stacked: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'N° de proyectos',
                    },
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },
            animation: {
                onComplete: function(animation) {
                    var dataUrl = document.getElementById("chart").toDataURL();
                    $("#btn_download").attr('href', dataUrl);
                }
            }
        },
        });
    }

    function resetVars()
    {
    	chartLabels = [];
		chartDatasets = [];
    }
</script>
@endsection


