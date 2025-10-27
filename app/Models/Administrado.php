<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Administrado extends Model
{
    protected $fillable = [
        'tipo_doc','numero_doc','razon_social','email','telefono','direccion'
    ];
}
