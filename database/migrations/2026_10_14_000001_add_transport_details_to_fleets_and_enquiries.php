<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fleets', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique();
            $table->string('baggage_capacity')->nullable();
            $table->text('features')->nullable();
            $table->longText('details')->nullable();
            $table->boolean('is_popular')->default(false);
        });

        foreach (DB::table('fleets')->select('id', 'name')->orderBy('id')->get() as $fleet) {
            DB::table('fleets')->where('id', $fleet->id)->update([
                'slug' => Str::slug($fleet->name).'-'.$fleet->id,
            ]);
        }

        Schema::table('enquiries', function (Blueprint $table) {
            $table->foreignId('fleet_id')->nullable()->constrained('fleets')->nullOnDelete();
            $table->string('pickup_location')->nullable();
            $table->string('dropoff_location')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropConstrainedForeignId('fleet_id');
            $table->dropColumn(['pickup_location', 'dropoff_location']);
        });

        Schema::table('fleets', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn(['slug', 'baggage_capacity', 'features', 'details', 'is_popular']);
        });
    }
};