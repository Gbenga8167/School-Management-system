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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable;
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('roll_id')->nullable;
            $table->string('dob')->nullable;
            $table->string('gender')->nullable;
            $table->integer('status')->default(1);
            $table->string('photo')->nullable;
            $table->string('parent_name')->nullable;
            $table->string('parent_occupation')->nullable;
            $table->string('parent_gender')->nullable;
            $table->string('state_of_origin')->nullable;
            $table->string('phone_number')->nullable;
            $table->string('address')->nullable;
            $table->string('nationality')->nullable;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
