@component('mail::message')

# Notificación Nueva Cuenta

Felicidades has sido ingresado con exito al sistema.

@component('mail::panel')
### Datos para el ingreso:

- **Email:** {{ $user->email }}
- **Contraseña:** {{ $password }}

@endcomponent

Gracias,<br>
Sistema de seguimiento, gestión y control documental de proyectos de título.<br><br>

@endcomponent