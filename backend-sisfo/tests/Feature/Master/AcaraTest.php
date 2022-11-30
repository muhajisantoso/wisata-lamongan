<?php

namespace Tests\Feature\Master;

use Tests\TestCase;
use App\Models\User;
use App\Models\Acara;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AcaraTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();

        Sanctum::actingAs($user);
    }

    public function test_user_can_see_all_data_provided_by_datatable()
    {
        Acara::factory(10)->create();

        $this->json('get', route('api.master.acara.dt'))
            ->assertOk()
            ->assertJsonStructure([
                'data'
            ])
            ->assertJsonCount(10, 'data');
    }

    public function test_user_can_store_new_acara_with_valid_data()
    {
        Storage::fake('acara');

        $acara = Acara::factory()->make();

        $this->json('post', route('api.master.acara.store'), $acara->toArray())
            ->assertOk()
            ->assertJsonFragment([
                'message' => __('response.store.success', ['attribute' => 'acara'])
            ]);
    }

    public function test_user_cant_store_new_acara_with_invalid_data()
    {
        Storage::fake('acara');


        $this->json('post', route('api.master.acara.store'), [])
            ->assertStatus(422)
            ->assertJsonFragment([
                'kategori_id' => [__('validation.required', ['Attribute' => 'Kategori'])],
                'name' => [__('validation.required', ['Attribute' => 'Nama'])],
                'tanggal' => [__('validation.required', ['Attribute' => 'Tanggal'])]
            ]);
    }

    public function test_user_can_see_detail_acara()
    {
        $acara = Acara::factory()->create();

        $this->json('get', route('api.master.acara.show', $acara->id))
            ->assertOk()
            ->assertJsonFragment([
                'name' => $acara->name
            ]);
    }

    public function test_user_can_update_acara_with_valid_data()
    {
        $acara = Acara::factory()->create();

        $updated = $acara->toArray();
        $updated['name'] = $acara->name . '_updated';

        $this->json('put', route('api.master.acara.update', $acara->id), $updated)
            ->assertOk()
            ->assertJsonFragment([
                'message' => __('response.update.success', ['attribute' => 'acara']),
                'name' => $acara->name . '_updated'
            ]);
    }

    public function test_user_can_destroy_acara()
    {
        $acara = Acara::factory()->create();

        $this->json('delete', route('api.master.acara.destroy', $acara->id))
            ->assertNoContent();

        $this->assertSoftDeleted($acara);
    }
}
