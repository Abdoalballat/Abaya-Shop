@php
    $selColors = array_map('intval', (array) old('colors', $selectedColors ?? []));
    $selSizes  = array_map('intval', (array) old('sizes',  $selectedSizes  ?? []));
    $stockVal  = old('stock', $stock ?? '');
@endphp

<div class="bg-[#FFF7F5] p-5 rounded-[20px] border border-[#F0D9DF] space-y-6" data-variants-picker>
    <h4 class="text-sm font-bold text-[#C2577A] flex items-center gap-2">
        <iconify-icon icon="lucide:layers"></iconify-icon> الألوان والمقاسات والكمية
    </h4>

    {{-- الألوان --}}
    <div>
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold text-[#8A6E78]">الألوان المتاحة</span>
            <button type="button" data-all="colors" class="text-xs font-bold text-[#C2577A] min-h-[32px]">تحديد الكل</button>
        </div>
        <div class="flex flex-wrap gap-2" data-group="colors">
            @foreach($colors as $color)
                <label class="cursor-pointer">
                    <input type="checkbox" name="colors[]" value="{{ $color->id }}" class="peer sr-only"
                           {{ in_array($color->id, $selColors) ? 'checked' : '' }}>
                    <span class="inline-flex items-center gap-2 min-h-[40px] px-4 rounded-full border border-[#F0D9DF] bg-white text-sm font-medium text-[#3B2530] transition
                                 peer-checked:bg-[#C2577A] peer-checked:border-[#C2577A] peer-checked:text-white peer-checked:font-bold
                                 peer-focus-visible:ring-2 peer-focus-visible:ring-[#C2577A]/40">
                        <span class="w-3.5 h-3.5 rounded-full border border-black/10" style="background-color: {{ $color->hex_code ?: '#eee' }}"></span>
                        {{ $color->name }}
                    </span>
                </label>
            @endforeach
        </div>
    </div>

    {{-- المقاسات --}}
    <div>
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold text-[#8A6E78]">المقاسات المتاحة</span>
            <button type="button" data-all="sizes" class="text-xs font-bold text-[#C2577A] min-h-[32px]">تحديد الكل</button>
        </div>
        <div class="flex flex-wrap gap-2" data-group="sizes">
            @foreach($sizes as $size)
                <label class="cursor-pointer">
                    <input type="checkbox" name="sizes[]" value="{{ $size->id }}" class="peer sr-only"
                           {{ in_array($size->id, $selSizes) ? 'checked' : '' }}>
                    <span class="inline-flex items-center min-h-[40px] px-5 rounded-full border border-[#F0D9DF] bg-white text-sm font-medium text-[#3B2530] transition
                                 peer-checked:bg-[#C2577A] peer-checked:border-[#C2577A] peer-checked:text-white peer-checked:font-bold
                                 peer-focus-visible:ring-2 peer-focus-visible:ring-[#C2577A]/40">
                        {{ $size->name }}
                    </span>
                </label>
            @endforeach
        </div>
    </div>

    {{-- الكمية الواحدة --}}
    <div>
        <label class="text-xs font-bold text-[#8A6E78] mb-2 block">الكمية المتاحة من الموديل</label>
        <input type="number" name="stock" required min="0" inputmode="numeric" value="{{ $stockVal }}" placeholder="مثال: 50"
               class="w-full px-4 py-3 rounded-[14px] border border-[#F0D9DF] bg-white focus:border-[#C2577A] focus:ring-4 focus:ring-[#C2577A]/10 outline-none">
    </div>

    <p data-summary class="text-xs text-[#8A6E78]"></p>
</div>

<script>
(() => {
    const root = document.querySelector('[data-variants-picker]');
    if (!root) return;

    const count = g => root.querySelectorAll(`[data-group="${g}"] input:checked`).length;
    const summary = root.querySelector('[data-summary]');

    const update = () => {
        const c = count('colors'), s = count('sizes');
        summary.textContent = c && s
            ? `سيتم إنشاء ${c * s} توليفة (${c} لون × ${s} مقاس)`
            : 'اختاري لوناً ومقاساً على الأقل';
    };

    root.addEventListener('change', update);

    root.querySelectorAll('[data-all]').forEach(btn => btn.addEventListener('click', () => {
        const boxes = [...root.querySelectorAll(`[data-group="${btn.dataset.all}"] input`)];
        const allOn = boxes.every(b => b.checked);
        boxes.forEach(b => b.checked = !allOn);
        btn.textContent = allOn ? 'تحديد الكل' : 'إلغاء الكل';
        update();
    }));

    // منع الإرسال لو مفيش لون أو مقاس
    root.closest('form')?.addEventListener('submit', e => {
        if (!count('colors') || !count('sizes')) {
            e.preventDefault();
            summary.textContent = 'لازم تختاري لوناً ومقاساً على الأقل';
            summary.classList.add('text-red-500');
            root.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

    update();
})();
</script>