<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage; // <-- IMPORTANTE

class Evidencia extends Model
{
    protected $table = 'evidencias';

    protected $fillable = [
        'acta_id', 'path', 'thumb_path', 'mime', 'size', 'original_name',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    public function acta(): BelongsTo
    {
        return $this->belongsTo(Acta::class);
    }

    // Helpers
    public function getIsImageAttribute(): bool
    {
        return str_starts_with(strtolower((string) $this->mime), 'image/');
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->path);
    }

    public function getThumbUrlAttribute(): string
    {
        $p = $this->thumb_path ?: $this->path;
        return Storage::disk('public')->url($p);
    }
}
