<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_student_resources(): void
    {
        $student = $this->createStudent();

        $this->getJson('/api/students')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [[
                    'id',
                    'name',
                    'email',
                    'phone',
                    'address',
                    'date_of_birth',
                    'age',
                ]],
            ])
            ->assertJsonPath('data.0.id', $student->id)
            ->assertJsonPath('data.0.date_of_birth', '2000-01-01');
    }

    public function test_store_creates_student_with_optional_date_of_birth_and_returns_201(): void
    {
        $name = str_repeat('A', 101);

        $this->postJson('/api/students', [
            'name' => $name,
            'email' => 'new-student@example.com',
            'phone' => '555-0101',
        ])
            ->assertCreated()
            ->assertJsonPath('data.name', $name)
            ->assertJsonPath('data.date_of_birth', null)
            ->assertJsonPath('data.age', null);

        $student = Student::query()->where('email', 'new-student@example.com')->firstOrFail();

        $this->assertModelExists($student);
    }

    public function test_show_returns_the_requested_student_resource(): void
    {
        $student = $this->createStudent();

        $this->getJson("/api/students/{$student->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $student->id)
            ->assertJsonPath('data.email', $student->email);
    }

    public function test_update_changes_the_student_and_returns_the_updated_resource(): void
    {
        $student = $this->createStudent();

        $this->putJson("/api/students/{$student->id}", [
            'name' => 'Updated Student',
            'email' => $student->email,
            'phone' => '555-0102',
            'address' => 'Updated address',
            'date_of_birth' => '2001-02-03',
        ])
            ->assertOk()
            ->assertJsonPath('data.name', 'Updated Student')
            ->assertJsonPath('data.date_of_birth', '2001-02-03');

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'name' => 'Updated Student',
            'phone' => '555-0102',
        ]);
    }

    public function test_destroy_deletes_the_student_and_returns_no_content(): void
    {
        $student = $this->createStudent();

        $this->deleteJson("/api/students/{$student->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }

    public function test_store_returns_validation_errors_for_missing_required_fields(): void
    {
        $this->postJson('/api/students', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email', 'phone']);

        $this->assertDatabaseCount('students', 0);
    }

    public function test_store_rejects_an_email_that_already_exists(): void
    {
        $this->createStudent();

        $this->postJson('/api/students', [
            'name' => 'Another Student',
            'email' => 'student@example.com',
            'phone' => '555-0103',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('email');

        $this->assertDatabaseCount('students', 1);
    }

    private function createStudent(): Student
    {
        return Student::query()->create([
            'name' => 'Student Example',
            'email' => 'student@example.com',
            'phone' => '555-0100',
            'address' => '1 Example Street',
            'date_of_birth' => '2000-01-01',
        ]);
    }
}
