<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tipificacion extends Model
{
    protected $table = 'tipificaciones';
    protected $fillable = ['acta_id','infraccion_id','multa','observacion'];

    public function acta(): BelongsTo
    {
        return $this->belongsTo(Acta::class);
    }

    public function infraccion(): BelongsTo
    {
        return $this->belongsTo(Infraccion::class);
    }
}
