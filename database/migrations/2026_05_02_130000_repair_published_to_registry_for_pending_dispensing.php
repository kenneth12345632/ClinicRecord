<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The initial column backfill set published_to_registry_at on every row, including
     * doctor/nurse consultations that must stay off Clinic Records until BHW clears the
     * medicine queue (or confirms a visit with no prescribed lines).
     */
    public function up(): void
    {
        if (!Schema::hasColumn('clinic_records', 'published_to_registry_at')) {
            return;
        }

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
        // Cannot safely restore prior published timestamps.
    }
};
