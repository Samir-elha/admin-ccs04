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
        Schema::create('editions', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->date("date");
            $table->boolean("isActive");
            $table->integer("numberOfAppointments");
            $table->dateTime("deadline");
            $table->timestamps();
        });

        DB::table('editions')->insert(
            [
                [
                    'name' => 'edition-2023',
                    'date' => '2023-02-15',
                    'isActive' => true,
                    'numberOfAppointments' => '3',
                    'deadline' => "2023-01-15",
                    'created_at' => now()
                ]
            ]
        );
        DB::table('editions')->insert(
            [
                [
                    'name' => 'edition-2022',
                    'date' => '2022-02-15',
                    'isActive' => false,
                    'numberOfAppointments' => '3',
                    'deadline' => "2022-01-15",
                    'created_at' => now()
                ]
            ]
        );
        DB::table('editions')->insert(
            [
                [
                    'name' => 'edition-2021',
                    'date' => '2021-02-15',
                    'isActive' => false,
                    'numberOfAppointments' => '3',
                    'deadline' => "2021-01-15",
                    'created_at' => now()
                ]
            ]
        );
        DB::table('editions')->insert(
            [
                [
                    'name' => 'edition-2020',
                    'date' => '2020-02-15',
                    'isActive' => false,
                    'numberOfAppointments' => '3',
                    'deadline' => "2020-01-15",
                    'created_at' => now()
                ]
            ]
        );
        DB::table('editions')->insert(
            [
                [
                    'name' => 'edition-2019',
                    'date' => '2019-02-15',
                    'isActive' => false,
                    'numberOfAppointments' => '3',
                    'deadline' => "2019-01-15",
                    'created_at' => now()
                ]
            ]
        );
        DB::table('editions')->insert(
            [
                [
                    'name' => 'edition-2018',
                    'date' => '2018-02-15',
                    'isActive' => false,
                    'numberOfAppointments' => '3',
                    'deadline' => "2018-01-15",
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
        Schema::dropIfExists('editions');
    }
};
