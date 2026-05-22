<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\ApiValidationResponse;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class HotelRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'country_id' => 'required|integer|exists:countries,id',
            'city_id' => 'required|integer|exists:cities,id',
            'rating' => 'required|integer|min:1|max:5',
            'description' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Hotel name is required.',
            'name.string' => 'Hotel name must be text.',
            'name.max' => 'Hotel name may not exceed 255 characters.',

            'country_id.required' => 'Please select a country.',
            'country_id.integer' => 'The selected country is invalid.',
            'country_id.exists' => 'The selected country does not exist.',

            'city_id.required' => 'Please select a city.',
            'city_id.integer' => 'The selected city is invalid.',
            'city_id.exists' => 'The selected city does not exist.',

            'rating.required' => 'Rating is required.',
            'rating.integer' => 'Rating must be a whole number.',
            'rating.min' => 'Rating must be at least 1.',
            'rating.max' => 'Rating may not be greater than 5.',

            'description.string' => 'Description must be a string.',
        ];
    }
}
