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
        Schema::connection('sigtap')->create('procedure_modalities', function (Blueprint $table) {
            $table->id();
            $table->integer('procedure_id');
            $table->integer('modality_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procedure_modalities');
    }
};
