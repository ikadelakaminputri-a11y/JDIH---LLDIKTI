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
        if (Schema::hasTable('regulations') && !Schema::hasColumn('regulations', 'publish_date')) {
            Schema::table('regulations', function (Blueprint $table) {
                $table->timestamp('publish_date')->nullable()->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('regulations') && Schema::hasColumn('regulations', 'publish_date')) {
            Schema::table('regulations', function (Blueprint $table) {
                $table->dropColumn('publish_date');
            });
        }
    }
};
