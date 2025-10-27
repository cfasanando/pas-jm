<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Infraccion;

class InitialSeeder extends Seeder {
  public function run(): void {
    if(!User::where('email','admin@jm.gob.pe')->exists()){
      User::create([
        'name'=>'Admin JM','email'=>'admin@jm.gob.pe','password'=>Hash::make('admin123')
      ]);
    }
    DB::table('sequences')->updateOrInsert(['name'=>'BOLETA_A001'],['prefix'=>null,'current'=>0,'padding'=>6]);
    if(!Infraccion::count()){
      Infraccion::create(['codigo'=>'RSA-101','descripcion'=>'Giro sin licencia','multa_min'=>230,'multa_max'=>920]);
      Infraccion::create(['codigo'=>'RSA-202','descripcion'=>'Ocupación de vía pública','multa_min'=>150,'multa_max'=>600]);
    }
  }
}
