<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_course_resources(): void
    {
        $course = $this->createCourse();

        $this->getJson('/api/courses')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [[
                    'id',
                    'name',
                    'description',
                    'duration',
                    'fee',
                    'difficulty',
                    'is_active',
                ]],
            ])
            ->assertJsonPath('data.0.id', $course->id)
            ->assertJsonPath('data.0.fee', '125.50');
    }

    public function test_store_creates_course_and_returns_201_with_database_defaults(): void
    {
        $this->postJson('/api/courses', [
            'name' => 'API Fundamentals',
            'description' => 'Build APIs with Laravel.',
            'duration' => 6,
            'fee' => 125.5,
            'difficulty' => 'Medium',
        ])
            ->assertCreated()
            ->assertJsonPath('data.name', 'API Fundamentals')
            ->assertJsonPath('data.fee', '125.50')
            ->assertJsonPath('data.is_active', true);

        $course = Course::query()->where('name', 'API Fundamentals')->firstOrFail();

        $this->assertModelExists($course);
        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'is_active' => true,
        ]);
    }

    public function test_show_returns_the_requested_course_resource(): void
    {
        $course = $this->createCourse();

        $this->getJson("/api/courses/{$course->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $course->id)
            ->assertJsonPath('data.name', $course->name);
    }

    public function test_update_changes_the_course_and_returns_the_updated_resource(): void
    {
        $course = $this->createCourse();

        $this->putJson("/api/courses/{$course->id}", [
            'name' => 'Updated Course',
            'description' => 'Updated description.',
            'duration' => 8,
            'fee' => 200,
            'difficulty' => 'Hard',
            'is_active' => false,
        ])
            ->assertOk()
            ->assertJsonPath('data.name', 'Updated Course')
            ->assertJsonPath('data.fee', '200.00')
            ->assertJsonPath('data.is_active', false);

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'name' => 'Updated Course',
            'is_active' => false,
        ]);
    }

    public function test_destroy_deletes_the_course_and_returns_no_content(): void
    {
        $course = $this->createCourse();

        $this->deleteJson("/api/courses/{$course->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    }

    public function test_store_rejects_invalid_course_values(): void
    {
        $this->postJson('/api/courses', [
            'name' => 'Invalid Course',
            'duration' => 0,
            'fee' => -1,
            'difficulty' => 'Expert',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['duration', 'fee', 'difficulty']);

        $this->assertDatabaseCount('courses', 0);
    }

    private function createCourse(): Course
    {
        return Course::query()->create([
            'name' => 'Course Example',
            'description' => 'Example course description.',
            'duration' => 4,
            'fee' => 125.50,
            'difficulty' => 'Easy',
            'is_active' => true,
        ]);
    }
}
