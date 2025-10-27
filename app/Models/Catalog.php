<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    protected $fillable = ['group', 'name', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];
}
