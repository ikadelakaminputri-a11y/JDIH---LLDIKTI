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
            $table->foreignId('source_regulation_id')->constrained('regulations')->cascadeOnDelete();
            $table->foreignId('target_regulation_id')->constrained('regulations')->cascadeOnDelete();
            $table->string('relation_type');
            $table->timestamps();
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
