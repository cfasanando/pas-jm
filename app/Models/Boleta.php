<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Boleta extends Model
{
  protected $fillable = ['acta_id','serie','numero','total','estado','pdf_path','hash','notified_at'];
  protected $casts = ['notified_at'=>'datetime'];
  public function acta(){ return $this->belongsTo(Acta::class); }
}
