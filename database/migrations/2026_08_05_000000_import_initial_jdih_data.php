<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign key checks to allow bulk import safely
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $sqlPath = __DIR__ . '/initial_data.sql';
        if (File::exists($sqlPath)) {
            $sql = File::get($sqlPath);
            // Split into individual SQL statements
            $statements = array_filter(array_map('trim', explode(";\n", $sql)));
            foreach ($statements as $stmt) {
                if (!empty($stmt)) {
                    try {
                        DB::unprepared($stmt);
                    } catch (\Throwable $e) {
                        // Log error for debugging if needed
                        logger()->error('Migration insert statement failed: ' . $e->getMessage());
                    }
                }
            }
        }

        // Restore soft-deleted regulations to ensure all 112+ regulations are visible
        try {
            DB::table('regulations')->update(['deleted_at' => null]);
        } catch (\Throwable $e) {
            // Ignore if table doesn't exist yet
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
