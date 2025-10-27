<?php

namespace App\Mail;

use App\Models\Boleta;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BoletaEmitida extends Mailable
{
  use Queueable, SerializesModels;

  public function __construct(public Boleta $boleta, public string $pdfPath){}

  public function build(){
    return $this->subject('Boleta de Infracción '.$this->boleta->serie.'-'.str_pad($this->boleta->numero,6,'0',STR_PAD_LEFT))
      ->view('emails.boleta_emitida')
      ->attach($this->pdfPath, ['as'=>'boleta.pdf','mime'=>'application/pdf']);
  }
}
