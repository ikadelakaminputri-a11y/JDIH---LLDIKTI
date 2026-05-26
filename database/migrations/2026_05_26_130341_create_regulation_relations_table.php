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
        Schema::create('regulation_relations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_regulation_id')
                ->constrained('regulations')
                ->cascadeOnDelete();
            $table->foreignId('target_regulation_id')
                ->constrained('regulations')
                ->cascadeOnDelete();
            $table->enum('relation_type', ['mengubah', 'mencabut', 'dicabut_sebagian']);
            $table->timestamps();

            // Pastikan source != target dicek di level aplikasi
            $table->index(['source_regulation_id', 'target_regulation_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regulation_relations');
    }
};
