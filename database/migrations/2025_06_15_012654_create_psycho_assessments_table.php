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
        Schema::create('psycho_assessments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('class_id');
            $table->string('term');
            $table->string('session');
            $table->unsignedBigInteger('teacher_id');
//
            //psychomotor

        $table->string('attendance')->nullable();
        $table->string('punctuality')->nullable();
        $table->string('neatness')->nullable();
        $table->string('honesty')->nullable();
        $table->string('musical')->nullable();
        $table->string('initiative')->nullable();
        $table->string('creativity')->nullable();
        $table->string('sport',)->nullable();
        $table->string('perseverance',)->nullable();
        $table->string('cooperation')->nullable();
        $table->text('teacher_comment')->nullable();
        $table->timestamps();
        });



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('psycho_assessments');
    }
};
