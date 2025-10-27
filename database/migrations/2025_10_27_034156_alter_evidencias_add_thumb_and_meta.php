<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Agregamos columnas faltantes sin perder datos
        Schema::table('evidencias', function (Blueprint $table) {
            if (!Schema::hasColumn('evidencias', 'thumb_path')) {
                $table->string('thumb_path')->nullable()->after('path');
            }
            if (!Schema::hasColumn('evidencias', 'mime')) {
                $table->string('mime', 150)->nullable()->after('thumb_path');
            }
            if (!Schema::hasColumn('evidencias', 'size')) {
                $table->unsignedBigInteger('size')->nullable()->after('mime');
            }
            if (!Schema::hasColumn('evidencias', 'original_name')) {
                $table->string('original_name')->nullable()->after('size');
            }
        });

        // Si tenías una columna 'tipo', copiamos a 'mime' (si existe)
        if (Schema::hasColumn('evidencias', 'tipo') && Schema::hasColumn('evidencias', 'mime')) {
            DB::statement("UPDATE evidencias SET mime = tipo WHERE (mime IS NULL OR mime = '') AND tipo IS NOT NULL");
        }
    }

    public function down(): void
    {
        Schema::table('evidencias', function (Blueprint $table) {
            if (Schema::hasColumn('evidencias', 'original_name')) $table->dropColumn('original_name');
            if (Schema::hasColumn('evidencias', 'size'))          $table->dropColumn('size');
            if (Schema::hasColumn('evidencias', 'mime'))          $table->dropColumn('mime');
            if (Schema::hasColumn('evidencias', 'thumb_path'))    $table->dropColumn('thumb_path');
        });
    }
};
