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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string("fileUname")->nullable();
            $table->string("fileName");
            $table->string("parentFolder")->nullable();
            $table->string("isFolder")->nullable();
            $table->boolean("isVisible");
            $table->foreignId("user_id")->nullOnDelete()->cascadeOnUpdate()->nullable()->constrained();
            $table->foreignId("company_id")->cascadeOnDelete()->cascadeOnUpdate()->nullable()->constrained();
            $table->foreignId("announcement_id")->cascadeOnDelete()->cascadeOnUpdate()->nullable()->constrained();
            $table->dateTime("postDate")->nullable();
            $table->foreignId("edition_id")->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
        DB::table('files')->insert(
            [
                [
                    'fileUname' => 'Job Application Training Extra Information',
                    'fileName' => 'Job Application Training 15 February',
                    'isVisible' => true,
                    'user_id' => 3,
                    'announcement_id' => 1,
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
        Schema::dropIfExists('files');
    }
};
