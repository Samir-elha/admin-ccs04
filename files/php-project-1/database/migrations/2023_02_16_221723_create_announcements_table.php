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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("content")->default('');
            $table->foreignId('user_id')->constrained()->restrictOnDelete()->cascadeOnUpdate();
            $table->boolean("isVisible");
            $table->dateTime("postDate")->nullable();
            $table->foreignId('edition_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
        DB::table('announcements')->insert(
            [
                [
                    'name' => 'Job Application Training',
                    'content' => 'You will find all the information for th Job Application Training on Wednesday, February 15 in the module JobApplicationTraining 15th of February',
                    'user_id' => 1,
                    'isVisible' => false,
                    'edition_id' => 1,
                    'created_at' => now()
                ],
                [
                    'name' => 'Job Application Training 2',
                    'content' => 'You will find all the information for th Job Application Training on Wednesday, February 15 in the module JobApplicationTraining 15th of February',
                    'user_id' => 1,
                    'isVisible' => false,
                    'edition_id' => 3,
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
        Schema::dropIfExists('announcements');
    }
};
