<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'image'       => ['required', 'image', 'mimes:jpeg,png'],
            'categories'  => ['required', 'array', 'min:1'],
            'categories.*'=> ['string'],
            'condition'   => ['required', 'string'],
            'price'       => ['required', 'integer', 'min:0'],
            'brand'       => ['nullable', 'string', 'max:255'],
        ];
    }
}
