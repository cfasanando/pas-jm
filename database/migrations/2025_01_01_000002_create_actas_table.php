<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('actas', function (Blueprint $t) {
            $t->id();
            $t->string('numero', 30)->nullable()->unique();
            $t->date('fecha')->nullable();
            $t->time('hora')->nullable();
            $t->string('lugar', 191)->nullable();
            $t->text('constatacion')->nullable();

            $t->foreignId('inspector_id')->constrained('users');
            $t->foreignId('administrado_id')->nullable()->constrained('administrados')->nullOnDelete();

            $t->decimal('lat', 10, 7)->nullable();
            $t->decimal('lng', 10, 7)->nullable();
            $t->enum('estado', ['borrador','emitida','notificada','anulada'])->default('borrador');

            $t->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('actas');
    }
};
