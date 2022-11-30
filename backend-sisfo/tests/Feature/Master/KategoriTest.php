<?php

namespace Tests\Feature\Master;

use App\Models\Kategori;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class KategoriTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();

        Sanctum::actingAs($user);
    }

    public function test_user_can_see_all_data()
    {
        Kategori::factory(10)->create();

        $this->json('get', route('api.master.kategori.json'))
            ->assertOk()
            ->assertJsonStructure([
                'message',
                'data'
            ]);
    }

    public function test_user_can_store_new_kategori_with_valid_data()
    {
        $kategori  = Kategori::factory()->make();

        $this->json('post', route('api.master.kategori.store'), $kategori->toArray())
            ->assertOk()
            ->assertJsonFragment([
                'message' => __('response.store.success', ['attribute' => 'kategori'])
            ]);

        $this->assertDatabaseHas('kategori', $kategori->toArray());
    }

    public function test_user_cant_store_new_kategori_with_invalid_data()
    {
        $this->json('post', route('api.master.kategori.store'), [
            'name' => ''
        ])
            ->assertStatus(422)
            ->assertJsonFragment([
                'name' => [__('validation.required', ['Attribute' => 'Nama'])]
            ]);
    }

    public function test_user_can_see_detail_kategori()
    {
        $kategori = Kategori::factory()->create();

        $this->json('get', route('api.master.kategori.show', $kategori->id))
            ->assertOk()
            ->assertJsonFragment([
                'name' => $kategori->name
            ]);
    }

    public function test_user_can_update_kategori_with_valid_data()
    {
        $kategori = Kategori::factory()->create();

        $this->json('put', route('api.master.kategori.update', $kategori->id), [
            'name' => $kategori->name . '_updated'
        ])
            ->assertOk()
            ->assertJsonFragment([
                'message' => __('response.update.success', ['attribute' => 'kategori']),
                'name' => $kategori->name . '_updated'
            ]);
    }

    public function test_user_cant_update_kategori_with_invalid_data()
    {
        $kategori = Kategori::factory()->create();

        $this->json('put', route('api.master.kategori.update', $kategori->id), [
            'name' => ''
        ])
            ->assertStatus(422)
            ->assertJsonFragment([
                'name' => [__('validation.required', ['Attribute' => 'Nama'])]
            ]);
    }

    public function test_user_can_destroy_kategori_with_valid_id()
    {
        $kategori = Kategori::factory()->create();

        $this->json('delete', route('api.master.kategori.destroy', $kategori->id))
            ->assertNoContent();

        $this->assertSoftDeleted($kategori);
    }

    public function test_user_cant_destroy_kategori_with_invalid_id()
    {
        $this->json('delete', route('api.master.kategori.destroy', 9191919))
            ->assertNotFound();
    }
}
