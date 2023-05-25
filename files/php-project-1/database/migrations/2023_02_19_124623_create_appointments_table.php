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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('time_slot_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('recruiter_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('state_id')->nullable()->constrained()->onDelete('set null')->onUpdate('set null');
            $table->timestamps();
        });
        DB::table('appointments')->insert(
            [
                [
                    'time_slot_id' => 1,
                    'recruiter_id' => 1,
                    'user_id' => 2,
                    'state_id' => 2,
                    'created_at' => now()
                ]
            ]
        );
        DB::table('appointments')->insert(
            [
                [
                    'time_slot_id' => 1,
                    'recruiter_id' => 1,
                    'user_id' => 3,
                    'state_id' => 2,
                    'created_at' => now()
                ]
            ]
        );
        DB::table('appointments')->insert(
            [
                [
                    'time_slot_id' => 1,
                    'recruiter_id' => 2,
                    'user_id' => 3,
                    'state_id' => 2,
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
        Schema::dropIfExists('appointments');
    }
};
