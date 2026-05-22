<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CountriesAndCitiesSeeder::class,
        ]);

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => 'password',
            ]
        );

        $hotels = [
            ['name' => 'Harbor View Suites', 'city' => 'San Diego', 'country' => 'USA', 'rating' => 5, 'description' => 'Waterfront suites with ocean views.'],
            ['name' => 'City Center Inn', 'city' => 'Chicago', 'country' => 'USA', 'rating' => 4, 'description' => 'Modern hotel near business and entertainment districts.'],
            ['name' => 'Alpine Retreat', 'city' => 'Denver', 'country' => 'USA', 'rating' => 4, 'description' => 'Cozy mountain hotel with modern amenities.'],
        ];

        foreach ($hotels as $hotelData) {
            $country = \App\Models\Country::firstOrCreate(['normalized_name' => mb_strtolower($hotelData['country'])], ['name' => $hotelData['country']]);
            $city = \App\Models\City::firstOrCreate(['country_id' => $country->id, 'normalized_name' => mb_strtolower($hotelData['city'])], ['name' => $hotelData['city'], 'country_id' => $country->id]);

            $hotel = Hotel::create([
                'name' => $hotelData['name'],
                'country_id' => $country->id,
                'city_id' => $city->id,
                'rating' => $hotelData['rating'],
                'description' => $hotelData['description'],
            ]);

            Room::create([
                'hotel_id' => $hotel->id,
                'name' => 'Standard Room',
                'price_per_night' => 129.99,
                'max_occupancy' => 2,
                'available_rooms' => 8,
            ]);

            Room::create([
                'hotel_id' => $hotel->id,
                'name' => 'Family Suite',
                'price_per_night' => 219.99,
                'max_occupancy' => 4,
                'available_rooms' => 4,
            ]);
        }
    }
}
