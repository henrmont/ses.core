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
        Schema::create('counties', function (Blueprint $table) {
            $table->id();
            $table->integer('ibge_code');
            $table->string('name');
            $table->char('fu', 2)->default('MT');
            $table->timestamp('tcu_population_base_year')->nullable();//Ver tipo do dado
            $table->integer('population')->default(0);
            $table->integer('health_region_id');
            $table->string('macroregion', 255);
            $table->string('polemunicipality', 255);
            $table->string('distance_from_pole_municipality');
            $table->string('distance_from_the_capital');
            $table->string('img_map');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('counties');
    }
};
