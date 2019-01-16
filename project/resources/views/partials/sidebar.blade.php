<aside class="main-sidebar">
	<section class="sidebar">
		<ul class="sidebar-menu" data-widget="tree">
			<li class="header">NAVEGACIÓN PRINCIPAL</li>
			

			@if(\Auth::user()->Administrador())
			<li><a href="/"><i class="fa fa-home"></i> <span>Home</span></a></li>

			<li class="treeview">
				<a href="#">
					<i class="fa fa-users"></i> <span>Usuarios</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="/index"><i class="fa fa-eye"></i> Ver todos</a></li>
					<li><a href="/create"><i class="fa fa-plus"></i> Agregar</a></li>
					<!--<li><a href="#"><i class="fa fa-plus"></i> Cargar Masivamente</a></li>-->
				</ul>
			</li>

			<li class="treeview">
				<a href="/proyectos">
					<i class="fa fa-archive"></i><span>Proyectos</span>
				</a>
			</li>

			@elseif(\Auth::user()->Funcionario())
			<li><a href="/"><i class="fa fa-home"></i> <span>Proyectos</span></a></li>
			<!--<li class="treeview">
				<a href="/createoldproyect">
					<i class="fa fa-plus"></i> <span>Agregar Proyecto</span>
				</a>
			</li>-->
			<li class="treeview">
				<a href="#">
					<i class="fa fa-users"></i> <span>Estudiantes</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="/estudents"><i class="fa fa-eye"></i> Activos </a></li>
					<li><a href="/estudentsEgresados"><i class="fa fa-eye"></i> Egresados</a></li>
				</ul>
			</li>

			<li class="treeview">
				<a href="#">
					<i class="fa fa-pie-chart"></i> <span>Esadisticas</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="/stadistic"><i class="fa fa-bar-chart"></i>Generales</a></li>
					<li><a href="/promedioduracion"><i class="fa  fa-line-chart"></i>Promedio Egreso</a></li>
				</ul>
			</li>

			@elseif(\Auth::user()->ProfesorGuia())
			<li><a href="/"><i class="fa fa-home"></i> <span>Home</span></a></li>
			<li class="treeview">
				<a href="#">
					<i class="fa fa-users"></i> <span>Estudiantes</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="/estudiantes"><i class="fa fa-eye"></i> Ver todos</a></li>
					<li><a href="/estudiantecreate"><i class="fa fa-plus"></i> Agregar</a></li>
				</ul>
			</li>

			<li class="treeview">
				<a href="#">
					<i class="fa fa-lightbulb-o"></i> <span>Proyectos</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="/"><i class="fa fa-eye"></i> Ver todos</a></li>
					<li><a href="/proyectocreate"><i class="fa fa-plus"></i> Agregar</a></li>
				</ul>
			</li>
			<li><a href="/reunion"><i class="fa fa-calendar"></i><span> Calendario </span>
				<span class="pull-right-container" id="aviso2" hidden>
		            <small class="label pull-right bg-red"> Aviso !</small>
            	</span>
            </a></li>

			@elseif(\Auth::user()->ProfesorCurso())

			<li><a href="/"><i class="fa fa-home"></i><span> Home </span></a></li>
			
			<li class="treeview">
				<a href="#">
					<i class="fa fa-users"></i> <span>Estudiantes</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="/estudiantecreate"><i class="fa fa-plus"></i> Agregar</a></li>
				</ul>
			</li>

			

			@elseif(\Auth::user()->Estudiante())
			<li><a href="/"><i class="fa fa-home"></i> <span>Home</span></a></li>
			<li class="treeview">
				<a href="#">
					<i class="fa fa-map-signs"></i> <span>Hitos</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="/"><i class="fa fa-eye"></i> Ver todos</a></li>
					<li><a href="/hito/create"><i class="fa fa-plus"></i> Agregar</a></li>
				</ul>
			</li>

			<li class="treeview">
				<a href="#">
					<i class="fa fa-tasks"></i> <span>Tareas</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="/indexTarea"><i class="fa fa-eye"></i> Ver todos</a></li>
					<li><a href="/tarea/create"><i class="fa fa-plus"></i> Agregar</a></li>
				</ul>
			</li>

			<li class="treeview">
				<a href="#">
					<i class="fa fa-hand-pointer-o"></i> <span>Entregables</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="/indexEntregable"><i class="fa fa-eye"></i> Ver todos</a></li>
					<li><a href="/entregable/create2"><i class="fa fa-plus"></i> Agregar</a></li>
				</ul>
			</li>

			<li class="treeview">
				<a href="/indexRepositorio">
					<i class="fa fa-archive"></i> <span>Documentos</span>
				</a>
			</li>

			<!--<li class="treeview">
				<a href="#">
					<i class="fa fa-bar-chart"></i> <span>Estadistica</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="#"><i class="fa fa-eye"></i> Semanal </a></li>
					<li><a href="#"><i class="fa fa-eye"></i> Mensual </a></li>
				</ul>
			</li>-->

			<li><a href="/planificacion"><i class="fa fa-calendar"></i><span> Calendario </span>
				<span class="pull-right-container" id="aviso" hidden>
		            <small class="label pull-right bg-red"> Aviso !</small>
            	</span>
			</a></li>

			@elseif(\Auth::user()->Invitado())
			<li >
				<a href="/">
					<i class="fa fa-lightbulb-o"></i> <span>Proyectos</span>
					<span class="pull-right-container">
						
					</span>
				</a>

			</li>
			@endif
		</ul>
	</section>
</aside>