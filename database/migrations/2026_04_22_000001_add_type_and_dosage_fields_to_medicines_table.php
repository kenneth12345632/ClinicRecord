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
        Schema::table('medicines', function (Blueprint $table) {
            $table->string('type')->nullable()->after('name');
            $table->decimal('dosage_value', 10, 2)->nullable()->after('type');
            $table->string('dosage_unit', 10)->nullable()->after('dosage_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->dropColumn(['type', 'dosage_value', 'dosage_unit']);
        });
    }
};

