<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clinic_records', function (Blueprint $table) {
            $table->timestamp('published_to_registry_at')->nullable()->after('doctor_consulted_by');
        });

        DB::table('clinic_records')->whereNull('published_to_registry_at')->update([
            'published_to_registry_at' => DB::raw('COALESCE(updated_at, created_at)'),
        ]);

        // Doctor/nurse EMR rows must not be treated as published until BHW finishes dispensing
        // (or confirms a zero-medicine visit). The blanket backfill above would wrongly show them on Clinic Records.
        DB::statement(<<<'SQL'
UPDATE clinic_records cr
SET published_to_registry_at = NULL
WHERE cr.doctor_consulted_by IS NOT NULL
AND (
    EXISTS (
        SELECT 1 FROM clinic_record_medicine crm
        WHERE crm.clinic_record_id = cr.id AND crm.dispensed_at IS NULL
    )
    OR NOT EXISTS (
        SELECT 1 FROM clinic_record_medicine crm
        WHERE crm.clinic_record_id = cr.id
    )
)
SQL);
    }

    public function down(): void
    {
        Schema::table('clinic_records', function (Blueprint $table) {
            $table->dropColumn('published_to_registry_at');
        });
    }
};
