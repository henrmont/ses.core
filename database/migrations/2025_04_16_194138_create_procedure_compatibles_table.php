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
        Schema::connection('sigtap')->create('procedure_compatibles', function (Blueprint $table) {
            $table->id();
            $table->integer('procedure_id');
            $table->integer('register_id');
            $table->integer('compatible_procedure_id');
            $table->integer('compatible_register_id');
            $table->string('type');
            $table->integer('amount');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procedure_compatibles');
    }
};
