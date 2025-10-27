<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('boletas', function (Blueprint $t) {
            $t->id();
            $t->string('serie', 10)->default('A001');
            $t->string('numero', 20)->nullable();

            $t->foreignId('acta_id')->nullable()->constrained('actas')->nullOnDelete();
            $t->foreignId('administrado_id')->nullable()->constrained('administrados')->nullOnDelete();

            $t->decimal('monto', 10, 2)->default(0);
            $t->enum('estado', ['emitida','notificada','anulada'])->default('emitida');
            $t->string('pdf_path', 191)->nullable();
            $t->string('qr_hash', 191)->nullable();

            $t->timestamps();

            $t->unique(['serie','numero']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('boletas');
    }
};
