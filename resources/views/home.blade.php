@extends('layouts.app')

@section('title', 'عبايات المصطفى - الرئيسية')

{{-- إخفاء الفوتر الثابت (Sticky Footer) الخاص بصفحة الدفع لأن الرئيسية لها فوتر خاص بها --}}
@php
    $hideFooter = true;
@endphp

@push('styles')
<style>
    .font-heading { font-family: 'Aref Ruqaa', serif; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .aspect-3-4 { aspect-ratio: 3 / 4; }
</style>
@endpush

@section('content')
    <!-- شريط التنبيه العلوي -->
    <!-- <section class="bg-[#FDEEF0] border-b border-[#F0D9DF] py-3 px-5 flex items-center justify-center gap-2 -mx-5 -mt-6 mb-6">
        <iconify-icon icon="lucide:info" class="text-[#C2577A]" width="18"></iconify-icon>
        <p class="text-[13px] font-medium">تحويل آمن قبل التسليم عبر Vodafone Cash أو InstaPay</p>
    </section> -->

    <!-- قسم الـ Hero -->
    <section class="relative py-12 px-6 overflow-hidden flex flex-col items-center text-center floral-pattern bg-gradient-to-b from-[#FDEEF0] to-[#FFF7F5] rounded-[20px] mb-8 border border-[#F0D9DF]/50">
        <div class="relative z-10 space-y-4">
            <h2 class="text-3xl font-bold font-heading text-[#3B2530] leading-tight">
                اختاري من أجمل تشكيلة<br>عبايات الخليج
            </h2>
            <p class="text-[#8A6E78] text-sm max-w-[280px] mx-auto">
                تصاميم حصرية بخامات عالية الجودة تمنحكِ الأناقة والفخامة التي تستحقينها
            </p>
            <a href="#products-section" class="mt-4 min-h-[52px] inline-flex px-10 bg-[#C2577A] hover:bg-[#A9456A] text-white rounded-[14px] font-bold text-lg shadow-lg shadow-[#C2577A]/20 transition-all items-center justify-center gap-2 mx-auto">
                تصفحي الموديلات
            </a>
        </div>
        <div class="absolute -bottom-10 -right-10 opacity-20 transform rotate-12">
            <iconify-icon icon="lucide:flower-2" width="120" class="text-[#C2577A]"></iconify-icon>
        </div>
    </section>

    <!-- أقسام التصنيفات (Chips) -->
    <section class="mb-6 -mx-5">
        <div class="flex overflow-x-auto no-scrollbar gap-3 px-5 scroll-smooth">
            <a href="{{ route('home') }}" class="shrink-0 flex items-center h-11 px-6 rounded-full {{ !request()->has('department') ? 'bg-[#C2577A] text-white shadow-[#C2577A]/10' : 'bg-[#EDE7F8] text-[#C2577A] border border-[#F0D9DF]' }} font-bold text-sm whitespace-nowrap shadow-md transition-all">الكل</a> 
            
            @foreach($departments as $department)
                <a href="{{ route('home', ['department' => $department->id]) }}" class="shrink-0 flex items-center h-11 px-6 rounded-full {{ request('department') == $department->id ? 'bg-[#C2577A] text-white shadow-[#C2577A]/10 shadow-md' : 'bg-[#EDE7F8] text-[#C2577A] border border-[#F0D9DF]' }} font-medium text-sm whitespace-nowrap transition-all">{{ $department->name }}</a> 
            @endforeach
        </div>
    </section>

    <!-- شبكة المنتجات (Product Grid) -->
    <section id="products-section" class="mb-12">
        <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4">
            @forelse($products as $product)
                <!-- كارت المنتج -->
                <a href="#" class="group block bg-white rounded-[20px] p-2.5 border border-[#F0D9DF] custom-shadow transition-all hover:border-[#C2577A] active:scale-[0.98]">
                    <div class="aspect-3-4 relative w-full overflow-hidden rounded-[14px] bg-[#FDEEF0]">
                        @if($product->primaryImage)
                            <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        @else
                            <img src="https://placehold.co/300x400/FDEEF0/C2577A?text={{ urlencode($product->name) }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        @endif
                        <span class="absolute top-2 right-2 bg-white/90 text-[10px] px-2 py-1 rounded-full text-[#C2577A] font-bold border border-[#F0D9DF] shadow-sm">كود {{ $product->code }}</span>
                    </div>
                    <div class="mt-3 space-y-1.5 px-1 pb-1">
                        <!-- نقاط الألوان -->
                        <div class="flex gap-1 flex-wrap">
                            @foreach($product->variances->unique('color_id') as $variant)
                                @if($variant->color)
                                    <span class="w-2.5 h-2.5 rounded-full border border-black/5" style="background-color: {{ $variant->color->hex_code }}" title="{{ $variant->color->name }}"></span>
                                @endif
                            @endforeach
                        </div>
                        <h3 class="text-sm font-bold text-[#3B2530] truncate">{{ $product->name }}</h3>
                        <p class="text-base font-bold text-[#C9A36B]">{{ number_format($product->price, 0) }} ج.م</p>
                    </div>
                </a>
            @empty
                <!-- حالة فارغة (Empty State) تظهر إذا لم توجد منتجات -->
                <div class="col-span-2 md:col-span-3 lg:col-span-4 py-12 px-10 flex flex-col items-center text-center space-y-4 opacity-70">
                    <div class="w-20 h-20 rounded-full bg-[#EDE7F8] flex items-center justify-center text-[#C2577A]">
                        <iconify-icon icon="lucide:ghost" width="40"></iconify-icon>
                    </div>
                    <div class="space-y-1">
                        <h4 class="text-lg font-bold">لا توجد موديلات في هذا القسم حالياً</h4>
                        <p class="text-xs text-[#8A6E78]">حاولي اختيار قسم آخر أو العودة لاحقاً لمشاهدة التشكيلة الجديدة</p>
                    </div>
                </div>
            @endforelse
        </div>
    </section>

    <!-- الفوتر الخاص بالصفحة الرئيسية -->
    <footer class="py-8 px-5 bg-[#C2577A] text-white text-center space-y-4 rounded-[20px] mb-4">
        <h4 class="font-heading text-xl">عبايات المصطفى</h4>
        <p class="text-xs opacity-80 leading-relaxed">جميع الحقوق محفوظة © {{ date('Y') }}<br>أناقة الخليج بلمسة مصرية</p>
    </footer>

    <!-- زر الواتساب العائم -->
    <a href="https://wa.me/201023456789" target="_blank" class="fixed bottom-8 left-6 z-[60] w-14 h-14 bg-[#25D366] text-white rounded-full flex items-center justify-center shadow-xl shadow-[#25D366]/30 animate-bounce hover:scale-110 transition-transform">
        <iconify-icon icon="logos:whatsapp-icon" width="28"></iconify-icon>
    </a>
@endsection