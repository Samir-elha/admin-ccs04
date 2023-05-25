<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('language_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruiter_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('language_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });

        // Insert some users (inside the up-function!)
        DB::table('language_lists')->insert(
            [
                [
                    'recruiter_id' => 1,
                    'language_id' => 1,
                    'created_at' => now()
                ],
                [
                    'recruiter_id' => 2,
                    'language_id' => 1,
                    'created_at' => now()
                ],
                [
                    'recruiter_id' => 3,
                    'language_id' => 2,
                    'created_at' => now()
                ],
                [
                    'recruiter_id' => 1,
                    'language_id' => 2,
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
        Schema::dropIfExists('language_lists');
    }
};
