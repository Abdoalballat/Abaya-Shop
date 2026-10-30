@extends('layouts.app')

@section('title', 'تم تأكيد الطلب - عبايات المصطفى')

{{-- لا نريد عرض الهيدر والفوتر الافتراضيين في صفحة النجاح لتكون الشاشة مركزة بالكامل على رسالة النجاح وإنشاء الحساب --}}
@php
    $hideNavbar = true;
    $hideFooter = true;
@endphp

@push('styles')
<style>
    .font-heading { font-family: 'Aref Ruqaa', serif; }
    .success-pulse { animation: pulse 2s infinite; }
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(194, 87, 122, 0.4); }
        70% { box-shadow: 0 0 0 20px rgba(194, 87, 122, 0); }
        100% { box-shadow: 0 0 0 0 rgba(194, 87, 122, 0); }
    }
</style>
@endpush

@section('content')
<div class="flex flex-col items-center text-center -mx-5 -mt-6">
    
    <!-- Header الخاص بالصفحة -->
    <header class="w-full pt-8 pb-6 flex items-center justify-center">
        <h1 class="text-2xl font-bold font-heading text-[#C2577A] tracking-tight">عبايات المصطفى</h1>
    </header>

    <!-- أيقونة النجاح -->
    <div class="w-24 h-24 rounded-full bg-[#EDE7F8] flex items-center justify-center mb-6 success-pulse">
        <iconify-icon icon="lucide:check-circle" class="text-[#C2577A]" width="54"></iconify-icon>
    </div>

    <!-- رسالة النجاح -->
    <div class="space-y-3 mb-8 px-4">
        <h2 class="text-2xl font-bold font-heading text-[#3B2530]">تم تأكيد طلبك بنجاح!</h2>
        <p class="text-[#8A6E78] text-sm leading-relaxed">شكراً لثقتكِ بنا، سنقوم بمراجعة إيصال الدفع وتجهيز طلبكِ في أقرب وقت ممكن.</p>
    </div>

    <!-- رقم الطلب -->
    <div class="w-full max-w-[320px] bg-[#FDEEF0] border border-[#F0D9DF] rounded-[20px] p-5 mb-10 custom-shadow mx-auto">
        <span class="text-xs font-bold text-[#8A6E78] block mb-2">رقم الطلب الخاص بكِ</span>
        <div class="flex items-center justify-center gap-2">
            <code class="text-lg font-bold text-[#C9A36B] tracking-wider" id="order-serial">{{ session('order_serial', 'ORD-XXXXXX') }}</code>
            <button onclick="copyToClipboard('order-serial')" class="text-[#C2577A] flex items-center justify-center hover:text-[#A9456A] transition-colors">
                <iconify-icon icon="lucide:copy" width="18"></iconify-icon>
            </button>
        </div>
    </div>

    <!-- فورم إنشاء الحساب (تظهر فقط إذا كان المستخدم غير مسجل دخول ولديه رقم طلب) -->
    @if(!auth()->check() && session('order_id'))
    <section class="w-full max-w-[340px] bg-[#EDE7F8] border border-[#9B87C9]/30 rounded-[24px] p-6 space-y-5 custom-shadow mx-auto mb-8 text-right">
        <div>
            <h3 class="text-lg font-bold flex items-center gap-2 text-[#3B2530]">
                <iconify-icon icon="lucide:user-plus" class="text-[#9B87C9]"></iconify-icon>
                <span>أنشئي حسابكِ لمتابعة طلبكِ</span>
            </h3>
            <p class="text-xs text-[#8A6E78] mt-2 leading-relaxed">رقم هاتفكِ محفوظ لدينا بالفعل، كل ما عليكِ هو إنشاء كلمة مرور لتتمكني من متابعة حالة الشحن لاحقاً.</p>
        </div>

        <form action="{{ route('post.checkout.register') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="order_id" value="{{ session('order_id') }}">

            <div class="space-y-1.5">
                <label class="text-[11px] font-bold text-[#8A6E78] mr-2 uppercase">كلمة المرور</label>
                <div class="relative">
                    <input type="password" name="password" required placeholder="••••••••" class="w-full h-[52px] px-4 rounded-[14px] bg-white border border-[#F0D9DF] focus:outline-none focus:ring-2 focus:ring-[#9B87C9]/20 transition-all text-left" dir="ltr">
                </div>
                @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="space-y-1.5">
                <label class="text-[11px] font-bold text-[#8A6E78] mr-2 uppercase">تأكيد كلمة المرور</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" required placeholder="••••••••" class="w-full h-[52px] px-4 rounded-[14px] bg-white border border-[#F0D9DF] focus:outline-none focus:ring-2 focus:ring-[#9B87C9]/20 transition-all text-left" dir="ltr">
                </div>
            </div>

            <button type="submit" class="w-full h-[52px] bg-[#C2577A] hover:bg-[#A9456A] text-white rounded-[14px] font-bold text-base shadow-lg shadow-[#C2577A]/20 transition-all mt-4">
                حفظ الحساب
            </button>
        </form>

        <div class="pt-2 text-center">
            <a href="{{ route('home') }}" class="text-sm text-[#8A6E78] hover:text-[#C2577A] transition-colors underline underline-offset-4">
                لا، شكراً.. سأتابع لاحقاً
            </a>
        </div>
    </section>
    @else
        <!-- إذا كان المستخدم مسجلاً مسبقاً، نظهر له زر العودة للرئيسية بدلاً من فورم التسجيل -->
        <a href="{{ route('home') }}" class="w-full max-w-[320px] h-[52px] bg-[#C2577A] hover:bg-[#A9456A] text-white rounded-[14px] font-bold text-base shadow-lg shadow-[#C2577A]/20 transition-all flex items-center justify-center mx-auto mb-8">
            العودة للصفحة الرئيسية
        </a>
    @endif

    <!-- روابط سريعة (دعم وسياسة) -->
    <div class="py-4 flex items-center justify-center gap-4 text-[#8A6E78]">
        <a href="#" class="text-xs flex items-center gap-1 hover:text-[#C2577A]">
            <iconify-icon icon="lucide:headphones"></iconify-icon> الدعم الفني
        </a>
        <span class="w-1 h-1 bg-[#F0D9DF] rounded-full"></span>
        <a href="#" class="text-xs flex items-center gap-1 hover:text-[#C2577A]">
            <iconify-icon icon="lucide:file-text"></iconify-icon> سياسة الإرجاع
        </a>
    </div>

    <footer class="pb-8 pt-4 flex flex-col items-center justify-center">
        <p class="text-[10px] text-[#8A6E78] opacity-60">جميع الحقوق محفوظة © عبايات المصطفى {{ date('Y') }}</p>
    </footer>
</div>
@endsection

@push('scripts')
<script>
    // دالة لنسخ رقم الطلب
    function copyToClipboard(elementId) {
        var copyText = document.getElementById(elementId).innerText;
        navigator.clipboard.writeText(copyText).then(function() {
            alert('تم نسخ رقم الطلب: ' + copyText);
        }, function(err) {
            console.error('Could not copy text: ', err);
        });
    }

    // بمجرد وصول المستخدم لهذه الصفحة (النجاح)، نقوم بتنظيف سلة المشتريات من الـ LocalStorage
    document.addEventListener('DOMContentLoaded', function() {
        localStorage.removeItem('cart');
    });
</script>
@endpush