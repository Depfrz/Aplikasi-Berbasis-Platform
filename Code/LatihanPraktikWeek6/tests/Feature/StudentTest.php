<?php

namespace Tests\Feature;

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StudentTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_display_student_list(): void
    {
        Student::factory()->count(5)->create();

        $response = $this->get(route('students.index'));

        $response->assertStatus(200);
        $response->assertViewHas('students');
    }

    public function test_can_create_student(): void
    {
        $studentData = [
            'nim' => '12345678',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone_number' => '081234567890',
            'study_program' => 'Informatics Engineering',
        ];

        $response = $this->post(route('students.store'), $studentData);

        $response->assertRedirect(route('students.index'));
        $this->assertDatabaseHas('students', $studentData);
    }

    public function test_validation_rules_for_creating_student(): void
    {
        // NIM is required and unique
        $response = $this->post(route('students.store'), [
            'nim' => '',
            'name' => 'JD', // Min 3 chars
            'email' => 'not-an-email',
            'phone_number' => 'abc', // Numeric
            'study_program' => '',
        ]);

        $response->assertSessionHasErrors(['nim', 'name', 'email', 'phone_number', 'study_program']);
    }

    public function test_name_validation_only_allows_alpha_and_spaces(): void
    {
        $response = $this->post(route('students.store'), [
            'nim' => '12345',
            'name' => 'John Doe 123', // Invalid name with numbers
            'email' => 'john@example.com',
            'phone_number' => '08123',
            'study_program' => 'Informatics',
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_can_update_student(): void
    {
        $student = Student::factory()->create();

        $updatedData = [
            'nim' => '99999999',
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone_number' => '089876543210',
            'study_program' => 'Information Systems',
        ];

        $response = $this->put(route('students.update', $student), $updatedData);

        $response->assertRedirect(route('students.index'));
        $this->assertDatabaseHas('students', array_merge(['id' => $student->id], $updatedData));
    }

    public function test_can_soft_delete_student(): void
    {
        $student = Student::factory()->create();

        $response = $this->delete(route('students.destroy', $student));

        $response->assertRedirect(route('students.index'));
        $this->assertSoftDeleted('students', ['id' => $student->id]);
    }

    public function test_can_restore_soft_deleted_student(): void
    {
        $student = Student::factory()->create();
        $student->delete();

        $response = $this->post(route('students.restore', $student->id));

        $response->assertRedirect(route('students.trashed'));
        $this->assertDatabaseHas('students', ['id' => $student->id, 'deleted_at' => null]);
    }

    public function test_can_search_students(): void
    {
        Student::factory()->create(['name' => 'UniqueName']);
        Student::factory()->create(['name' => 'OtherName']);

        $response = $this->get(route('students.index', ['search' => 'UniqueName']));

        $response->assertStatus(200);
        $response->assertSee('UniqueName');
        $response->assertDontSee('OtherName');
    }
}
