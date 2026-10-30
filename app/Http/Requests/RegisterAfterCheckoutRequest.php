<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterAfterCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_serial'   => ['required', 'string', 'exists:orders,order_serial'],
            'customer_phone' => ['required', 'string', 'unique:users,phone'],
            'password'       => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'order_serial.required' => 'رقم الطلب مطلوب.',
            'order_serial.exists'   => 'رقم الطلب غير صحيح.',
            'customer_phone.unique' => 'رقم الهاتف مسجل مسبقاً، يرجى تسجيل الدخول.',
            'password.required'     => 'يرجى إدخال كلمة المرور.',
            'password.min'          => 'كلمة المرور يجب أن تتكون من 8 أحرف على الأقل.',
            'password.confirmed'    => 'كلمة المرور غير متطابقة.',
        ];
    }
}