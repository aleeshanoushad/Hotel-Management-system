@extends('layouts.app')

@section('content')
<div class="row gy-4">
    <div class="col-md-4">
        <div class="card border-primary h-100">
            <div class="card-body">
                <h5 class="card-title">Total hotels</h5>
                <p class="display-6">{{ $totalHotels }}</p>
                <p class="text-muted small mb-0">Total number of hotel properties currently managed in the system.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-success h-100">
            <div class="card-body">
                <h5 class="card-title">Total rooms</h5>
                <p class="display-6">{{ $totalRooms }}</p>
                <p class="text-muted small mb-0">All rooms created across every hotel, including booked and available inventory.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-info h-100">
            <div class="card-body">
                <h5 class="card-title">Available rooms</h5>
                <p class="display-6">{{ $availableRooms }}</p>
                <p class="text-muted small mb-0">Sum of rooms currently marked as available for booking.</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header">Top cities</div>
            <div class="card-body">
                @if($topCities->isEmpty())
                    <div class="text-muted">No hotels available yet.</div>
                @else
                    <div class="accordion" id="cityAccordion">
                        @foreach($topCities as $cityName => $hotels)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $loop->index }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $loop->index }}" aria-expanded="false" aria-controls="collapse{{ $loop->index }}">
                                        {{ $cityName }}
                                        <span class="badge bg-primary ms-3">{{ $hotels->count() }} hotels</span>
                                    </button>
                                </h2>
                                <div id="collapse{{ $loop->index }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $loop->index }}" data-bs-parent="#cityAccordion">
                                    <div class="accordion-body">
                                        @foreach($hotels as $hotel)
                                            <div class="mb-4 p-3 border rounded">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h5 class="mb-1">{{ $hotel->name }}</h5>
                                                        <p class="mb-1 text-muted">{{ $hotel->description }}</p>
                                                    </div>
                                                    <span class="badge bg-secondary">Rating: {{ $hotel->rating }}★</span>
                                                </div>
                                                <div class="small text-muted mb-2">
                                                    Rooms: {{ $hotel->rooms->count() }} | Available: {{ $hotel->rooms->sum('available_rooms') }}
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-bordered mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th>Room</th>
                                                                <th>Price / Night</th>
                                                                <th>Max Occupancy</th>
                                                                <th>Available</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($hotel->rooms as $room)
                                                                <tr>
                                                                    <td>{{ $room->name }}</td>
                                                                    <td>{{ number_format($room->price_per_night, 2) }}</td>
                                                                    <td>{{ $room->max_occupancy }}</td>
                                                                    <td>{{ $room->available_rooms }}</td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="4" class="text-center text-muted">No rooms available for this hotel.</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
