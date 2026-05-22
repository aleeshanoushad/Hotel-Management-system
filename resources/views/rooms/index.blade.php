@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Room List</span>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#roomModal">Add Room</button>
    </div>
    <div class="card-body">
        @if($rooms->count())
            <div class="mb-4 d-flex justify-content-between align-items-center gap-3">
                <div class="flex-fill">
                    <input id="roomFilter" type="text" class="form-control" placeholder="Search rooms by hotel, room name, occupancy, or availability">
                </div>
                <div>
                    <span class="badge bg-secondary">{{ $rooms->total() }} rooms</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered mb-0" id="roomTable">
                    <thead>
                        <tr>
                            <th>Hotel</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Occupancy</th>
                            <th>Available</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rooms as $room)
                            <tr>
                                <td>{{ $room->hotel?->name ?? 'N/A' }}</td>
                                <td>{{ $room->name }}</td>
                                <td>${{ number_format($room->price_per_night, 2) }}</td>
                                <td>{{ $room->max_occupancy }}</td>
                                <td>{{ $room->available_rooms }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $rooms->links() }}</div>
        @else
            <p class="mb-0">No rooms available yet.</p>
        @endif
    </div>
</div>

<!-- Room Modal -->
<div class="modal fade" id="roomModal" tabindex="-1" aria-labelledby="roomModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="roomModalLabel">Add Room</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('rooms.store') }}" id="roomCreateForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Hotel</label>
                        <select name="hotel_id" class="form-select @error('hotel_id') is-invalid @enderror" required>
                            <option value="">Select hotel</option>
                            @foreach($hotels as $hotel)
                                <option value="{{ $hotel->id }}" {{ old('hotel_id') == $hotel->id ? 'selected' : '' }}>{{ $hotel->name }} ({{ $hotel->city->name ?? '' }})</option>
                            @endforeach
                        </select>
                        @error('hotel_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Room name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price per night</label>
                        <input type="number" step="0.01" name="price_per_night" class="form-control @error('price_per_night') is-invalid @enderror" value="{{ old('price_per_night') }}" required>
                        @error('price_per_night')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Max occupancy</label>
                        <input type="number" name="max_occupancy" class="form-control @error('max_occupancy') is-invalid @enderror" value="{{ old('max_occupancy') }}" required>
                        @error('max_occupancy')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Available rooms</label>
                        <input type="number" name="available_rooms" class="form-control @error('available_rooms') is-invalid @enderror" value="{{ old('available_rooms', 1) }}" required>
                        @error('available_rooms')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="roomCreateForm">Create Room</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const roomFilter = document.querySelector('#roomFilter');
    const roomRows = document.querySelectorAll('#roomTable tbody tr');

    if (roomFilter) {
        roomFilter.addEventListener('keyup', function () {
            const query = this.value.trim().toLowerCase();

            roomRows.forEach((row) => {
                const text = row.textContent.trim().toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    }

    @if($errors->hasAny(['hotel_id','name','price_per_night','max_occupancy','available_rooms']))
        window.addEventListener('DOMContentLoaded', function () {
            const roomModal = new bootstrap.Modal(document.getElementById('roomModal'));
            roomModal.show();
        });
    @endif
</script>
@endsection
