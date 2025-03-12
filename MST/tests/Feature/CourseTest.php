<?php

namespace Tests\Feature;

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_course()
    {
        $response = $this->postJson('/api/courses', [
            'name' => 'Test course',
            'description' => 'This is a test course.',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('course', ['name' => 'Test course']);
    }

    public function test_can_list_course()
    {
        Course::factory()->count(3)->create();
        $response = $this->getJson('/api/courses');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    public function test_can_show_course()
    {
        $course = course::factory()->create();
        $response = $this->getJson('/api/course/' . $course->id);

        $response->assertStatus(200);
        $response->assertJson(['name' => $course->name]);
    }

    public function test_can_update_course()
    {
        $course = course::factory()->create();
        $response = $this->putJson('/api/course/' . $course->id, [
            'name' => 'Updated course',
            'description' => 'This is an updated course.',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('course', ['name' => 'Updated course']);
    }

    public function test_can_delete_course()
    {
        $course = course::factory()->create();
        $response = $this->deleteJson('/api/course/' . $course->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('course', ['id' => $course->id]);
    }
}
