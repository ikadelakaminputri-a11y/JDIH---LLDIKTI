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
        Schema::create('regulations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();
            $table->string('title', 500);
            $table->string('number', 100);
            $table->string('slug', 255)->unique();
            $table->smallInteger('year')->unsigned()->index();
            $table->enum('status', ['published', 'unpublished'])->default('unpublished');
            $table->timestamps();
            $table->softDeletes(); // deleted_at

            // Index untuk pencarian & filter
            $table->index('title');
            $table->index('number');
            $table->index('category_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regulations');
    }
};
