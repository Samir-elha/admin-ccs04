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
        Schema::create('specializations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Insert some users (inside the up-function!)
        DB::table('specializations')->insert(
            [
                [
                    'name' => 'APP',
                    'created_at' => now()
                ],
                [
                    'name' => 'ACS',
                    'created_at' => now()
                ],
                [
                    'name' => 'DI',
                    'created_at' => now()
                ],
                [
                    'name' => 'CCS',
                    'created_at' => now()
                ],
                [
                    'name' => 'IOT',
                    'created_at' => now()
                ],
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
        Schema::dropIfExists('specializations');
    }
};
