<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('administrados', function (Blueprint $t) {
            $t->id();
            $t->enum('tipo_doc', ['DNI','RUC','CE','PAS'])->nullable();
            $t->string('numero_doc', 20)->nullable();
            $t->string('razon_social', 191)->nullable();
            $t->string('nombres', 100)->nullable();
            $t->string('apellidos', 150)->nullable();
            $t->string('email', 191)->nullable();
            $t->string('telefono', 50)->nullable();
            $t->string('direccion', 191)->nullable();
            $t->timestamps();

            $t->unique(['tipo_doc','numero_doc']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('administrados');
    }
};
