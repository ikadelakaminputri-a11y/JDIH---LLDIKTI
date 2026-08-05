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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $sqlPath = __DIR__ . '/initial_data.sql';
        if (File::exists($sqlPath)) {
            $sql = File::get($sqlPath);
            $statements = array_filter(array_map('trim', explode(";\n", $sql)));
            foreach ($statements as $stmt) {
                if (!empty($stmt)) {
                    try {
                        DB::unprepared($stmt);
                    } catch (\Throwable $e) {
                        logger()->error('Import execution statement error: ' . $e->getMessage());
                    }
                }
            }
        }

        // Restore soft-deleted regulations to ensure all 112+ regulations are active & visible
        try {
            DB::table('regulations')->update(['deleted_at' => null]);
        } catch (\Throwable $e) {
            //
        }

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
