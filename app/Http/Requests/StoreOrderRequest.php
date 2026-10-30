<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name'               => ['required', 'string', 'max:255'],
            'customer_phone'              => ['required', 'string', 'max:20'],
            'governorate_id' => 'required|exists:governorates,id',
            'address_details'             => ['required', 'string', 'max:500'],
            'pay_method'                  => ['required', 'in:vodafone_cash,instapay'],
            'payment_photo'               => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'items'                       => ['required', 'array', 'min:1'],
            'items.*.product_variance_id' => ['required', 'exists:product_variants,id'],
            'items.*.quantity'            => ['required', 'integer', 'min:1'],
            'items.*.notes'               => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_name.required'               => 'يرجى إدخال الاسم بالكامل.',
            'customer_phone.required'              => 'يرجى إدخال رقم الهاتف للتواصل.',
            'governorate_id.required'              => 'يرجى اختيار المحافظة لحساب مصاريف الشحن.',
            'governorate_id.exists'                => 'المحافظة المختارة غير صالحة.',
            'address_details.required'             => 'يرجى إدخال العنوان بالتفصيل لضمان وصول الشحنة.',
            'pay_method.required'                  => 'يرجى اختيار طريقة الدفع.',
            'pay_method.in'                        => 'طريقة الدفع المختارة غير مدعومة.',
            'payment_photo.required'               => 'يرجى إرفاق صورة إيصال التحويل لتأكيد الطلب.',
            'payment_photo.image'                  => 'الملف المرفق يجب أن يكون صورة صالحة.',
            'payment_photo.max'                    => 'حجم الصورة يجب ألا يتجاوز 5 ميجابايت.',
            'items.required'                       => 'سلة المشتريات فارغة.',
            'items.*.product_variance_id.exists'   => 'أحد المنتجات المختارة غير متوفر حالياً.',
            'items.*.quantity.min'                 => 'الكمية المطلوبة يجب أن تكون 1 على الأقل.',
        ];
    }
}