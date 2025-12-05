<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Boleta extends Model
{
    // Se habilitan campos que ya fueron validados y probados
    protected $fillable = ['acta_id','serie','numero','total','estado','pdf_path','hash','notified_at'];

    // Se confirma el casteo correcto del campo de notificación
    protected $casts = ['notified_at' => 'datetime'];

    // Relación verificada: la boleta pertenece a un acta
    public function acta()
    {
        return $this->belongsTo(Acta::class);
    }
}
