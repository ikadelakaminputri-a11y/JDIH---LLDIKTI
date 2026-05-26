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
        Schema::create('regulation_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regulation_id')
                ->constrained('regulations')
                ->cascadeOnDelete();
            $table->tinyInteger('version_number')->unsigned();
            $table->string('file_path', 500);
            $table->integer('file_size')->unsigned(); // bytes
            $table->boolean('is_active')->default(false);
            $table->foreignId('uploaded_by')
                ->constrained('users')
                ->restrictOnDelete();
            $table->text('notes')->nullable();
            $table->integer('download_count')->unsigned()->default(0);
            $table->timestamp('created_at')->nullable(); // no updated_at by design

            $table->index(['regulation_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regulation_versions');
    }
};
