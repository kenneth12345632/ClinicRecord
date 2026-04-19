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
    Schema::create('clinic_record_medicine', function (Blueprint $table) {
        $table->id();
        // Links to your clinic_records table
        $table->foreignId('clinic_record_id')->constrained()->onDelete('cascade');
        // Links to your medicines table
        $table->foreignId('medicine_id')->constrained()->onDelete('cascade');
        $table->integer('quantity')->default(1);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_record_medicine');
    }
};
