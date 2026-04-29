<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Older or hand-edited databases may have a UNIQUE index on role, which prevents
 * multiple accounts per role. The application allows unlimited users per role.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        try {
            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique(['role']);
            });
        } catch (\Throwable) {
            // No such index, or incompatible driver — nothing to drop.
        }
    }

    public function down(): void
    {
        // Intentionally empty: restoring a UNIQUE on role would re-break multi-account-per-role.
    }
};
