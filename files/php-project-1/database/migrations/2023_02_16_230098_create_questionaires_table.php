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
        Schema::create('questionaires', function (Blueprint $table) {
            $table->id();
            $table->string("url");
            $table->foreignId('edition_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });

        DB::table('questionaires')->insert(
            [
                [
                    'url' => "https://docs.google.com/forms/d/e/1FAIpQLScIBeQThica1U9FNg4Z-__QB06XWDPi_hMV7PVf6WfV2lEo0g/viewform?usp=sf_link",
                    'edition_id' => 1,
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
        Schema::dropIfExists('questionaires');
    }
};
