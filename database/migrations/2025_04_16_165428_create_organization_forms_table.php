<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'sigtap';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('sigtap')->create('organization_forms', function (Blueprint $table) {
            $table->id();
            $table->integer('competence_id');
            $table->integer('group_id');
            $table->integer('subgroup_id');
            $table->string('code');
            $table->string('name');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_forms');
    }
};
