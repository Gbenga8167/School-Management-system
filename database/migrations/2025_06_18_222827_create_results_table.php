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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('subject_id');
            $table->string('term');
            $table->string('session');
            $table->decimal('ca1', 5, 2)->nullable();
            $table->decimal('ca2', 5, 2)->nullable();
            $table->decimal('exam', 5, 2)->nullable();
            $table->decimal('total', 5, 2)->nullable();
            $table->string('grade')->nullable();
            $table->string('remark')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
