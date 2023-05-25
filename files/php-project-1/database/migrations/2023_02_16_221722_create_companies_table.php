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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('companyName');
            $table->string('description');
            $table->string('website');
            $table->timestamps();
        });
        $company_names = [
            "Alphi", "Arxus", "Axxes", "Cegeka", "Credon",
            "Cronos", "Dignify", "dotNET lab", "Epic Data", "Eurofins Digital Testing",
            "Exert", "GMI Group", "Gumption Group", "iO (voorheen Intracto)", "Melexis",
            "Netropolix", "Ordina", "TheValueChain", "Tokheim Belgium", "vanroey.be"
        ];
        for ($i = 0; $i <= sizeof($company_names) - 1; $i++) {
            DB::table("companies")->insert([
                [
                    'companyName' => $company_names[$i],
                    'description' => 'We are ' . $company_names[$i],
                    'website' => 'https://www.' . $company_names[$i] . '.com/',
                    /*'user_id' => $i + 6,*/
                    'created_at' => now()
                ]
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
};
