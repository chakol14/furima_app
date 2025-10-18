<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:255'],
            'postal_code'  => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'address'      => ['required', 'string', 'max:255'],
            'building_name'=> ['nullable', 'string', 'max:255'],
        ];
    }
}
