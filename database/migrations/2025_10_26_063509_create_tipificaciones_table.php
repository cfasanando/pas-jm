<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('tipificaciones')) {
            Schema::create('tipificaciones', function (Blueprint $table) {
                $table->id();
                $table->foreignId('acta_id')->constrained('actas')->cascadeOnDelete();
                $table->foreignId('infraccion_id')->constrained('infracciones')->restrictOnDelete();
                $table->decimal('multa', 10, 2)->nullable();
                $table->text('observacion')->nullable();
                $table->timestamps();

                // Índices útiles
                $table->index(['acta_id']);
                $table->index(['infraccion_id']);
            });
        }

        // Si por alguna razón alguien creó 'tipificacion' en singular, puedes renombrarla:
        if (Schema::hasTable('tipificacion') && !Schema::hasTable('tipificaciones')) {
            Schema::rename('tipificacion', 'tipificaciones');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tipificaciones');
    }
};
