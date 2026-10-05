<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Zone;
use App\Services\NasaFirmsService;
use Tests\TestCase;

class NasaFirmsTest extends TestCase
{
    public function test_nasa_firms_service_returns_data()
    {
        $service = app(NasaFirmsService::class);
        $result = $service->getHotspots(36.8065, 10.1815, 50, 1);

        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('hotspots', $result);
        $this->assertArrayHasKey('stats', $result);
    }

    public function test_fires_index_page_accessible_for_authenticated_user()
    {
        $user = User::factory()->make([
            'id' => 1,
            'role' => 'user',
        ]);

        $response = $this->actingAs($user)->get(route('front.fires.index'));
        $response->assertStatus(200);
        $response->assertSee('Détection des Départs de Feux');
    }

    public function test_fires_api_returns_json()
    {
        $user = User::factory()->make([
            'id' => 1,
            'role' => 'user',
        ]);

        $response = $this->actingAs($user)->getJson(route('front.fires.api', [
            'lat' => 36.8,
            'lng' => 10.2,
            'radius' => 50,
        ]));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'hotspots',
            'stats',
        ]);
    }
}
