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
        Schema::create('c_b_t_attempts', function (Blueprint $table) {
            $table->id();

            //which test and which student attempted
            $table->unsignedBigInteger('cbt_test_id');
            $table->unsignedBigInteger('student_id');

            //Total score student got in this attempt
            $table->Integer('score')->nullable();

            //time stamps to track when test started and submitted
            $table->timestamp('started_at')->nullable();
            $table->timestamp('submitted_at')->nullable();

            //Track how long student spent (in minutes)
            $table->Integer('duration_used')->nullable();

            //status can be 'completed', 'in_progress', 'abandoned'
            $table->string('status')->default('in_progress');

            $table->timestamps();

            //foreign key
            $table->foreign('cbt_test_id')->references('id')->on('c_b_t_tests')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('c_b_t_attempts');
    }
};
