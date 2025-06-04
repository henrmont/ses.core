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
        Schema::connection('sigtap')->create('cids', function (Blueprint $table) {
            $table->id();
            $table->integer('competence_id');
            $table->string('code');
            $table->string('name');
            $table->string('grievance');
            $table->string('gender');
            $table->string('stadium');
            $table->integer('irradiated_fields');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cids');
    }
};
