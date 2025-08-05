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
        Schema::create('c_b_t_answers', function (Blueprint $table) {
            $table->id();

            //Link to the student's test attempt and question answered
            $table->unsignedBigInteger('cbt_attempt_id');
            $table->unsignedBigInteger('cbt_question_id');

            //student's selected option(e.g. 'a', 'b', 'c', 'd')
            $table->enum('selected_option', [
                'a', 'b', 'c', 'd'
            ]);

            //whether the selected option is correct or not
            $table->boolean('is_correct')->default(false);
            $table->timestamps();

             //foreign key
             $table->foreign('cbt_attempt_id')->references('id')->on('c_b_t_attempts')->onDelete('cascade');
             $table->foreign('cbt_question_id')->references('id')->on('c_b_t_questions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('c_b_t_answers');
    }
};
