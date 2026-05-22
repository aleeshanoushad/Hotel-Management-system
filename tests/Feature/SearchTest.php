<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_search_returns_available_hotels_for_authenticated_user()
    {
        $user = User::factory()->create();

        $country = \App\Models\Country::firstOrCreate(['normalized_name' => mb_strtolower('USA')], ['name' => 'USA']);
        $city = \App\Models\City::firstOrCreate(['country_id' => $country->id, 'normalized_name' => mb_strtolower('Chicago')], ['name' => 'Chicago', 'country_id' => $country->id]);

        $hotel = Hotel::create([
            'name' => 'Downtown Hotel',
            'city_id' => $city->id,
            'country_id' => $country->id,
            'rating' => 4,
            'description' => 'Central location',
        ]);

        Room::create([
            'hotel_id' => $hotel->id,
            'name' => 'Standard Room',
            'price_per_night' => 100,
            'max_occupancy' => 2,
            'available_rooms' => 3,
        ]);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->get('/api/search?city=Chicago&checkin_date=2026-06-01&checkout_date=2026-06-05&guests=2');

        $response->assertStatus(200);
        $response->assertJsonStructure(['hotels', 'nights']);
        $response->assertJsonCount(1, 'hotels');
    }

    public function test_api_search_route_is_rate_limited_after_ten_requests()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        for ($i = 0; $i < 10; $i++) {
            $response = $this->withHeader('Authorization', 'Bearer '.$token)
                ->get('/api/search?city=Chicago&checkin_date=2026-06-01&checkout_date=2026-06-02&guests=1');

            $response->assertStatus(200);
        }

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->get('/api/search?city=Chicago&checkin_date=2026-06-01&checkout_date=2026-06-02&guests=1');

        $response->assertStatus(429);
    }
}
