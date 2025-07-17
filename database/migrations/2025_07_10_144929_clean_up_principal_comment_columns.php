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
        Schema::table('psycho_assessments', function (Blueprint $table){

            //Drop the wrong column if it exists
            if(Schema::hasColumn('psycho_assessments','principla_comment')){
                $table->dropColumn('principla_comment');
        
        }
             
        //Drop the correct one temporarily if it already present
        if(Schema::hasColumn('psycho_assessments','principal_comment')){
            DB::statement("ALTER TABLE psycho_assessments MODIFY 
            principal_comment TEXT NULL AFTER teacher_comment");
    }else{
        // if it doesnt exist add it in the right spot
        Schema::table('psycho_assessments', function (Blueprint $table){

                $table->text('principal_comment')->nullable()->after('teacher_comment');

         });
        
    }
});


        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
        Schema::table('psycho_assessments', function (Blueprint $table){

            //Drop the correct column 
                $table->dropColumn('principal_comment');
        
        });
    }
};
