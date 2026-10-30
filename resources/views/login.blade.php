@extends('layouts.app')

@section('title', 'تسجيل الدخول - عبايات المصطفى')

{{-- لا نحتاج الهيدر والفوتر الافتراضيين هنا --}}
@php
    $hideNavbar = true;
    $hideFooter = true;
@endphp

@push('styles')
<style>
    .font-heading { font-family: 'Aref Ruqaa', serif; }
    input::placeholder { color: #BDBDBD; opacity: 0.6; }
    .duration-700 { transition-duration: 700ms; }
    .placeholder\:text-right::placeholder { text-align: right; }
    .scale-0 { transform: scale(0); }
    .peer:checked ~ .peer-checked\:scale-100 { transform: scale(1); }
</style>
@endpush

@section('content')
<div class="flex-1 flex flex-col items-center justify-center px-4 pt-14 pb-[34px] -mx-5 -mt-6">
    <div class="w-full max-w-[380px] bg-white rounded-[20px] p-8 custom-shadow flex flex-col duration-700">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold font-heading text-[#C2577A] mb-2">عبايات المصطفى</h1>
            <p class="text-sm text-[#8A6E78]">تسجيل الدخول لحسابكِ</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf

            <!-- رقم الهاتف -->
            <div class="space-y-1.5">
                <label for="phone" class="block text-xs font-bold text-[#8A6E78] mr-2">رقم الهاتف</label>
                <div class="relative">
                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required placeholder="01xxxxxxxxx" dir="ltr" class="w-full h-[52px] px-4 rounded-[14px] bg-white border border-[#F0D9DF] focus:outline-none focus:ring-2 focus:ring-[#C2577A]/20 focus:border-[#C2577A] transition-all text-left placeholder:text-right">
                </div>
                @error('phone') <span class="text-xs text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- كلمة المرور -->
            <div class="space-y-1.5">
                <label for="password" class="block text-xs font-bold text-[#8A6E78] mr-2">كلمة المرور</label>
                <div class="relative">
                    <input type="password" name="password" id="password" required placeholder="••••••••" class="w-full h-[52px] pl-4 pr-12 rounded-[14px] bg-white border border-[#F0D9DF] focus:outline-none focus:ring-2 focus:ring-[#C2577A]/20 focus:border-[#C2577A] transition-all text-left" dir="ltr">
                    <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 px-4 flex items-center justify-center text-[#8A6E78] hover:text-[#C2577A] transition-colors">
                        <iconify-icon icon="lucide:eye" width="20"></iconify-icon>
                    </button>
                </div>
                @error('password') <span class="text-xs text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- تذكرني ونسيت كلمة المرور -->
            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center gap-2 cursor-pointer group">
                    <div class="relative flex items-center justify-center">
                        <input type="checkbox" name="remember" class="peer sr-only">
                        <div class="w-5 h-5 rounded-md border border-[#F0D9DF] bg-white peer-checked:bg-[#C2577A] peer-checked:border-[#C2577A] transition-all"></div>
                        <iconify-icon icon="lucide:check" class="absolute text-white scale-0 peer-checked:scale-100 transition-transform" width="14"></iconify-icon>
                    </div>
                    <span class="text-[13px] text-[#8A6E78] group-hover:text-[#C2577A] transition-colors">تذكرني</span>
                </label>
                <a href="#" class="text-[13px] text-[#8A6E78] hover:text-[#C2577A] hover:underline underline-offset-4 transition-all">نسيت كلمة المرور؟</a>
            </div>

            <!-- زر الدخول -->
            <button type="submit" class="w-full h-[52px] mt-2 bg-[#C2577A] hover:bg-[#A9456A] text-white rounded-[14px] font-bold text-lg shadow-lg shadow-[#C2577A]/20 transition-all transform active:scale-[0.98]">
                تسجيل الدخول
            </button>
        </form>

        <div class="mt-6 pt-6 border-t border-dashed border-[#F0D9DF] text-center">
            <p class="text-xs text-[#8A6E78] leading-relaxed">ليس لديكِ حساب؟ يمكنكِ إنشاء حساب بسهولة بعد إتمام طلبكِ الأول من المتجر.</p>
        </div>
    </div>

    <!-- زر العودة -->
    <a href="{{ route('home') }}" class="mt-8 flex items-center gap-1.5 text-[#8A6E78] hover:text-[#C2577A] hover:underline underline-offset-4 transition-all group">
        <span class="text-sm font-medium">العودة للمتجر</span>
        <iconify-icon icon="lucide:chevron-left" width="18" class="transition-transform group-hover:-translate-x-1"></iconify-icon>
    </a>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('toggle-password').addEventListener('click', function() {
        const input = document.getElementById('password');
        const icon = this.querySelector('iconify-icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.setAttribute('icon', 'lucide:eye-off');
        } else {
            input.type = 'password';
            icon.setAttribute('icon', 'lucide:eye');
        }
    });
</script>
@endpush