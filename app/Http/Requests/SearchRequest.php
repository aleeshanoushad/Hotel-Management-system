<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\ApiValidationResponse;
use App\Models\City;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    use ApiValidationResponse;

    protected function prepareForValidation(): void
    {
        if ($this->filled('city_id') && ! $this->filled('city')) {
            $city = City::find($this->city_id);
            $this->merge(['city' => $city?->name]);
        }
    }

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
        $rules = [
            'city' => 'nullable|string|max:255',
            'city_id' => 'nullable|integer|exists:cities,id',
            'checkin_date' => 'nullable|date|before:checkout_date',
            'checkout_date' => 'nullable|date|after:checkin_date',
            'guests' => 'nullable|integer|min:1',
        ];

        if ($this->boolean('search_submitted')) {
            $rules['city_id'] = 'required|integer|exists:cities,id';
            $rules['checkin_date'] = 'required|date|before:checkout_date';
            $rules['checkout_date'] = 'required|date|after:checkin_date';
            $rules['guests'] = 'required|integer|min:1';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'city.required_without' => 'Please select a city.',
            'city.string' => 'City must be text.',
            'city.max' => 'City may not exceed 255 characters.',

            'city_id.required_without' => 'Please select a city.',
            'city_id.integer' => 'The selected city is invalid.',
            'city_id.exists' => 'The selected city does not exist.',

            'checkin_date.required' => 'Check-in date is required.',
            'checkin_date.date' => 'Check-in date must be a valid date.',
            'checkin_date.before' => 'Check-in date must be before the checkout date.',

            'checkout_date.required' => 'Check-out date is required.',
            'checkout_date.date' => 'Check-out date must be a valid date.',
            'checkout_date.after' => 'Check-out date must be after the check-in date.',

            'guests.required' => 'Number of guests is required.',
            'guests.integer' => 'Guests must be a whole number.',
            'guests.min' => 'Guests must be at least 1.',
        ];
    }
}
