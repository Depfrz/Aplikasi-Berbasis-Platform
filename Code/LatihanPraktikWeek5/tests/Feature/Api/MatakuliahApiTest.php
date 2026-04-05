<?php

namespace Tests\Feature\Api;

use App\Models\Matakuliah;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MatakuliahApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_api_can_list_matakuliah()
    {
        Matakuliah::factory(5)->create();
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/v1/matakuliah');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    public function test_api_can_create_matakuliah()
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/v1/matakuliah', [
            'kode_mk' => 'CS101',
            'nama' => 'Intro to Programming',
            'sks' => 3,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.kode_mk', 'CS101');
    }

    public function test_api_validation_fails()
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/v1/matakuliah', [
            'kode_mk' => '',
            'sks' => 10, // Max 6
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['kode_mk', 'sks']);
    }

    public function test_api_unauthorized_fails()
    {
        $response = $this->getJson('/api/v1/matakuliah');
        $response->assertStatus(401);
    }
}
