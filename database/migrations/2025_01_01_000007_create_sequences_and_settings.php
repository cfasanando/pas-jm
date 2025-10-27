<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sequences', function (Blueprint $t) {
            $t->id();
            $t->string('key', 50)->unique();  // ej: actas, boleta_A001
            $t->unsignedBigInteger('value')->default(0);
            $t->timestamps();
        });

        Schema::create('settings', function (Blueprint $t) {
            $t->id();
            $t->string('key', 100)->unique(); // ej: org.name
            $t->text('value')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('sequences');
    }
};
