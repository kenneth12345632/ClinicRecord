<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('clinic_records', 'published_to_registry_at')) {
            return;
        }

        // Nothing on Clinic Records should look "released" while medicine lines are still pending.
        DB::statement(<<<'SQL'
UPDATE clinic_records cr
SET published_to_registry_at = NULL
WHERE cr.published_to_registry_at IS NOT NULL
AND EXISTS (
    SELECT 1 FROM clinic_record_medicine crm
    WHERE crm.clinic_record_id = cr.id AND crm.dispensed_at IS NULL
)
SQL);

        // BHW intake placeholders are not finalized EMR visits; keep them off the registry until workflow completes.
        DB::table('clinic_records')
            ->where('diagnosis', 'waiting_for_doctor/nurse')
            ->update(['published_to_registry_at' => null]);
    }

    public function down(): void
    {
        //
    }
};
