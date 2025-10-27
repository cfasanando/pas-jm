<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('catalogs', function (Blueprint $t) {
            $t->id();
            $t->string('group', 50);  // ej: doc, giro, estado_local
            $t->string('code', 50);
            $t->string('label', 191);
            $t->json('meta')->nullable();
            $t->timestamps();

            $t->unique(['group','code']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('catalogs');
    }
};
