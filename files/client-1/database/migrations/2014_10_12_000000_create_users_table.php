<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstName');
            $table->string('lastName');
            $table->string('email')->unique();
            $table->boolean('active')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->foreignId('type_id')->constrained()->restrictOnDelete()->restrictOnUpdate();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->timestamps();
            $table->foreignId('specialization_id')->nullable()->cascadeOnDelete()->cascadeOnUpdate()->constrained();
            $table->foreignId("company_id")->nullable()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('rNumber')->unique()->nullable();
            $table->boolean('emailNotification')->default(true);
        });


        // Insert some users (inside the up-function!)
        DB::table('users')->insert(
            [
                [
                    'firstName' => 'Dirk',
                    'lastName' => 'De Peuter',
                    'email' => 'Dirk.depeuter@coordinator.com',
                    'active' => true,
                    'password' => Hash::make('coordinator1234'),
                    'email_verified_at' => now(),
                    'type_id' => 3,
                    'rNumber' => 'R00000001',
                    'emailNotification' => true,
                    'created_at' => now()
                ]
            ]
        );

        DB::table('users')->insert(
            [
                [
                    'firstName' => 'Lwam',
                    'lastName' => 'Teklay',
                    'email' => 'r0878953@student.thomasmore.be',
                    'active' => true,
                    'password' => Hash::make('student1234'),
                    'email_verified_at' => now(),
                    'type_id' => 1,
                    'rNumber' => 'R00000002',
                    'specialization_id' => 1,
                    'emailNotification' => true,
                    'created_at' => now()
                ]
            ]
        );
        DB::table('users')->insert(
            [
                [
                    'firstName' => 'Salih',
                    'lastName' => 'Ekici',
                    'email' => 'EkiciSalih0@gmail.com',
                    'active' => true,
                    'password' => Hash::make('student1234'),
                    'email_verified_at' => now(),
                    'type_id' => 1,
                    'rNumber' => 'R00000003',
                    'specialization_id' => 2,
                    'emailNotification' => true,
                    'created_at' => now()
                ]
            ]
        );
        DB::table('users')->insert(
            [
                [
                    'firstName' => 'Jorn',
                    'lastName' => 'Van Dijck',
                    'email' => 'jorn.van.dijck@gmail.com',
                    'active' => true,
                    'password' => Hash::make('student1234'),
                    'email_verified_at' => now(),
                    'type_id' => 1,
                    'rNumber' => 'R00000004',
                    'specialization_id' => 2,
                    'emailNotification' => true,
                    'created_at' => now()
                ]
            ]
        );



        DB::table('users')->insert(
            [
                [
                    'firstName' => 'Super',
                    'lastName' => 'User',
                    'email' => 'superuser@example.com',
                    'active' => true,
                    'password' => Hash::make('superuser1234'),
                    'email_verified_at' => now(),
                    'type_id' => 4,
                    'rNumber' => 'R00458903',
                    'specialization_id' => 1,
                    'emailNotification' => true,
                    'created_at' => now()
                ]
            ]
        );
        DB::table('users')->insert(
            [
                [
                    'firstName' => 'Jane',
                    'lastName' => 'Doe',
                    'email' => 'mock.contact@company1.com',
                    'active' => true,
                    'password' => Hash::make('company1234'),
                    'email_verified_at' => now(),
                    'type_id' => 2,
                    'company_id' => 3,
                    'emailNotification' => true,
                    'created_at' => now()
                ]
            ]
        );
        DB::table('users')->insert(
            [
                [
                    'firstName' => 'Jane2',
                    'lastName' => 'Doe',
                    'email' => 'mock.contact2@company1.com',
                    'active' => true,
                    'password' => Hash::make('company1234'),
                    'email_verified_at' => now(),
                    'type_id' => 2,
                    'company_id' => 4,
                    'emailNotification' => true,
                    'created_at' => now()
                ]
            ]
        );
        DB::table('users')->insert(
            [
                [
                    'firstName' => 'Jane3',
                    'lastName' => 'Doe',
                    'email' => 'mock.contact3@company1.com',
                    'active' => true,
                    'password' => Hash::make('company1234'),
                    'email_verified_at' => now(),
                    'type_id' => 5,
                    'company_id' => 5,
                    'emailNotification' => true,
                    'created_at' => now()
                ]
            ]
        );

        $company_names = [
            "Alphi", "Arxus", "Axxes", "Cegeka", "Credon",
            "Cronos", "Dignify", "dotNET lab", "Epic Data", "Eurofins Digital Testing",
            "Exert", "GMI Group", "Gumption Group", "iO (voorheen Intracto)", "Melexis",
            "Netropolix", "Ordina", "TheValueChain", "Tokheim Belgium", "vanroey.be"
        ];

        for ($i = 0; $i <= sizeof($company_names) - 1; $i++) {

            DB::table("users")->insert(

                [
                    "firstName" => $company_names[$i],
                    "lastName" => "Company",
                    "email" => ($i + 1) . "@example.com",
                    'password' => Hash::make('123456'),
                    'active' => true,
                    'emailNotification' => true,
                    'email_verified_at' => now(),
                    'type_id' => 2,
                    'created_at' => now()
                ]
            );
        };
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
