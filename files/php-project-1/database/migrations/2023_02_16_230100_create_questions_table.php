<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string("content");
            $table->foreignId('question_type_id')->constrained()->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('questionaire_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
        DB::table('questions')->insert(
            [
                [
                    'content' => "which companies did you pick?",
                    'question_type_id' => 2,
                    'questionaire_id' => 1,
                    'created_at' => now()
                ]

            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
};
