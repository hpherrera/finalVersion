@component('mail::message')

# Notificación de Revisión

Se ha subido una nueva revision del entregable al sistema el {{ $entregable->fecha }}.

@component('mail::panel')
### Datos del entregable:

- **Entregable:** {{ $entregable->nombre() }}
- **Tarea:** {{ $entregable->tarea->nombre() }}

Para acceder al entregable seleccione <a href="{{$link}}">aquí</a><br>
@endcomponent

Gracias,<br>
Sistema de seguimiento, gestión y control documental de proyectos de título.<br><br>

@endcomponent