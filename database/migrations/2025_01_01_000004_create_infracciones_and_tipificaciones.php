<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('infracciones', function (Blueprint $t) {
            $t->id();
            $t->string('codigo', 50)->unique();
            $t->string('descripcion', 191);
            $t->text('base_legal')->nullable();
            $t->decimal('multa', 10, 2)->default(0);
            $t->boolean('activo')->default(true);
            $t->timestamps();
        });

        Schema::create('acta_infraccion', function (Blueprint $t) {
            $t->id();
            $t->foreignId('acta_id')->constrained('actas')->cascadeOnDelete();
            $t->foreignId('infraccion_id')->constrained('infracciones')->cascadeOnDelete();
            $t->enum('gravedad', ['leve','grave','muy_grave'])->nullable();
            $t->timestamps();

            $t->unique(['acta_id','infraccion_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('acta_infraccion');
        Schema::dropIfExists('infracciones');
    }
};
