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
   Schema::create('medicines', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('batch_number')->nullable(); // Added to distinguish deliveries
    $table->integer('stock');
    $table->date('expiration_date');
    $table->timestamps(); // Automatically gives us 'created_at' (Arrival Date)
});
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
