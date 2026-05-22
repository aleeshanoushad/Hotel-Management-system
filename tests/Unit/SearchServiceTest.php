<?php

namespace Tests\Unit;

use App\Models\Hotel;
use App\Models\Room;
use App\Repositories\HotelRepository;
use App\Repositories\SearchRepository;
use App\Services\SearchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SearchServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_service_caches_results_and_returns_available_rooms()
    {
        Cache::spy();

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

        $service = new SearchService(new SearchRepository(new HotelRepository()));

        $filters = [
            'city' => 'Chicago',
            'checkin_date' => '2026-06-01',
            'checkout_date' => '2026-06-05',
            'guests' => 2,
        ];

        $result = $service->search($filters);

        Cache::shouldHaveReceived('remember')->once();

        $this->assertSame(4.0, $result['nights']);
        $this->assertCount(1, $result['hotels']);
        $this->assertSame('Downtown Hotel', $result['hotels'][0]['hotel']['name']);
        $this->assertSame(400.0, $result['hotels'][0]['rooms'][0]['total_price']);

        $secondResult = $service->search($filters);
        $this->assertSame($result, $secondResult);
    }
}
