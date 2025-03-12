<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_category()
    {
        $response = $this->postJson('/api/categories', [
            'name' => 'Test Category',
            'description' => 'This is a test category.',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('categories', ['name' => 'Test Category']);
    }

    public function test_can_list_categories()
    {
        Category::factory()->count(3)->create();
        $response = $this->getJson('/api/categories');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    public function test_can_show_category()
    {
        $category = Category::factory()->create();
        $response = $this->getJson('/api/categories/' . $category->id);

        $response->assertStatus(200);
        $response->assertJson(['name' => $category->name]);
    }

    public function test_can_update_category()
    {
        $category = Category::factory()->create();
        $response = $this->putJson('/api/categories/' . $category->id, [
            'name' => 'Updated Category',
            'description' => 'This is an updated category.',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('categories', ['name' => 'Updated Category']);
    }

    public function test_can_delete_category()
    {
        $category = Category::factory()->create();
        $response = $this->deleteJson('/api/categories/' . $category->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
