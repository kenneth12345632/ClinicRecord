<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clinic_record_medicine', function (Blueprint $table) {
            $table->timestamp('dispensed_at')->nullable()->after('quantity');
        });

        // Historical rows already reduced stock at save time — treat as dispensed.
        DB::table('clinic_record_medicine')
            ->whereNull('dispensed_at')
            ->update(['dispensed_at' => DB::raw('COALESCE(updated_at, created_at)')]);
    }

    public function down(): void
    {
        Schema::table('clinic_record_medicine', function (Blueprint $table) {
            $table->dropColumn('dispensed_at');
        });
    }
};
