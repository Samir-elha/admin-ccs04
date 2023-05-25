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
        Schema::create('recruiters', function (Blueprint $table) {
            $table->id();
            $table->string('firstName');
            $table->string('lastName');
            $table->string('email')->unique();
            $table->foreignId('company_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
        DB::table("recruiters")->insert(
            [
                [
                    'firstName' => 'Werner',
                    'lastName' => 'Roozen',
                    'email' => "recruiter1@example.com",
                    'company_id' => 1,
                    'created_at' => now()
                ],
                [
                    'firstName' => 'Ben',
                    'lastName' => 'Luts',
                    'email' => "recruiter2@example.com",
                    'company_id' => 1,
                    'created_at' => now()
                ]
            ]
        );

        DB::table("recruiters")->insert(
            [
                [
                    'firstName' => 'Larry',
                    'lastName' => 'Pierce',
                    'email' => "anede@nuhikub.tw",
                    'company_id' => rand(1, 20),
                    'created_at' => now()
                ],
                [
                    'firstName' => 'Francisco',
                    'lastName' => 'Soto',
                    'email' => "zuna@vefpar.sn",
                    'company_id' => rand(1, 20),
                    'created_at' => now()
                ],
                [
                    'firstName' => 'Marian',
                    'lastName' => 'Luna',
                    'email' => "be@wez.uk",
                    'company_id' => rand(1, 20),
                    'created_at' => now()
                ],
                [
                    'firstName' => 'May',
                    'lastName' => 'Vega',
                    'email' => "dihulze@memeez.ps",
                    'company_id' => rand(1, 20),
                    'created_at' => now()
                ],
                [
                    'firstName' => 'Roxie',
                    'lastName' => 'Rice',
                    'email' => "osipumik@futiru.bw",
                    'company_id' => rand(1, 20),
                    'created_at' => now()
                ],
                [
                    'firstName' => 'Elva',
                    'lastName' => 'Phillips',
                    'email' => "satep@daclaf.mk",
                    'company_id' => rand(1, 20),
                    'created_at' => now()
                ],
                [
                    'firstName' => 'Andre',
                    'lastName' => 'Barton',
                    'email' => "opuzipbig@dure.gu",
                    'company_id' => rand(1, 20),
                    'created_at' => now()
                ],
                [
                    'firstName' => 'Bertha',
                    'lastName' => 'Wright',
                    'email' => "cizdo@obegovahe.al",
                    'company_id' => rand(1, 20),
                    'created_at' => now()
                ],
                [
                    'firstName' => 'Jim',
                    'lastName' => 'Holloway',
                    'email' => "po@zihrav.kg",
                    'company_id' => rand(1, 20),
                    'created_at' => now()
                ],
                [
                    'firstName' => 'Olive',
                    'lastName' => 'Higgins',
                    'email' => "hedsaice@pohod.ua",
                    'company_id' => rand(1, 20),
                    'created_at' => now()
                ],
                [
                    'firstName' => 'Marie',
                    'lastName' => 'Walters',
                    'email' => "akpic@paslamru.om",
                    'company_id' => rand(1, 20),
                    'created_at' => now()
                ],
                [
                    'firstName' => 'Lenora',
                    'lastName' => 'Ford',
                    'email' => "adadizo@wit.tw",
                    'company_id' => rand(1, 20),
                    'created_at' => now()
                ],
                [
                    'firstName' => 'Glen',
                    'lastName' => 'Weaver',
                    'email' => "vijcoim@doagsub.cl",
                    'company_id' => rand(1, 20),
                    'created_at' => now()
                ],
                [
                    'firstName' => 'Robert',
                    'lastName' => 'Colon',
                    'email' => "cuwuco@hurlupi.bh",
                    'company_id' => rand(1, 20),
                    'created_at' => now()
                ],
                [
                    'firstName' => 'Lewis',
                    'lastName' => 'Ruiz',
                    'email' => "kusbuwe@aw.tn",
                    'company_id' => rand(1, 20),
                    'created_at' => now()
                ],
                [
                    'firstName' => 'Lenora',
                    'lastName' => 'Washington',
                    'email' => "zelpaped@ti.com",
                    'company_id' => rand(1, 20),
                    'created_at' => now()
                ],
                [
                    'firstName' => 'Pauline',
                    'lastName' => 'Hoffman',
                    'email' => "kec@lib.nl",
                    'company_id' => rand(1, 20),
                    'created_at' => now()
                ],
                [
                    'firstName' => 'Elsie',
                    'lastName' => 'Leonard',
                    'email' => "ci@uppo.sg",
                    'company_id' => rand(1, 20),
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
        Schema::dropIfExists('recruiters');
    }
};
