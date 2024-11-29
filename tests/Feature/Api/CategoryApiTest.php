<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Tests\UserLogin;


class CategoryApiTest extends TestCase
{
    use UserLogin;

    protected function setUp(): void
    {
        parent::setUp();

        // Clean up the categories table
        Category::truncate();
    }

    // Test for storing a category successfully
    public function test_store_category_success()
    {
        $token = $this->getUserToken();

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
        ])->postJson(route('category.api.store'), $data);

        // Assert: Check if the category was created and response status is 201 (Created)
        $response->assertStatus(201);

        $response->assertJsonStructure([
            'data' => ['category_name', 'base_price', 'description', 'color_code', 'status'],
        ]);
    }

    // Test for validation errors when required fields are missing
    public function test_store_category_validation_fail()
    {
        $token = $this->getUserToken();

        // Act: Make the request with the token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
         ])->postJson(route('category.api.store'), [
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
        $token = $this->getUserToken();

        // First create a category
        Category::create([
            'category_name' => 'Existing Category',
            'base_price' => 100.0,
            'description' => 'This is an existing category',
            'color_code' => '#FF0000',
            'status' => 'publish',
            'created_by' => $this->user->id,
        ]);
       

        // Act: Make the request with the token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
         ])->postJson(route('category.api.store'), [
                             'category_name' => 'Existing Category',  // Duplicate name
                             'base_price' => 200.0,
                             'description' => 'This is a duplicate category',
                             'color_code' => '00FF00',
                         ]);

        // Assert: Check for validation error for duplicate category_name
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['category_name']);
    }

    public function test_update_category_success(){

        // Generate token
        $token = $this->getUserToken();

        // Create a category to delete
        $category = Category::factory()->create();

        // Act: Make the DELETE request with the token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson(route('category.api.update',$category->id),[
            'category_name' => 'Update Category',  // update name
            'base_price' => 200.0,
            'description' => 'This is a duplicate category',
            'color_code' => '#00FF00',
        ]);

        $response->assertStatus(200);

        // Assert the category name is updated
        $this->assertDatabaseHas('categories', [
            'category_name' => 'Update Category',
        ]);

        // Optional: Check that the old name no longer exists
        $this->assertDatabaseMissing('categories', [
            'category_name' => $category->category_name,
        ]);
    }

    public function test_category_update_duplicate_name(){

        // Generate token
        $token = $this->getUserToken();

        // Create a category to delete
        $category1 = Category::factory()->create();

        $category2 = Category::factory()->create();

        // Act: Make the DELETE request with the token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson(route('category.api.update',$category2->id),[
            'category_name' => $category1->category_name,  // update name
            'base_price' => 200.0,
            'description' => 'This is a duplicate category',
            'color_code' => '#00FF00',
        ]);

        $response->assertStatus(409);
    }

    // Test for Delete
    public function test_delete_category_success()
    {
        $token = $this->getUserToken();

        // Create a category to delete
        $category = Category::factory()->create();

        // Act: Make the DELETE request with the token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson(route('category.api.destroy',$category->id));

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
