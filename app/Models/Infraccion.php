<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Infraccion extends Model
{
    protected $table = 'infracciones'; // evita el plural inglés 'infraccions'
    protected $fillable = ['codigo','descripcion','base_legal','multa','activo'];

    public function actas(): BelongsToMany
    {
        return $this->belongsToMany(Acta::class, 'tipificaciones', 'infraccion_id', 'acta_id')
            ->withPivot(['multa','observacion'])
            ->withTimestamps();
    }
}
