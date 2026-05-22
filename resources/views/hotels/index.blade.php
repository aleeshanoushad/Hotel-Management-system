@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Hotels</span>
        <div class="d-flex gap-2 align-items-center">
            <form id="hotel-filter-form" method="GET" action="{{ route('hotels.index') }}" class="d-flex gap-2">
                <input id="hotelFilter" type="text" name="city" class="form-control form-control-sm" placeholder="Filter by city" value="{{ $city }}">
            </form>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#hotelModal">Add Hotel</button>
        </div>
    </div>
    <div class="card-body">
        @if($hotels->count())
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>City</th>
                            <th>Country</th>
                            <th>Rating</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hotels as $hotel)
                            <tr>
                                <td>{{ $hotel->name }}</td>
                                <td>{{ $hotel->city->name ?? '' }}</td>
                                <td>{{ $hotel->country->name ?? '' }}</td>
                                <td>{{ $hotel->rating }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $hotels->withQueryString()->links() }}</div>
        @else
            <p class="mb-0">No hotels found.</p>
        @endif
    </div>
</div>

<!-- Hotel Modal -->
<div class="modal fade" id="hotelModal" tabindex="-1" aria-labelledby="hotelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hotelModalLabel">Add Hotel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('hotels.store') }}" id="hotelCreateForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Country</label>
                        <select name="country_id" id="countrySelect" class="form-select @error('country_id') is-invalid @enderror" required>
                            <option value="">Choose country</option>
                            @foreach(\App\Models\Country::orderBy('name')->get() as $country)
                                <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                            @endforeach
                        </select>
                        @error('country_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">City</label>
                        <select name="city_id" id="citySelect" class="form-select @error('city_id') is-invalid @enderror" required>
                            <option value="">Choose city</option>
                            @if(old('country_id'))
                                @foreach(\App\Models\City::where('country_id', old('country_id'))->orderBy('name')->get() as $city)
                                    <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('city_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rating</label>
                        <input type="number" name="rating" value="{{ old('rating', 5) }}" class="form-control @error('rating') is-invalid @enderror" min="1" max="5" required>
                        @error('rating')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="hotelCreateForm">Create Hotel</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const hotelFilter = document.querySelector('#hotelFilter');
    let hotelFilterTimeout;

    if (hotelFilter) {
        hotelFilter.addEventListener('keyup', function () {
            clearTimeout(hotelFilterTimeout);
            hotelFilterTimeout = setTimeout(() => {
                document.querySelector('#hotel-filter-form').submit();
            }, 300);
        });
    }

    // dynamic city dropdown when country changes
    const countrySelect = document.getElementById('countrySelect');
    if (countrySelect) {
        countrySelect.addEventListener('change', function (e) {
            const countryId = e.target.value;
            const citySelect = document.getElementById('citySelect');
            citySelect.innerHTML = '<option value="">Choose city</option>';

            if (! countryId) return;

            fetch(`/countries/${countryId}/cities`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(function (city) {
                        const opt = document.createElement('option');
                        opt.value = city.id;
                        opt.textContent = city.name;
                        citySelect.appendChild(opt);
                    });
                })
                .catch(err => console.error(err));
        });
    }

    @if($errors->hasAny(['name','country_id','city_id','rating','description']))
        window.addEventListener('DOMContentLoaded', function () {
            const hotelModal = new bootstrap.Modal(document.getElementById('hotelModal'));
            hotelModal.show();
        });
    @endif
</script>
@endsection
