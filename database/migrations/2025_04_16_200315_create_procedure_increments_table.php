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
        Schema::connection('sigtap')->create('procedure_increments', function (Blueprint $table) {
            $table->id();
            $table->integer('procedure_id');
            $table->integer('license_id');
            $table->float('hospital_percentage_procedure_value',16,2);
            $table->float('outpatient_percentage_procedure_value',16,2);
            $table->float('profissional_percentage_procedure_value',16,2);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procedure_increments');
    }
};
