<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produtos_categorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto_id')->constrained('produtos');
            $table->foreignId('categoria_id')->constrained('categorias');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos_categorias');
    }
};
