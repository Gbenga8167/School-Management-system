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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name')->nullable;
            $table->string('address')->nullable;
            $table->string('nationality')->nullable;
            $table->string('phone_number')->nullable;
            $table->string('qualification')->nullable;
            $table->string('work_experience')->nullable;
            $table->string('marital_status')->nullable;
            $table->string('state_of_origin')->nullable;
            $table->string('dob')->nullable;
            $table->string('photo')->nullable;
            $table->string('gender')->nullable;
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
