<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\ApiValidationResponse;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RoomRequest extends FormRequest
{
    use ApiValidationResponse;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'hotel_id' => 'required|exists:hotels,id',
            'name' => 'required|string|max:255',
            'price_per_night' => 'required|numeric|min:0.01',
            'max_occupancy' => 'required|integer|min:1',
            'available_rooms' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'hotel_id.required' => 'Please select a hotel.',
            'hotel_id.exists' => 'The selected hotel does not exist.',

            'name.required' => 'Room name is required.',
            'name.string' => 'Room name must be text.',
            'name.max' => 'Room name may not exceed 255 characters.',

            'price_per_night.required' => 'Price per night is required.',
            'price_per_night.numeric' => 'Price per night must be a valid number.',
            'price_per_night.min' => 'Price per night must be at least 0.01.',

            'max_occupancy.required' => 'Max occupancy is required.',
            'max_occupancy.integer' => 'Max occupancy must be a whole number.',
            'max_occupancy.min' => 'Max occupancy must be at least 1.',

            'available_rooms.required' => 'Available rooms is required.',
            'available_rooms.integer' => 'Available rooms must be a whole number.',
            'available_rooms.min' => 'Available rooms must be at least 0.',
        ];
    }
}
