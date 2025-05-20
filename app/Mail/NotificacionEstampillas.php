<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificacionEstampillas extends Mailable
{
    use Queueable, SerializesModels;
    public $detalleCorreo;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($detalleCorreo)
    {
        $this->detalleCorreo = $detalleCorreo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->detalleCorreo['documento'] == 'NO'){
            return  $this->subject($this->detalleCorreo['Subject'])->view('emails.notificacion_liqestampillas');
            }else if($this->detalleCorreo['documento'] == 'SI'){
                $path = public_path('storage/documentos_espectaculos/'.$this->detalleCorreo['radicado'].'/ACTO-ADMINISTRATIVO-RAD-'.$this->detalleCorreo['radicado'].'.pdf');
                return  $this->subject($this->detalleCorreo['Subject'])->view('emails.notificacion_liqestampillas')->attach($path);
            }else if($this->detalleCorreo['documento'] == 'SI-DEVOLUCION'){
                $path = public_path('storage/documentos_espectaculos/'.$this->detalleCorreo['radicado'].'/DEVOLUCION-GARANTIA-'.$this->detalleCorreo['radicado'].'.pdf');
                return  $this->subject($this->detalleCorreo['Subject'])->view('emails.notificacion_liqestampillas')->attach($path);
            }else{
                $path = public_path('storage/documentos_espectaculos/'.$this->detalleCorreo['radicado'].'/ACTO-ADMINISTRATIVO-REVOCADO-RAD-'.$this->detalleCorreo['radicado'].'.pdf');
                return  $this->subject($this->detalleCorreo['Subject'])->view('emails.notificacion_liqestampillas')->attach($path);

            }
    }
}
