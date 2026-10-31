@extends('layouts.app')

@section('title', $product->name . ' - عبايات المصطفى')

@php
    $hideFooter = true;

    $colors = $product->variances->unique('color_id')->map->color->filter()->values();
    $sizes  = $product->variances->unique('size_id')->map->size->filter()->values();

    $mappedVariances = $product->variances->map(fn ($v) => [
        'id'       => $v->id,
        'color_id' => $v->color_id,
        'size_id'  => $v->size_id,
        'stock'    => (int) $v->stock,
    ])->values()->all();

    // المعرض: الرئيسية الأول، وكل صورة مربوطة بلونها
    $gallery = $product->images
        ->sortBy([['is_main', 'desc'], ['id', 'asc']])
        ->values()
        ->map(fn ($i) => [
            'color_id' => $i->color_id,
            'full'     => $i->url('w-900,q-85,f-auto'),
            'thumb'    => $i->url('w-160,q-70,f-auto'),
            'cart'     => $i->url('w-200,q-75,f-auto'),
        ])->all();

    $productData = [
        'id'    => $product->id,
        'name'  => $product->name,
        'code'  => (string) ($product->code ?? $product->id),
        'price' => (float) $product->price,
    ];
@endphp

@push('styles')
<style>
    .font-heading { font-family: 'Aref Ruqaa', serif; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    .color-option.selected { box-shadow: 0 0 0 2px #fff, 0 0 0 4px #C2577A; transform: scale(1.08); }
    .color-option:disabled { opacity: .35; cursor: not-allowed; position: relative; }
    .color-option:disabled::after { content: ""; position: absolute; inset: 50% -2px auto; border-top: 2px solid #8A6E78; transform: rotate(-45deg); }

    .size-option.selected { background: #C2577A; color: #fff; border-color: #C2577A; }
    .size-option:disabled { opacity: .4; text-decoration: line-through; cursor: not-allowed; }

    .thumb.selected { border-color: #C2577A; box-shadow: 0 0 0 2px rgba(194,87,122,.25); }
    .shake { animation: shake .35s; }
    @keyframes shake { 25%{transform:translateX(-4px)} 75%{transform:translateX(4px)} }
</style>
@endpush

@section('header_left')
    <a href="{{ route('home') }}" aria-label="رجوع"
       class="w-10 h-10 flex items-center justify-center rounded-full bg-[#FDEEF0] text-[#C2577A] active:bg-[#F0D9DF]">
        <iconify-icon icon="lucide:chevron-right" width="24"></iconify-icon>
    </a>
@endsection

@section('content')
<div class="flex flex-col gap-5 pb-36 md:max-w-5xl md:mx-auto md:grid md:grid-cols-2 md:gap-10 md:pb-32">

    {{-- المعرض --}}
    <div class="space-y-3 md:sticky md:top-20 md:self-start">
        <div class="relative w-full aspect-[3/4] overflow-hidden rounded-[20px] bg-[#FDEEF0] border border-[#F0D9DF]/60 shadow-sm">
            @if(count($gallery))
                <img id="main-image" src="{{ $gallery[0]['full'] }}"
                     alt="{{ $product->name }} - كود {{ $productData['code'] }}"
                     width="900" height="1200" fetchpriority="high" decoding="async"
                     class="absolute inset-0 w-full h-full object-cover object-top transition-opacity duration-200">
            @else
                <div class="absolute inset-0 flex items-center justify-center text-[#C2577A]/40">
                    <iconify-icon icon="lucide:image" width="56"></iconify-icon>
                </div>
            @endif

            <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-xs px-3 py-1.5 rounded-full text-[#C2577A] font-bold border border-[#F0D9DF] shadow-sm">
                كود {{ $productData['code'] }}
            </span>
        </div>

        {{-- شريط الصور المصغرة --}}
        @if(count($gallery) > 1)
            <div class="flex gap-2.5 overflow-x-auto no-scrollbar -mx-5 px-5 pb-1">
                @foreach($gallery as $i => $img)
                    <button type="button" data-index="{{ $i }}" onclick="selectImage({{ $i }})"
                            aria-label="صورة {{ $i + 1 }}"
                            class="thumb shrink-0 w-[68px] aspect-[3/4] rounded-xl overflow-hidden border-2 border-[#F0D9DF] bg-[#FDEEF0] transition {{ $i === 0 ? 'selected' : '' }}">
                        <img src="{{ $img['thumb'] }}" alt="" loading="lazy" decoding="async"
                             class="w-full h-full object-cover object-top">
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    {{-- التفاصيل --}}
    <div class="flex flex-col gap-5">

        <div class="text-center md:text-right space-y-1.5">
            <h1 class="text-2xl font-bold font-heading text-[#3B2530] leading-snug">{{ $product->name }}</h1>
            <p class="text-xl font-bold text-[#C9A36B]">{{ number_format($product->price, 0) }} ج.م</p>
        </div>

        <hr class="border-[#F0D9DF]/60">

        {{-- اللون --}}
        @if($colors->count() > 0)
        <div id="color-section" class="space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-sm text-[#3B2530]">اختاري اللون</h3>
                <span id="selected-color-name" class="text-xs font-bold text-[#C2577A]"></span>
            </div>
            <div class="flex flex-wrap gap-3.5 pt-1">
                @foreach ($colors as $color)
                    <button type="button"
                            id="color-btn-{{ $color->id }}"
                            data-color="{{ $color->id }}"
                            onclick='selectColor({{ $color->id }}, @json($color->name))'
                            aria-label="{{ $color->name }}" title="{{ $color->name }}"
                            class="color-option shrink-0 w-11 h-11 rounded-full border border-black/10 shadow-sm transition-all"
                            style="background-color: {{ $color->hex_code }}"></button>
                @endforeach
            </div>
        </div>
        @endif

        {{-- المقاس --}}
        @if($sizes->count() > 0)
        <div id="size-section" class="space-y-3">
            <h3 class="font-bold text-sm text-[#3B2530]">المقاس</h3>
            <div class="flex flex-wrap gap-2">
                @foreach ($sizes as $size)
                    <button type="button"
                            id="size-btn-{{ $size->id }}"
                            data-size="{{ $size->id }}"
                            onclick='selectSize({{ $size->id }}, @json($size->name))'
                            class="size-option min-w-[3.5rem] min-h-[44px] px-4 rounded-xl bg-white border border-[#F0D9DF] text-[#8A6E78] font-bold text-sm transition-all active:scale-95">
                        {{ $size->name }}
                    </button>
                @endforeach
            </div>
        </div>
        @endif

        {{-- الكمية --}}
        <div class="flex items-center justify-between p-4 bg-white border border-[#F0D9DF] rounded-2xl shadow-sm">
            <div>
                <h3 class="font-bold text-sm text-[#3B2530]">الكمية المطلوبة</h3>
                <p id="stock-hint" class="text-[11px] text-[#C2577A] mt-0.5 hidden"></p>
            </div>
            <div class="flex items-center bg-[#FDEEF0] rounded-full p-1 gap-3">
                <button type="button" onclick="updateQty(1)" aria-label="زيادة"
                        class="w-10 h-10 flex items-center justify-center bg-[#C2577A] rounded-full text-white font-bold text-lg active:scale-90 transition-transform">+</button>
                <span id="qty-display" class="text-base font-bold text-[#3B2530] w-5 text-center">1</span>
                <button type="button" onclick="updateQty(-1)" aria-label="نقصان"
                        class="w-10 h-10 flex items-center justify-center bg-white rounded-full text-[#C2577A] font-bold text-lg active:scale-90 transition-transform">−</button>
            </div>
        </div>

        {{-- ملاحظات --}}
        <div class="space-y-2">
            <label for="order-notes" class="font-bold text-sm text-[#3B2530]">ملاحظات إضافية (اختياري)</label>
            <textarea id="order-notes" maxlength="300" placeholder="أريد الطول أطول بـ 5 سم..."
                      class="w-full p-4 rounded-2xl bg-white border border-[#F0D9DF] focus:outline-none focus:ring-2 focus:ring-[#C2577A]/25 h-24 resize-none text-sm shadow-sm"></textarea>
        </div>

        <p class="text-[11px] text-[#8A6E78] text-center md:text-right flex items-center gap-1.5 justify-center md:justify-start">
            <iconify-icon icon="lucide:shield-check" width="14" class="text-[#C2577A]"></iconify-icon>
            الدفع مسبقاً عبر فودافون كاش أو إنستاباي، والتوصيل لكل المحافظات
        </p>
    </div>
</div>

{{-- Toast --}}
<div id="toast" role="status" aria-live="polite"
     class="fixed left-1/2 -translate-x-1/2 z-[80] px-5 py-3 rounded-full bg-[#3B2530] text-white text-sm font-medium shadow-xl opacity-0 pointer-events-none transition-opacity duration-300"
     style="bottom: calc(6.5rem + env(safe-area-inset-bottom));"></div>

{{-- الفوتر الثابت --}}
<footer class="fixed bottom-0 inset-x-0 z-[60] bg-white/95 backdrop-blur border-t border-[#F0D9DF] px-4 pt-3 shadow-[0_-10px_30px_-10px_rgba(194,87,122,0.15)]"
        style="padding-bottom: calc(1rem + env(safe-area-inset-bottom));">
    <div class="max-w-md mx-auto">
        <button type="button" id="add-btn" onclick="addToCart()"
                class="w-full min-h-[52px] bg-[#C2577A] active:bg-[#A9456A] md:hover:bg-[#A9456A] active:scale-[0.98] text-white rounded-[14px] font-bold text-base transition-all flex items-center justify-center gap-3 shadow-lg shadow-[#C2577A]/30">
            <iconify-icon icon="lucide:shopping-bag" width="22"></iconify-icon>
            <span>أضيفي للسلة</span>
            <span class="opacity-90 border-r border-white/30 pr-3 mr-1 text-sm">
                <span id="btn-total-price">{{ number_format($product->price, 0) }}</span> ج.م
            </span>
        </button>
    </div>
</footer>
@endsection

@push('scripts')
<script>
    const productData = @json($productData);
    const variances   = @json($mappedVariances);
    const gallery     = @json($gallery);
    const hasColors   = {{ $colors->count() > 0 ? 'true' : 'false' }};
    const hasSizes    = {{ $sizes->count() > 0 ? 'true' : 'false' }};
    const cartUrl     = @json(route('cart'));

    let selectedColor = { id: null, name: null };
    let selectedSize  = { id: null, name: null };
    let quantity = 1;
    let currentImage = 0;

    /* ---------- أدوات ---------- */
    function toast(msg) {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.classList.remove('opacity-0');
        clearTimeout(t._h);
        t._h = setTimeout(() => t.classList.add('opacity-0'), 2500);
    }

    function flag(sectionId) {
        const el = document.getElementById(sectionId);
        if (!el) return;
        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
        el.classList.remove('shake'); void el.offsetWidth; el.classList.add('shake');
    }

    function currentVariance() {
        return variances.find(v =>
            (!hasColors || v.color_id == selectedColor.id) &&
            (!hasSizes  || v.size_id  == selectedSize.id)
        ) || null;
    }

    function maxQty() {
        const v = currentVariance();
        return v ? Math.max(v.stock, 0) : 99;
    }

    /* ---------- الصور ---------- */
    function selectImage(i) {
        if (!gallery[i]) return;
        currentImage = i;
        const main = document.getElementById('main-image');
        if (main) {
            main.style.opacity = 0;
            setTimeout(() => { main.src = gallery[i].full; main.style.opacity = 1; }, 120);
        }
        document.querySelectorAll('.thumb').forEach(el =>
            el.classList.toggle('selected', Number(el.dataset.index) === i));

        // لو الصورة مربوطة بلون، اختار اللون تلقائياً
        const cid = gallery[i].color_id;
        if (cid && hasColors && selectedColor.id != cid) {
            const btn = document.getElementById('color-btn-' + cid);
            if (btn && !btn.disabled) btn.click();
        }
    }

    function showColorImage(colorId) {
        const idx = gallery.findIndex(g => g.color_id == colorId);
        if (idx > -1 && idx !== currentImage) selectImage(idx);
    }

    /* ---------- اللون والمقاس ---------- */
    function selectColor(id, name) {
        selectedColor = { id, name };
        document.querySelectorAll('.color-option').forEach(el => el.classList.remove('selected'));
        document.getElementById('color-btn-' + id).classList.add('selected');
        document.getElementById('selected-color-name').textContent = '( ' + name + ' )';
        showColorImage(id);
        refreshSizes();
        refreshStock();
    }

    function selectSize(id, name) {
        selectedSize = { id, name };
        document.querySelectorAll('.size-option').forEach(el => el.classList.remove('selected'));
        document.getElementById('size-btn-' + id).classList.add('selected');
        refreshStock();
    }

    // المقاسات اللي مش متاحة للون المختار تتعطل
    function refreshSizes() {
        document.querySelectorAll('.size-option').forEach(btn => {
            const sid = btn.dataset.size;
            const ok = variances.some(v =>
                v.size_id == sid && v.stock > 0 &&
                (!hasColors || !selectedColor.id || v.color_id == selectedColor.id));
            btn.disabled = !ok;
            if (!ok && selectedSize.id == sid) {
                selectedSize = { id: null, name: null };
                btn.classList.remove('selected');
            }
        });
    }

    // الألوان اللي كلها نفدت تتعطل من الأول
    function initColors() {
        document.querySelectorAll('.color-option').forEach(btn => {
            const cid = btn.dataset.color;
            btn.disabled = !variances.some(v => v.color_id == cid && v.stock > 0);
        });
    }

    /* ---------- الكمية ---------- */
    function refreshStock() {
        const hint = document.getElementById('stock-hint');
        const v = currentVariance();
        if (v && v.stock > 0 && v.stock <= 3) {
            hint.textContent = 'متبقي ' + v.stock + ' قطع فقط';
            hint.classList.remove('hidden');
        } else {
            hint.classList.add('hidden');
        }
        if (quantity > maxQty()) quantity = Math.max(maxQty(), 1);
        renderQty();
    }

    function renderQty() {
        document.getElementById('qty-display').textContent = quantity;
        document.getElementById('btn-total-price').textContent =
            (quantity * productData.price).toLocaleString('en-US');
    }

    function updateQty(change) {
        const next = quantity + change;
        if (next < 1) return;
        if (next > maxQty()) { toast('الكمية المتاحة ' + maxQty() + ' فقط'); return; }
        quantity = next;
        renderQty();
    }

    /* ---------- السلة ---------- */
    function addToCart() {
        if (hasColors && !selectedColor.id) { toast('من فضلك اختاري اللون أولاً'); flag('color-section'); return; }
        if (hasSizes  && !selectedSize.id)  { toast('من فضلك اختاري المقاس أولاً'); flag('size-section'); return; }

        let varianceId = null;
        if (variances.length > 0) {
            const v = currentVariance();
            if (!v)          { toast('هذا اللون مع هذا المقاس غير متوفر حالياً'); return; }
            if (v.stock <= 0){ toast('عفواً، هذه النسخة نفدت من المخزون'); return; }
            varianceId = v.id;
        }

        // صورة السلة: صورة اللون المختار، وإلا أول صورة
        const imgObj = gallery.find(g => g.color_id == selectedColor.id) || gallery[0];

        const item = {
            product_id: productData.id,
            product_variance_id: varianceId,
            name: productData.name,
            code: productData.code,
            price: productData.price,
            image_url: imgObj ? imgObj.cart : null,
            color: selectedColor.name || 'الأساسي',
            size: selectedSize.name || 'الأساسي',
            quantity: quantity,
            notes: document.getElementById('order-notes').value.trim()
        };

        let cart = [];
        try { cart = JSON.parse(localStorage.getItem('cart')) || []; } catch (e) { cart = []; }

        // نفس المنتج ونفس النسخة بس هي اللي تتجمع
        const i = cart.findIndex(c =>
            c.product_id === item.product_id && c.product_variance_id === item.product_variance_id);

        if (i > -1) {
            cart[i].quantity += quantity;
            if (item.notes) cart[i].notes = item.notes;
        } else {
            cart.push(item);
        }

        try { localStorage.setItem('cart', JSON.stringify(cart)); }
        catch (e) { toast('تعذر حفظ السلة، تأكدي من إعدادات المتصفح'); return; }

        window.dispatchEvent(new CustomEvent('cart:updated'));

        const btn = document.getElementById('add-btn');
        btn.disabled = true;
        btn.classList.add('opacity-80');
        window.location.href = cartUrl;
    }

    /* ---------- تشغيل ---------- */
    initColors();
    refreshSizes();
    renderQty();
</script>
@endpush