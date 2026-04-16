<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
{
    Schema::table('clinic_records', function (Blueprint $table) {
        // Change the age column to string to allow "yrs" and "Mon" labels
        $table->string('age')->change();
    });
}

public function down(): void
{
    Schema::table('clinic_records', function (Blueprint $table) {
        // Revert back to integer if needed
        $table->integer('age')->change();
    });
}
};
