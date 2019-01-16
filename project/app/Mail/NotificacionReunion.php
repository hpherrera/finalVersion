<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Reunion;

class NotificacionReunion extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $fecha,$hora,$modificado;
    public function __construct($fecha,$hora,$modificado)
    {
        $this->fecha = $fecha;
        $this->hora = $hora;
        $this->modificado = $modificado;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('email.notificacionReunion');
    }
}
