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
    Schema::create('vehicles', function (Blueprint $table) {
        $table->id();
        $table->string('plate_number', 15)->unique();
        $table->string('brand', 50);
        $table->string('model', 80);
        $table->string('vehicle_type', 50);
        $table->string('color', 30);
        $table->integer('capacity');
        $table->decimal('daily_rate', 10, 2);
        $table->string('status', 30)->default('Disponible');
        $table->timestamps();
    });
}
};