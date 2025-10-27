<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('expedientes', function (Blueprint $t) {
            $t->id();
            $t->string('codigo', 30)->unique();
            $t->foreignId('acta_id')->nullable()->constrained('actas')->nullOnDelete();
            $t->enum('estado', ['abierto','en_tramite','concluido','archivado'])->default('abierto');
            $t->string('derivado_a', 191)->nullable();
            $t->date('fecha_apertura')->nullable();
            $t->date('fecha_cierre')->nullable();
            $t->text('observacion')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('expedientes');
    }
};
