<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use Storage;
use Illuminate\Http\UploadFile;

class UserTestSuccess extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function store()
    {
        $this->withoutExceptionHandling();

        // $this->post("/user",[]); or

        $this->json("POST","/user",[        
            "email" => "testing@gmail.com",
            "password" => "12345678"
        ])
        ->assertStatus(422)
        // ->assertExactJson([
        ->assertJson([
            "status" => "Failed",
        ]);

        $this->json("POST","/user",[
            "name" => "test ing",            
            "password" => "87654324614365456213"
        ])
        ->assertStatus(422)
        ->assertJson([
            "status" => "Failed"
        ]);

        $this->json("POST","/user",[
            "name" => "test ing",
            "email" => "test@gmail.com"
        ])
        ->assertStatus(422)
        ->assertJson([
            "status" => "Failed"
        ]);

        $this->assertCount(0,User::all());

        // UPLOAD FILE
        // Storage::fake("avatars");
        // $file = UploadFile::fake()->image("avatar.png");
        /// $reponse = $this->json("POST","/user",[
            // "avatar" => $file
        // ]);
        // Storage::disk("avatars")->assertExists($file->hasName());
        // Storage::disk("acatars")->assertMissing('name file');
    }


    /** @test */
    public function destory(){
        $this->withoutExceptionHandling();

        $this->json("POST","/user",[
            "name" => "test",
            "email" => "testing@gmail.com",
            "password" => "12345678"
        ]);

        $this->assertCount(1,User::all());
        
        $this->json("DELETE","/user/1")
            ->assertStatus(200)
            ->assertJson([
                "status" => "Success",
            ]);

        $this->assertCount(0,User::all());
    }

    /** @test */
    public function update(){
        $this->withoutExceptionHandling();

        $this->json("POST","/user",[
            "name" => "test",
            "email" => "testing@gmail.com",
            "password" => "12345678"
        ])
        ->assertStatus(201)
        ->assertJson([
            "status" => "Success",
        ]);

        $this->assertCount(1,User::all());
    
        $this->json("PUT","/user/1",[
            "name" => "testing",
            "email" => "testing@testing.com",
            "password" => "12345678"
        ])
        ->assertStatus(200)
        ->assertJson([
            "status" => "Success"
        ]);

        $this->assertCount(1,
                User::where([
                    "name" => "testing",
                    "email" => "testing@testing.com",
                    "password" => "12345678"
                ])->get()     
            );
    }

    /** @test */
    public function index(){
        $this->withoutExceptionHandling();

        User::create([
            "name" => "test",
            "email" => "test@gmail.com",
            "password" => "12345678"
        ]);

        $this->json("GET","/user")
            ->assertStatus(200)
            ->assertJson([
                "total" => 1
            ]);    

        User::create([
            "name" => "test1",
            "email" => "test1@gmail.com",
            "password" => "123456789"
        ]);

        User::create([
            "name" => "test2",
            "email" => "test2@gmail.com",
            "password" => "123456781910"
        ]);

        $this->json("GET","/user")
             ->assertStatus(200)
             ->assertJson([
                "total" => 3
            ]);

        $this->json("GET","/user",[
                "name" => "test2"
            ])
             ->assertStatus(200)
             ->assertJson([
                "total" => 1
            ]);

        $this->json("GET","/user",[
                "email" => "test2@gmail.com"
            ])
            ->assertStatus(200)
            ->assertJson([
                "total" => 1
            ]);
    }

    /** @test */
    public function show(){
        $this->withoutExceptionHandling();

        $user = User::create([
            "name" => "test",
            "email" => "test@gmail.om",
            "password" => "12345678"
        ]);
    
        $this->json("GET","/user/1")
            ->assertStatus(200)
            ->assertJson($user->toArray());        
    }
}
