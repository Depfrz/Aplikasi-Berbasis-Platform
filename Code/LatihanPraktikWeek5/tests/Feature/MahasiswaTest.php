<?php

namespace Tests\Feature;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MahasiswaTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_store_valid_mahasiswa()
    {
        $response = $this->actingAs($this->user)
            ->post(route('mahasiswa.store'), [
                'nim' => '1234567890',
                'nama' => 'Test Mahasiswa',
                'email' => 'test@example.com',
                'prodi' => 'Informatika',
                'kelas' => 'IF-01',
            ]);

        $response->assertRedirect(route('mahasiswa.index'));
        $this->assertDatabaseHas('mahasiswas', ['nim' => '1234567890']);
    }

    public function test_store_invalid_mahasiswa_fails_validation()
    {
        $response = $this->actingAs($this->user)
            ->post(route('mahasiswa.store'), [
                'nim' => '', // Required
                'nama' => 'Test',
                'email' => 'not-an-email',
            ]);

        $response->assertSessionHasErrors(['nim', 'email']);
    }

    public function test_update_mahasiswa()
    {
        $mahasiswa = Mahasiswa::factory()->create();

        $response = $this->actingAs($this->user)
            ->put(route('mahasiswa.update', $mahasiswa->id), [
                'nim' => $mahasiswa->nim,
                'nama' => 'Updated Name',
                'email' => $mahasiswa->email,
                'prodi' => $mahasiswa->prodi,
                'kelas' => $mahasiswa->kelas,
            ]);

        $response->assertRedirect(route('mahasiswa.index'));
        $this->assertDatabaseHas('mahasiswas', ['nama' => 'Updated Name']);
    }

    public function test_delete_mahasiswa()
    {
        $mahasiswa = Mahasiswa::factory()->create();

        $response = $this->actingAs($this->user)
            ->delete(route('mahasiswa.destroy', $mahasiswa->id));

        $response->assertRedirect(route('mahasiswa.index'));
        $this->assertSoftDeleted('mahasiswas', ['id' => $mahasiswa->id]);
    }

    public function test_mahasiswa_pagination_works()
    {
        Mahasiswa::factory(20)->create();

        $response = $this->actingAs($this->user)
            ->get(route('mahasiswa.index'));

        $response->assertStatus(200);
        $response->assertSee('Next'); // Check for pagination link
    }
}
