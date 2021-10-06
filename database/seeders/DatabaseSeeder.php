<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            "name" => "Jamal",
            "is_admin"=>"1",
            "email"=> "jamal@gmail.com",
            "password"=> bcrypt("password"),
            "gender"=> "0",
            "interested_in"=> "1",
            "dob" => "1998-12-12",
            "height" => "170",
            "nationality" => "Lebanon",
            "bio" => "Hello"
            ]); 
        User::create([
            "name" => "Luna",
            "is_admin"=>"0",
            "email"=> "luna@gmail.com",
            "password"=> bcrypt("password"),
            "gender"=> "1",
            "interested_in"=> "0",
            "dob" => "1998-12-12",
            "height" => "170",
            "nationality" => "Lebanon",
            "bio" => "Hello"
            ]); 
        User::create([
            "name" => "Lana",
            "is_admin"=>"0",
            "email"=> "lana@gmail.com",
            "password"=> bcrypt("password"),
            "gender"=> "1",
            "interested_in"=> "0",
            "dob" => "1998-12-12",
            "height" => "170",
            "nationality" => "Lebanon",
            "bio" => "Hello"
            ]); 
            User::create([
                "name" => "Jina",
                "is_admin"=>"0",
                "email"=> "jina@gmail.com",
                "password"=> bcrypt("password"),
                "gender"=> "1",
                "interested_in"=> "0",
                "dob" => "1998-12-12",
                "height" => "170",
                "nationality" => "Lebanon",
                "bio" => "Hello"
                ]); 
            User::create([
                "name" => "regina",
                "is_admin"=>"0",
                "email"=> "regina@gmail.com",
                "password"=> bcrypt("password"),
                "gender"=> "1",
                "interested_in"=> "0",
                "dob" => "1998-12-12",
                "height" => "170",
                "nationality" => "Lebanon",
                "bio" => "Hello"
                ]);
            User::create([
                "name" => "George",
                "is_admin"=>"0",
                "email"=> "george@gmail.com",
                "password"=> bcrypt("password"),
                "gender"=> "0",
                "interested_in"=> "1",
                "dob" => "1998-12-12",
                "height" => "170",
                "nationality" => "Lebanon",
                "bio" => "Hello"
                ]);

                User::create([
                    "name" => "Maria",
                    "is_admin"=>"0",
                    "email"=> "maria@gmail.com",
                    "password"=> bcrypt("password"),
                    "gender"=> "1",
                    "interested_in"=> "0",
                    "dob" => "1998-12-12",
                    "height" => "170",
                    "nationality" => "Lebanon",
                    "bio" => "Hello"
                    ]);
                User::create([
                    "name" => "rana",
                    "is_admin"=>"0",
                    "email"=> "rana@gmail.com",
                    "password"=> bcrypt("password"),
                    "gender"=> "1",
                    "interested_in"=> "0",
                    "dob" => "1998-12-12",
                    "height" => "170",
                    "nationality" => "Lebanon",
                    "bio" => "Hello"
                    ]);
            User::create([
                "name" => "romeo",
                "is_admin"=>"0",
                "email"=> "romeo@gmail.com",
                "password"=> bcrypt("password"),
                "gender"=> "0",
                "interested_in"=> "0",
                "dob" => "1998-12-12",
                "height" => "170",
                "nationality" => "Lebanon",
                "bio" => "Hello"
                ]);
            User::create([
                "name" => "roni",
                "is_admin"=>"0",
                "email"=> "roni@gmail.com",
                "password"=> bcrypt("password"),
                "gender"=> "0",
                "interested_in"=> "0",
                "dob" => "1998-12-12",
                "height" => "170",
                "nationality" => "Lebanon",
                "bio" => "Hello"
                ]);
            User::create([
                "name" => "kk",
                "is_admin"=>"0",
                "email"=> "kk@gmail.com",
                "password"=> bcrypt("password"),
                "gender"=> "0",
                "interested_in"=> "0",
                "dob" => "1998-12-12",
                "height" => "170",
                "nationality" => "Lebanon",
                "bio" => "Hello"
                ]);

                User::create([
                    "name" => "sooo",
                    "is_admin"=>"0",
                    "email"=> "sooo@gmail.com",
                    "password"=> bcrypt("password"),
                    "gender"=> "0",
                    "interested_in"=> "0",
                    "dob" => "1998-12-12",
                    "height" => "170",
                    "nationality" => "Lebanon",
                    "bio" => "Hello"
                    ]);
                    User::create([
                        "name" => "experimenting",
                        "is_admin"=>"0",
                        "email"=> "experimenting@gmail.com",
                        "password"=> bcrypt("password"),
                        "gender"=> "0",
                        "interested_in"=> "0",
                        "dob" => "1998-12-12",
                        "height" => "170",
                        "nationality" => "Lebanon",
                        "bio" => "Hello"
                        ]);
                User::create([
                    "name" => "rawa2",
                    "is_admin"=>"0",
                    "email"=> "rawa2@gmail.com",
                    "password"=> bcrypt("password"),
                    "gender"=> "0",
                    "interested_in"=> "0",
                    "dob" => "1998-12-12",
                    "height" => "170",
                    "nationality" => "Lebanon",
                    "bio" => "Hello"
                    ]);

                User::create([
                    "name" => "hello",
                    "is_admin"=>"0",
                    "email"=> "hello@gmail.com",
                    "password"=> bcrypt("password"),
                    "gender"=> "1",
                    "interested_in"=> "0",
                    "dob" => "1998-12-12",
                    "height" => "170",
                    "nationality" => "Lebanon",
                    "bio" => "Hello"
                    ]);
                
    }
}
