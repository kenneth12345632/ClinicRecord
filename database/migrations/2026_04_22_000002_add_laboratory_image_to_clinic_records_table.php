<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clinic_records', function (Blueprint $table) {
            $table->string('laboratory_image_path')->nullable()->after('medicines_given');
        });
    }

    public function down(): void
    {
        Schema::table('clinic_records', function (Blueprint $table) {
            $table->dropColumn('laboratory_image_path');
        });
    }
};

