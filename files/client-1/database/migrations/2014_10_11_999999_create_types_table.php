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
        Schema::create('types', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->timestamps();
        });

        DB::table('types')->insert(
            [
                [
                    'name' => 'student',
                    'created_at' => now()
                ],
                [
                    'name' => 'company',
                    'created_at' => now(),
                ],
                [
                    'name' => 'coordinator',
                    'created_at' => now(),
                ],
                [
                    'name' => 'admin',
                    'created_at' => now(),
                ],
                [
                    'name' => 'contact',
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
        Schema::dropIfExists('types');
    }
};
