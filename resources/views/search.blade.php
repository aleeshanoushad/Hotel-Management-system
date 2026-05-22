@extends('layouts.app')

@section('content')
<div class="row gy-4">
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header">Search Availability</div>
            <div class="card-body">
                <form method="GET" action="{{ route('search.index') }}">
                    <input type="hidden" name="search_submitted" value="1">
                    <div class="mb-3">
                        <label class="form-label">City</label>
                        <select name="city_id" class="form-select @error('city_id') is-invalid @enderror" required>
                            <option value="">Choose city</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ (string) old('city_id', $filters['city_id'] ?? '') === (string) $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}@if($city->country), {{ $city->country->name }}@endif
                                </option>
                            @endforeach
                        </select>
                        @error('city_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Check-in</label>
                        <input type="date" name="checkin_date" value="{{ $filters['checkin_date'] ?? '' }}" class="form-control @error('checkin_date') is-invalid @enderror" required>
                        @error('checkin_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Check-out</label>
                        <input type="date" name="checkout_date" value="{{ $filters['checkout_date'] ?? '' }}" class="form-control @error('checkout_date') is-invalid @enderror" required>
                        @error('checkout_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Guests</label>
                        <input type="number" name="guests" value="{{ $filters['guests'] ?? 1 }}" min="1" class="form-control @error('guests') is-invalid @enderror" required>
                        @error('guests')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button class="btn btn-primary w-100">Search</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header">Available Hotels</div>
            <div class="card-body">
                @if(! empty($results['hotels']) && count($results['hotels']))
                    @foreach($results['hotels'] as $item)
                        <div class="mb-3 border rounded p-3">
                            <h5>{{ $item['hotel']['name'] }} <small class="text-muted">({{ $item['hotel']['city'] }})</small></h5>
                            <p class="mb-1">Rating: {{ $item['hotel']['rating'] }}</p>
                            <p class="mb-2">Total price for {{ $results['nights'] }} night(s): <strong>${{ number_format($item['total_hotel_price'], 2) }}</strong></p>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Room</th>
                                            <th>Price</th>
                                            <th>Occupancy</th>
                                            <th>Available</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($item['rooms'] as $room)
                                            <tr>
                                                <td>{{ $room['name'] }}</td>
                                                <td>${{ number_format($room['price_per_night'], 2) }}</td>
                                                <td>{{ $room['max_occupancy'] }}</td>
                                                <td>{{ $room['available_rooms'] }}</td>
                                                <td>${{ number_format($room['total_price'], 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                @elseif($searchExecuted)
                    <p class="mb-0">No available rooms found for the selected filters.</p>
                @else
                    <p class="mb-0">Enter search criteria to see available rooms.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
