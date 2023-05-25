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
        Schema::create('specialization_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('specialization_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('recruiter_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });

        // Insert some users (inside the up-function!)
        DB::table('specialization_lists')->insert(
            [
                [
                    'specialization_id' => 1,
                    'company_id' => 1,
                    'recruiter_id' => 1,
                    'created_at' => now()
                ],
                [
                    'specialization_id' => 2,
                    'company_id' => 2,
                    'recruiter_id' => 2,
                    'created_at' => now()
                ],
                [
                    'specialization_id' => 3,
                    'company_id' => 1,
                    'recruiter_id' => 1,
                    'created_at' => now()
                ],
                [
                    'specialization_id' => 3,
                    'company_id' => 2,
                    'recruiter_id' => 2,
                    'created_at' => now()
                ],
                [
                    'specialization_id' => 1,
                    'company_id' => 3,
                    'recruiter_id' => 3,
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
        Schema::dropIfExists('specialization_lists');
    }
};
