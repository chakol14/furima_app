<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_method' => ['required', 'in:コンビニ支払い,カード支払い'],
            'postal_code'    => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'address'        => ['required', 'string', 'max:255'],
            'building_name'  => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'payment_method.required' => '支払い方法を選択してください',
            'payment_method.in' => '支払い方法は選択肢から選んでください',
            'postal_code.required' => '郵便番号を入力してください',
            'postal_code.regex' => '郵便番号はハイフンありの8文字（例：123-4567）で入力してください',
            'address.required' => '配送先住所を入力してください',
            'address.max' => '配送先住所は255文字以内で入力してください',
            'building_name.max' => '建物名は255文字以内で入力してください',
        ];
    }
}
