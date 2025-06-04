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
        Schema::connection('sigtap')->create('procedures', function (Blueprint $table) {
            $table->id();
            $table->integer('competence_id');
            $table->integer('group_id');
            $table->integer('subgroup_id');
            $table->integer('organization_form_id');
            $table->integer('financing_id');
            $table->integer('heading_id')->nullable();
            $table->string('code');
            $table->string('name');
            $table->string('complexity');
            $table->string('gender');
            $table->integer('max_execution');
            $table->integer('days_of_stay');
            $table->integer('time_of_stay');
            $table->integer('points');
            $table->integer('min_age');
            $table->integer('max_age');
            $table->float('hospital_procedure_value',16,2);
            $table->float('outpatient_procedure_value',16,2);
            $table->float('profissional_procedure_value',16,2);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procedures');
    }
};
