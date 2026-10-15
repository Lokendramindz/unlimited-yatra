<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fleets', function (Blueprint $table) {
            $table->string('vehicle_class')->nullable()->index();
            $table->unsignedSmallInteger('seat_count')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('transmission')->nullable();
            $table->boolean('air_conditioning')->default(true);
            $table->boolean('driver_included')->default(true);
            $table->decimal('per_day_rate', 10, 2)->nullable();
            $table->unsignedSmallInteger('included_km_per_day')->nullable();
            $table->decimal('extra_km_rate', 8, 2)->nullable();
            $table->longText('faqs')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('fleets', function (Blueprint $table) {
            $table->dropIndex(['vehicle_class']);
            $table->dropColumn([
                'vehicle_class', 'seat_count', 'fuel_type', 'transmission', 'air_conditioning',
                'driver_included', 'per_day_rate', 'included_km_per_day', 'extra_km_rate', 'faqs',
            ]);
        });
    }
};