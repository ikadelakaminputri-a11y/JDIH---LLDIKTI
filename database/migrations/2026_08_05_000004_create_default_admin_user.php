<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@lldikti.test'],
            [
                'name' => 'Admin LLDIKTI XI',
                'password' => Hash::make('password'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@lldikti.go.id'],
            [
                'name' => 'Admin LLDIKTI XI',
                'password' => Hash::make('password'),
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
