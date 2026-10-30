<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrackOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_serial'   => ['required', 'string'],
            'customer_phone' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'order_serial.required'   => 'يرجى إدخال رقم الطلب.',
            'customer_phone.required' => 'يرجى إدخال رقم الهاتف المرتبط بالطلب.',
        ];
    }
}