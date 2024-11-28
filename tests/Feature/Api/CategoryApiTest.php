<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;


class CategoryApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Clean up the categories table
        Category::truncate();
    }

    // Test for storing a category successfully
    public function test_store_category_success()
    {
        // Arrange: Create a user
        $user = User::factory()->create();

        // Generate token if using Sanctum
        $token = $user->createToken('API Token')->plainTextToken;

        // Define valid category data
        $data = [
            'category_name' => 'Electronics',
            'base_price' => 199.99,
            'description' => 'A category for electronic items',
            'color_code' => '#FFFFFF',
        ];

        // Act: Make the request with the token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/backend/categories/store', $data);

        // Assert: Check if the category was created and response status is 201 (Created)
        $response->assertStatus(201);

        $response->assertJsonStructure([
            'data' => ['category_name', 'base_price', 'description', 'color_code', 'status'],
        ]);
    }

    // Test for validation errors when required fields are missing
    public function test_store_category_validation_fail()
    {
       // Arrange: Create a user
       $user = User::factory()->create();

       // Generate token if using Sanctum
       $token = $user->createToken('API Token')->plainTextToken;

        // Act: Make the request with the token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
         ])->postJson('/api/backend/categories/store', [
                             'base_price' => 100.0,
                             'description' => 'This is a test category',
                         ]);

        // Assert: Check if validation error is returned for missing category_name
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['category_name']);
    }

    // Test for duplicate category_name
    public function test_store_category_duplicate_name()
    {
       // Arrange: Create a user
       $user = User::factory()->create();

       // Generate token if using Sanctum
       $token = $user->createToken('API Token')->plainTextToken;

        // First create a category
        Category::create([
            'category_name' => 'Existing Category',
            'base_price' => 100.0,
            'description' => 'This is an existing category',
            'color_code' => '#FF0000',
            'status' => 'publish',
            'created_by' => $user->id,
        ]);
       

        // Act: Make the request with the token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
         ])->postJson('/api/backend/categories/store', [
                             'category_name' => 'Existing Category',  // Duplicate name
                             'base_price' => 200.0,
                             'description' => 'This is a duplicate category',
                             'color_code' => '00FF00',
                         ]);

        // Assert: Check for validation error for duplicate category_name
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['category_name']);
    }

    // Test for Delete
    public function test_delete_category_success()
    {
        // Arrange: Create a user and generate a token
        $user = User::factory()->create();
        $token = $user->createToken('API Token')->plainTextToken;

        // Create a category to delete
        $category = Category::factory()->create();

        // Act: Make the DELETE request with the token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson('/api/backend/categories/' . $category->id);

        // Assert: Check if the category was successfully deleted
        $response->assertStatus(200); // Expecting 200 for successful deletion
        $response->assertJson([
            'success' => true,
            'message' => 'Category deleted successfully',
        ]);

        // Verify the category is no longer in the database
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    
}
