<?php

use Illuminate\Database\Seeder;
use App\User;

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
    		"name" => "Testing",
    		"email" => "testing@gmail.com",
    		"password" => "12345678"
    	]);
    }
}
