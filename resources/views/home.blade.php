@extends('layouts.app')

@section('title', 'عبايات المصطفى - الرئيسية')

@php $hideFooter = true; @endphp

@push('styles')
<style>
    .font-heading { font-family: 'Aref Ruqaa', serif; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('content')
<div class="flex flex-col min-h-[calc(100vh-80px)] justify-between">
    <div>
        {{-- Hero --}}
        <section class="relative overflow-hidden floral-pattern bg-gradient-to-b from-[#FDEEF0] to-[#FFF7F5] rounded-[20px] border border-[#F0D9DF]/50 mb-5 px-5 py-8 md:py-14 text-center">
            <div class="relative z-10 space-y-3">
                <h2 class="text-2xl md:text-4xl font-bold font-heading text-[#3B2530] leading-snug">
                    اختاري من أجمل تشكيلة<br>عبايات الخليج
                </h2>
                <p class="text-[#8A6E78] text-sm max-w-[280px] md:max-w-md mx-auto">
                    تصاميم حصرية بخامات عالية الجودة تمنحكِ الأناقة والفخامة التي تستحقينها
                </p>
                <a href="#products-section"
                   class="mt-2 min-h-[48px] inline-flex items-center justify-center px-8 bg-[#C2577A] active:bg-[#A9456A] md:hover:bg-[#A9456A] text-white rounded-[14px] font-bold shadow-lg shadow-[#C2577A]/20 transition-colors">
                    تصفحي الموديلات
                </a>
            </div>
            <iconify-icon icon="lucide:flower-2" width="110" class="absolute -bottom-8 -right-8 opacity-15 rotate-12 text-[#C2577A]"></iconify-icon>
        </section>

        {{-- الأقسام --}}
        <nav id="chips" aria-label="الأقسام"
             class="sticky top-[64px] z-30 -mx-5 px-5 py-3 bg-[#FFF7F5]/95 backdrop-blur border-b border-[#F0D9DF]/60 overflow-x-auto no-scrollbar">
            <div class="flex gap-2.5 w-max">
                @php
                    $chipBase = 'shrink-0 inline-flex items-center h-11 px-5 rounded-full text-sm whitespace-nowrap transition-colors';
                    $chipOn   = 'bg-[#C2577A] text-white font-bold shadow-md shadow-[#C2577A]/20';
                    $chipOff  = 'bg-white text-[#C2577A] font-medium border border-[#F0D9DF]';
                @endphp

                <a href="{{ route('home') }}#products-section"
                   class="{{ $chipBase }} {{ !$activeDepartment ? $chipOn : $chipOff }}"
                   @if(!$activeDepartment) aria-current="page" @endif>
                    الكل
                </a>

                @foreach($departments as $department)
                    @php $isActive = $activeDepartment && $activeDepartment->id === $department->id; @endphp
                    <a href="{{ route('home', ['department' => $department->id]) }}#products-section"
                       class="{{ $chipBase }} {{ $isActive ? $chipOn : $chipOff }}"
                       @if($isActive) aria-current="page" @endif>
                        {{ $department->name }}
                    </a>
                @endforeach
            </div>
        </nav>

        {{-- المنتجات --}}
        <section id="products-section" class="scroll-mt-[130px] mt-5 mb-10">
            <div class="flex items-center justify-between mb-4 px-1">
                <h3 class="font-heading text-xl text-[#3B2530]">
                    {{ $activeDepartment->name ?? 'كل الموديلات' }}
                </h3>
                <span class="text-xs text-[#8A6E78]">{{ $products->total() }} موديل</span>
            </div>

            <div class="grid grid-cols-2 gap-3 md:grid-cols-3 md:gap-5 lg:grid-cols-4">
                @forelse($products as $product)
                    @php
                        $colors = $product->variances->unique('color_id')->filter(fn ($v) => $v->color);
                        $img = $product->primaryImage?->url();
                    @endphp

                    <a href="{{ route('product.show', $product->id) }}"
                       class="group block bg-white rounded-[18px] p-2 border border-[#F0D9DF] custom-shadow transition active:scale-[0.98] md:hover:border-[#C2577A]">

                        <div class="relative w-full aspect-[3/4] overflow-hidden rounded-[14px] bg-[#FDEEF0]">
                            @if($img)
                                <img src="{{ $img }}"
                                     alt="{{ $product->name }} كود {{ $product->code }}"
                                     width="600" height="800"
                                     loading="{{ $loop->index < 4 ? 'eager' : 'lazy' }}" decoding="async"
                                     onerror="this.remove()"
                                     class="absolute inset-0 w-full h-full object-cover object-top">
                            @else
                                <div class="absolute inset-0 flex items-center justify-center text-[#C2577A]/40">
                                    <iconify-icon icon="lucide:image" width="40"></iconify-icon>
                                </div>
                            @endif
                        </div>

                        <div class="mt-2.5 px-1 pb-1 space-y-1.5">
                            @if($colors->isNotEmpty())
                                <div class="flex items-center gap-1.5">
                                    @foreach($colors->take(4) as $variant)
                                        <span class="w-3 h-3 rounded-full border border-black/10"
                                              style="background-color: {{ $variant->color->hex_code }}"
                                              title="{{ $variant->color->name }}"></span>
                                    @endforeach
                                    @if($colors->count() > 4)
                                        <span class="text-[10px] text-[#8A6E78]">+{{ $colors->count() - 4 }}</span>
                                    @endif
                                </div>
                            @endif

                            <h3 class="text-[13px] md:text-sm font-bold text-[#3B2530] leading-snug line-clamp-2 min-h-[2.5rem]">
                                {{ $product->name }}
                            </h3>
                            <p class="text-base font-bold text-[#C9A36B]">{{ number_format($product->price, 0) }} ج.م</p>
                        </div>
                    </a>

                @empty
                    <div class="col-span-2 md:col-span-3 lg:col-span-4 py-12 px-6 flex flex-col items-center text-center space-y-4">
                        <div class="w-20 h-20 rounded-full bg-[#EDE7F8] flex items-center justify-center text-[#C2577A]">
                            <iconify-icon icon="lucide:shopping-bag" width="36"></iconify-icon>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-lg font-bold text-[#3B2530]">لا توجد موديلات في هذا القسم حالياً</h4>
                            <p class="text-xs text-[#8A6E78]">جرّبي قسم آخر أو ارجعي لاحقاً لمشاهدة التشكيلة الجديدة</p>
                        </div>
                        @if($activeDepartment)
                            <a href="{{ route('home') }}#products-section"
                               class="min-h-[44px] inline-flex items-center px-6 rounded-[14px] border border-[#C2577A] text-[#C2577A] font-bold text-sm">
                                عرض كل الموديلات
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>

            @if($products->hasPages())
                <div class="mt-8">{{ $products->links() }}</div>
            @endif
        </section>
    </div>

    {{-- الفوتر المعدل (تمت إزالة mb-24 وتثبيته في أسفل الصفحة) --}}
    <footer class="py-8 px-5 bg-[#C2577A] text-white text-center space-y-3 rounded-t-[20px] mt-10">
        <h4 class="font-heading text-xl">عبايات المصطفى</h4>
        <p class="text-xs opacity-80 leading-relaxed">
            جميع الحقوق محفوظة © {{ date('Y') }}<br>أناقة الخليج بلمسة مصرية
        </p>
    </footer>
</div>

{{-- زر الواتساب الثابت --}}
<a href="https://wa.me/201021002218?text={{ urlencode('أريد الاستفسار عن موديل') }}"
   target="_blank" rel="noopener" aria-label="واتساب للاستفسار"
   style="bottom: calc(1.25rem + env(safe-area-inset-bottom));"
   class="fixed left-5 z-[60] w-14 h-14 bg-[#25D366] text-white rounded-full flex items-center justify-center shadow-xl shadow-[#25D366]/30 active:scale-95 transition-transform">
    <iconify-icon icon="logos:whatsapp-icon" width="28"></iconify-icon>
</a>
@endsection

@push('scripts')
<script>
    document.querySelector('#chips [aria-current="page"]')
        ?.scrollIntoView({ inline: 'center', block: 'nearest' });
</script>
@endpush