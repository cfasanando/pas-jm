<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Acta extends Model
{
    protected $fillable = [
        'numero','fecha','hora','lugar','constatacion',
        'inspector_id','administrado_id','lat','lng','estado',
    ];

    protected $casts = ['fecha'=>'date','lat'=>'float','lng'=>'float'];

    public function inspector(): BelongsTo { return $this->belongsTo(User::class, 'inspector_id'); }
    public function administrado(): BelongsTo { return $this->belongsTo(Administrado::class); }

    public function evidencias(): HasMany { return $this->hasMany(Evidencia::class); }

    public function tipificaciones(): HasMany { return $this->hasMany(Tipificacion::class); }

    // Alias conveniente para usar directamente $acta->infracciones
    public function infracciones(): BelongsToMany
    {
        return $this->belongsToMany(Infraccion::class, 'tipificaciones', 'acta_id', 'infraccion_id')
            ->withPivot(['multa','observacion'])
            ->withTimestamps();
    }

    public function getTotalAttribute(): float
    {
        // suma segura del campo 'multa'
        return (float) $this->tipificaciones()->sum('multa');
    }

    public function boleta(): HasOne
    {
        return $this->hasOne(\App\Models\Boleta::class);
    }
}
