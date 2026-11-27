<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResourceRouteNamingTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_edit_page_uses_web_routes_for_navigation_and_cancel(): void
    {
        $student = Student::query()->create([
            'name' => 'Student Example',
            'email' => 'student@example.com',
            'phone' => '555-0100',
            'date_of_birth' => '2000-01-01',
        ]);

        $this->get(route('students.edit', $student))
            ->assertOk()
            ->assertSee(route('students.index'), false)
            ->assertSee(route('students.update', $student), false)
            ->assertSee(route('students.show', $student), false)
            ->assertDontSee(route('api.students.show', $student), false);
    }

    public function test_course_edit_page_uses_web_routes_for_navigation_and_cancel(): void
    {
        $course = Course::query()->create([
            'name' => 'Course Example',
            'duration' => 4,
            'fee' => 125.50,
            'difficulty' => 'Easy',
        ]);

        $this->get(route('courses.edit', $course))
            ->assertOk()
            ->assertSee(route('courses.index'), false)
            ->assertSee(route('courses.update', $course), false)
            ->assertSee(route('courses.show', $course), false)
            ->assertDontSee(route('api.courses.show', $course), false);
    }

    public function test_student_update_redirects_to_the_web_detail_page(): void
    {
        $student = Student::query()->create([
            'name' => 'Student Example',
            'email' => 'student@example.com',
            'phone' => '555-0100',
            'date_of_birth' => '2000-01-01',
        ]);

        $this->put(route('students.update', $student), [
            'name' => 'Updated Student',
            'email' => 'student@example.com',
            'phone' => '555-0101',
            'date_of_birth' => '2000-01-01',
        ])
            ->assertRedirect(route('students.show', $student));
    }
}
