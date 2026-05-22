<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('normalized_name')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('normalized_name')->nullable()->index();
            $table->timestamps();
        });

        // add country_id and city_id to hotels
        Schema::table('hotels', function (Blueprint $table) {
            $table->foreignId('country_id')->nullable()->after('id')->constrained('countries')->nullOnDelete();
            $table->foreignId('city_id')->nullable()->after('country_id')->constrained('cities')->nullOnDelete();
        });

        // migrate existing country and city data into the new tables
        $hotels = DB::table('hotels')->get();

        foreach ($hotels as $h) {
            $countryName = trim($h->country ?? '');
            $cityName = trim($h->city ?? '');

            if ($countryName !== '') {
                $normalizedCountry = mb_strtolower($countryName);
                $country = DB::table('countries')->where('normalized_name', $normalizedCountry)->first();
                if (! $country) {
                    $countryId = DB::table('countries')->insertGetId([
                        'name' => $countryName,
                        'normalized_name' => $normalizedCountry,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    $countryId = $country->id;
                }

                if ($cityName !== '') {
                    $normalizedCity = mb_strtolower($cityName);
                    $city = DB::table('cities')->where('country_id', $countryId)->where('normalized_name', $normalizedCity)->first();
                    if (! $city) {
                        $cityId = DB::table('cities')->insertGetId([
                            'country_id' => $countryId,
                            'name' => $cityName,
                            'normalized_name' => $normalizedCity,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    } else {
                        $cityId = $city->id;
                    }
                } else {
                    $cityId = null;
                }

                DB::table('hotels')->where('id', $h->id)->update([
                    'country_id' => $countryId,
                    'city_id' => $cityId,
                ]);
            }
        }

        // drop old string columns if they exist
        Schema::table('hotels', function (Blueprint $table) {
            if (Schema::hasColumn('hotels', 'country')) {
                $table->dropColumn('country');
            }
            if (Schema::hasColumn('hotels', 'city')) {
                $table->dropColumn('city');
            }
        });
    }

    public function down(): void
    {
        // restore country and city as simple strings (best-effort)
        Schema::table('hotels', function (Blueprint $table) {
            if (! Schema::hasColumn('hotels', 'country')) {
                $table->string('country')->nullable()->after('name');
            }
            if (! Schema::hasColumn('hotels', 'city')) {
                $table->string('city')->nullable()->after('country');
            }
        });

        // try to populate strings from relations
        $hotels = DB::table('hotels')->get();
        foreach ($hotels as $h) {
            $countryName = null;
            $cityName = null;
            if ($h->country_id) {
                $c = DB::table('countries')->where('id', $h->country_id)->first();
                $countryName = $c->name ?? null;
            }
            if ($h->city_id) {
                $ct = DB::table('cities')->where('id', $h->city_id)->first();
                $cityName = $ct->name ?? null;
            }
            DB::table('hotels')->where('id', $h->id)->update([
                'country' => $countryName,
                'city' => $cityName,
            ]);
        }

        Schema::table('hotels', function (Blueprint $table) {
            if (Schema::hasColumn('hotels', 'city_id')) {
                $table->dropConstrainedForeignId('city_id');
            }
            if (Schema::hasColumn('hotels', 'country_id')) {
                $table->dropConstrainedForeignId('country_id');
            }
        });

        Schema::dropIfExists('cities');
        Schema::dropIfExists('countries');
    }
};
