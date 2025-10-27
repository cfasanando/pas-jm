<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class SequenceService {
  public static function next(string $name, string $prefix = '', int $padding = 6): string {
    return DB::transaction(function() use($name,$prefix,$padding){
      $row = DB::table('sequences')->lockForUpdate()->where('name',$name)->first();
      if(!$row){
        DB::table('sequences')->insert(['name'=>$name,'prefix'=>$prefix,'current'=>1,'padding'=>$padding]);
        $n = 1; $pr=$prefix; $pd=$padding;
      } else {
        $n = $row->current + 1; $pr=$row->prefix ?? ''; $pd=$row->padding ?? 6;
        DB::table('sequences')->where('id',$row->id)->update(['current'=>$n]);
      }
      return $pr . str_pad((string)$n, $pd, '0', STR_PAD_LEFT);
    });
  }
}
