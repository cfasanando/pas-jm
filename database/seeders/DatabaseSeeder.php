<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- Usuario admin
        DB::table('users')->insert([
            'name'              => 'Admin JM',
            'email'             => 'admin@jm.gob.pe',
            'password'          => Hash::make('Admin#12345'),
            'email_verified_at' => now(),
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // --- Secuencias iniciales
        DB::table('sequences')->insert([
            ['key' => 'actas',       'value' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'boleta_A001', 'value' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'expedientes', 'value' => 0, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // --- Settings
        DB::table('settings')->insert([
            ['key' => 'org.name', 'value' => 'Municipalidad de Jesús María — PAS', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'ui.theme', 'value' => 'jm',                                   'created_at' => now(), 'updated_at' => now()],
        ]);

        // --- Catálogos (tipo de documento)
        DB::table('catalogs')->insert([
            ['group' => 'doc', 'code' => 'DNI', 'label' => 'DNI',                    'created_at' => now(), 'updated_at' => now()],
            ['group' => 'doc', 'code' => 'RUC', 'label' => 'RUC',                    'created_at' => now(), 'updated_at' => now()],
            ['group' => 'doc', 'code' => 'CE',  'label' => 'Carné de Extranjería',   'created_at' => now(), 'updated_at' => now()],
            ['group' => 'doc', 'code' => 'PAS', 'label' => 'Pasaporte',              'created_at' => now(), 'updated_at' => now()],
        ]);

        // --- Infracciones base (ejemplos)
        DB::table('infracciones')->insert([
            [
                'codigo'      => 'INF-001',
                'descripcion' => 'Giro distinto al autorizado',
                'base_legal'  => 'Ord. XXXX',
                'multa'       => 920,
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'codigo'      => 'INF-002',
                'descripcion' => 'Funcionamiento fuera de horario',
                'base_legal'  => 'Ord. XXXX',
                'multa'       => 460,
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'codigo'      => 'INF-003',
                'descripcion' => 'Publicidad no autorizada',
                'base_legal'  => 'Ord. XXXX',
                'multa'       => 350,
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);

        // --- Administrados demo (empresa + persona)
        DB::table('administrados')->insert([
            [
                'tipo_doc'     => 'RUC',
                'numero_doc'   => '20123456789',
                'razon_social' => 'Comercial XYZ',
                'email'        => 'contacto@xyz.com',
                'telefono'     => '999999999',
                'direccion'    => 'Av. Brasil 1234',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'tipo_doc'     => 'DNI',
                'numero_doc'   => '44556677',
                'razon_social' => 'Juan Pérez', // usar un solo campo para personas
                'email'        => 'juan@example.com',
                'telefono'     => '988887777',
                'direccion'    => 'Jr. Los Olivos 456',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ]);

        DB::table('settings')->updateOrInsert(['key'=>'app.name'],    ['value'=>'Municipalidad de Jesús María — PAS','created_at'=>now(),'updated_at'=>now()]);
        DB::table('settings')->updateOrInsert(['key'=>'app.logo'],    ['value'=>'','created_at'=>now(),'updated_at'=>now()]);
        DB::table('settings')->updateOrInsert(['key'=>'app.primary'], ['value'=>'#0d6efd','created_at'=>now(),'updated_at'=>now()]);
        DB::table('settings')->updateOrInsert(['key'=>'mail.from'],   ['value'=>'notificaciones@jm.gob.pe','created_at'=>now(),'updated_at'=>now()]);
        DB::table('settings')->updateOrInsert(['key'=>'pdf.footer'],  ['value'=>'Municipalidad de Jesús María — PAS','created_at'=>now(),'updated_at'=>now()]);

    }
}
