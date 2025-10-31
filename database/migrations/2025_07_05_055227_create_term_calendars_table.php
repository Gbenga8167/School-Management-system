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
        Schema::create('term_calendars', function (Blueprint $table) {
            $table->id();
            $table->string('session');
            $table->string('term');
            $table->date('next_term_begins');
            $table->timestamps();

            // 1 row per pair
            $table->unique(['session', 'term']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('term_calendars');
    }
};
