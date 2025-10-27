<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('evidencias', function (Blueprint $t) {
            $t->id();
            $t->foreignId('acta_id')->constrained('actas')->cascadeOnDelete();
            $t->enum('tipo', ['foto','video'])->default('foto');
            $t->string('path', 191);
            $t->string('hash', 191)->nullable();
            $t->unsignedBigInteger('size_bytes')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('evidencias');
    }
};
