<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreMoodRequest extends FormRequest
{
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'mood' => 'required|string|max:255',
            'note' => 'nullable|string|max:1000',
            'activity' => 'nullable|array',
            'activity.*' => 'string|max:255',
            'sleep_hours' => 'nullable|numeric|min:0|max:24',
            'stress_level' => 'nullable|integer|min:1|max:10',
        ];
    }
}
