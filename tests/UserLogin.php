<?php
namespace Tests;

use App\Models\User;

trait UserLogin{
    public $user;
    public $token;

    function getUserToken(){
        // Arrange: Create a user
       $this->user = User::factory()->create();

       // Generate token if using Sanctum
       $this->token = $this->user->createToken('API Token')->plainTextToken;

       return $this->token;
    }
}