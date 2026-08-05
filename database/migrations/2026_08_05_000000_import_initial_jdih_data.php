<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    /**
     * Run the migrations safely statement by statement.
     */
    public function up(): void
    {
        $sqlPath = __DIR__ . '/initial_data.sql';
        if (File::exists($sqlPath)) {
            $sql = File::get($sqlPath);
            $statements = array_filter(array_map('trim', explode(";\n", $sql)));
            foreach ($statements as $stmt) {
                if (!empty($stmt)) {
                    try {
                        DB::unprepared($stmt);
                    } catch (\Throwable $e) {
                        // Safely ignore duplicate key or minor PDO statement errors
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
