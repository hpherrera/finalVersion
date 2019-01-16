@component('mail::message')

# Notificación de Reunión


@if($modificado == 1)
	Se ha modificado la reunión con su profesor guía.
@elseif($modificado == 0)
	Se ha agendado una nueva reunión con su profesor guía.
@else
	Se ha cancelado la reunión con su profesor guía.
@endif

@component('mail::panel')
### Datos de la reunión:

- **Fecha:** {{ $fecha }}
@if($modificado != 2)
- **Hora:** {{ $hora }}
@endif

@endcomponent

Gracias,<br>
Sistema de seguimiento, gestión y control documental de proyectos de título.<br><br>

@endcomponent