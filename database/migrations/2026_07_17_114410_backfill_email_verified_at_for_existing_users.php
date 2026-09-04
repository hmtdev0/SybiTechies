<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Email verification is being introduced after users already exist in
     * production. Mark everyone already in the table as verified so this
     * new gate doesn't lock out accounts that predate the feature.
     */
    public function up(): void
    {
        DB::table('users')
            ->whereNull('email_verified_at')
            ->update(['email_verified_at' => now()]);
    }

    public function down(): void
    {
        //
    }
};
