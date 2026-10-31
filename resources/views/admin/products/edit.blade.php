<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل موديل - عبايات المصطفى</title>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Aref+Ruqaa:wght@400;700&family=Tajawal:wght@300;400;500;700&display=swap');
        body { font-family: 'Tajawal', sans-serif; background-color: #FFF7F5; color: #3B2530; margin: 0; }
        .font-heading { font-family: 'Aref Ruqaa', serif; }
        .custom-shadow { box-shadow: 0 10px 30px -10px rgba(194, 87, 122, 0.15); }
        ::-webkit-scrollbar { display: none; } * { scrollbar-width: none; }
    </style>
</head>
<body>

<header class="h-16 bg-white border-b border-[#F0D9DF] px-8 flex items-center justify-between sticky top-0 z-40">
    <h2 class="text-xl font-bold font-heading text-[#C2577A]">عبايات المصطفى — تعديل بيانات الموديل</h2>
    <a href="{{ route('admin.dashboard') }}#products-section" class="flex items-center gap-2 text-sm font-bold text-[#8A6E78] hover:text-[#C2577A] transition-colors">
        <iconify-icon icon="lucide:arrow-right" width="18"></iconify-icon>
        رجوع للوحة التحكم
    </a>
</header>

<main class="p-8 max-w-6xl mx-auto">
@php
    // كلاس موحد للمدخلات (نفس شكل صفحة الإضافة)
    $inputCls = 'w-full px-4 py-3 rounded-[14px] border border-[#F0D9DF] bg-[#FFF7F5] focus:bg-white focus:border-[#C2577A] focus:ring-4 focus:ring-[#C2577A]/10 outline-none transition-all';
    $smallCls = 'w-full bg-[#FFF7F5] border border-[#F0D9DF] text-[#3B2530] text-sm p-2.5 rounded-lg focus:border-[#C2577A] outline-none';

    // الصور: بيدعم العمود url (ImageKit) أو image_path (storage)
    $images = $product->images ?? collect();
    $imgUrl = fn($img) => $img->url ?: ($img->image_path ? asset('storage/' . $img->image_path) : '');
$mainImage = $images->firstWhere('is_main', true) ?? $images->first(); 
   $selectedMain = old('main_image_id', $mainImage->id ?? null);

    // الصفوف (اللون/المقاس/الكمية): من القيم القديمة لو فيه خطأ، وإلا من الداتابيز
    $rows = old('variance') ?: $product->variances->map(fn($v) => [
        'color_id' => $v->color_id,
        'size_id'  => $v->size_id,
        'stock'    => $v->stock,
    ])->values()->all();
    if (empty($rows)) {
        $rows = [['color_id' => '', 'size_id' => '', 'stock' => '']];
    }
    $nextIndex = max(array_map('intval', array_keys($rows))) + 1;

    // حالة "منشور": لو فيه old input نقرأ من الـ checkbox، وإلا من الداتابيز
    $isActive = !empty(old()) ? old('is_active') !== null : (bool) $product->is_active;
@endphp

{{-- رسائل الأخطاء --}}
@if($errors->any())
    <div class="bg-red-50 text-red-500 p-4 rounded-xl mb-6 border border-red-100">
        <ul class="list-disc list-inside text-sm font-bold">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<section class="bg-white rounded-[20px] custom-shadow border border-[#F0D9DF] p-8">
    <h3 class="text-lg font-bold font-heading mb-6 border-b border-[#F0D9DF] pb-4 text-[#3B2530]">
        تعديل: {{ $product->name }}
    </h3>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        @csrf
        @method('PUT')

        {{-- ===================== العمود الأول: البيانات ===================== --}}
        <div class="space-y-6">
            <div>
                <label class="text-xs font-bold text-[#8A6E78] mb-2 block">اسم الموديل</label>
                <input type="text" name="name" required value="{{ old('name', $product->name) }}" placeholder="مثال: عباية ملكي كريب" class="{{ $inputCls }}">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-[#8A6E78] mb-2 block">كود الموديل</label>
                    <input type="text" name="code" required value="{{ old('code', $product->code) }}" placeholder="مثال: 909" class="{{ $inputCls }}">
                </div>
                <div>
                    <label class="text-xs font-bold text-[#8A6E78] mb-2 block">السعر (ج.م)</label>
                    <input type="number" name="price" required min="0" step="any" value="{{ old('price', $product->price) }}" placeholder="950" class="{{ $inputCls }}">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-[#8A6E78] mb-2 block">التصنيف</label>
                    <select name="department_id" required class="{{ $inputCls }}">
                        <option value="" disabled>اختاري التصنيف...</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ (string) old('department_id', $product->department_id) === (string) $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-[#8A6E78] mb-2 block">نوع الخامة</label>
                    <input type="text" name="material_type" value="{{ old('material_type', $product->material_type) }}" placeholder="كريب كوري فاخر" class="{{ $inputCls }}">
                </div>
            </div>

            <div>
                <label class="text-xs font-bold text-[#8A6E78] mb-2 block">الوصف</label>
                <textarea name="description" rows="4" placeholder="اكتبي وصف الموديل هنا..." class="{{ $inputCls }}">{{ old('description', $product->description) }}</textarea>
            </div>

            {{-- حالة الموديل (منشور / مخفي) --}}
            <label class="flex items-center gap-3 cursor-pointer bg-[#FFF7F5] border border-[#F0D9DF] rounded-[14px] px-4 py-3">
                <input type="checkbox" name="is_active" value="1" {{ $isActive ? 'checked' : '' }} class="w-5 h-5 accent-[#C2577A]">
                <span class="text-sm font-bold text-[#3B2530]">الموديل ظاهر في المتجر</span>
            </label>

            {{-- الألوان والمقاسات والكميات --}}
            <div class="bg-[#FFF7F5] p-5 rounded-[20px] border border-[#F0D9DF]">
                <label class="text-sm font-bold text-[#C2577A] mb-4 flex items-center gap-2">
                    <iconify-icon icon="lucide:layers"></iconify-icon> الألوان، المقاسات والكميات
                </label>

                <div id="variances-container" class="space-y-4">
                    @foreach($rows as $i => $row)
                        <div class="variance-row flex flex-wrap md:flex-nowrap items-center gap-4 bg-white p-4 rounded-xl border border-[#F0D9DF] shadow-sm">
                            <div class="flex-1 min-w-[120px]">
                                <label class="text-[10px] font-bold text-[#8A6E78] mb-1.5 block">اللون</label>
                                <select name="variance[{{ $i }}][color_id]" required class="{{ $smallCls }}">
                                    <option value="" disabled {{ empty($row['color_id']) ? 'selected' : '' }}>اختر اللون...</option>
                                    @foreach($colors as $color)
                                        <option value="{{ $color->id }}" {{ (string) ($row['color_id'] ?? '') === (string) $color->id ? 'selected' : '' }}>{{ $color->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex-1 min-w-[120px]">
                                <label class="text-[10px] font-bold text-[#8A6E78] mb-1.5 block">المقاس</label>
                                <select name="variance[{{ $i }}][size_id]" required class="{{ $smallCls }}">
                                    <option value="" disabled {{ empty($row['size_id']) ? 'selected' : '' }}>اختر المقاس...</option>
                                    @foreach($sizes as $size)
                                        <option value="{{ $size->id }}" {{ (string) ($row['size_id'] ?? '') === (string) $size->id ? 'selected' : '' }}>{{ $size->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="w-24">
                                <label class="text-[10px] font-bold text-[#8A6E78] mb-1.5 block">الكمية</label>
                                <input type="number" name="variance[{{ $i }}][stock]" required min="0" placeholder="0" value="{{ $row['stock'] ?? '' }}" class="{{ $smallCls }} text-center">
                            </div>

                            <div class="w-10 flex justify-center mt-5">
                                <button type="button" onclick="removeVarianceRow(this)" class="p-2 text-red-500 hover:bg-red-50 rounded-full transition-colors" title="حذف هذا الصف">
                                    <iconify-icon icon="lucide:trash-2" width="20"></iconify-icon>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" onclick="addVarianceRow()" class="mt-4 flex items-center gap-2 text-sm font-bold text-[#C2577A] hover:text-[#A9456A] transition-colors">
                    <iconify-icon icon="lucide:plus-circle" width="18"></iconify-icon>
                    إضافة لون أو مقاس آخر
                </button>
            </div>
        </div>

        {{-- ===================== العمود الثاني: الصور ===================== --}}
        <div class="space-y-6">
            {{-- الصورة الرئيسية الحالية --}}
            <div class="bg-[#FFF7F5] p-5 rounded-[20px] border border-[#F0D9DF]">
                <h4 class="text-sm font-bold text-[#C2577A] mb-2 flex items-center gap-2">
                    <iconify-icon icon="lucide:image"></iconify-icon> الصورة الرئيسية
                </h4>
                <div class="relative w-full aspect-[4/3] bg-white border-2 border-dashed border-[#C2577A]/50 rounded-[14px] overflow-hidden flex items-center justify-center">
                    <img id="main-preview" src="{{ $mainImage ? $imgUrl($mainImage) : '' }}" alt="الصورة الرئيسية" class="absolute inset-0 w-full h-full object-cover object-top {{ $mainImage ? '' : 'hidden' }}">
                    <div id="main-preview-empty" class="flex flex-col items-center text-[#C2577A] {{ $mainImage ? 'hidden' : '' }}">
                        <iconify-icon icon="lucide:image-off" width="32"></iconify-icon>
                        <span class="text-xs font-bold mt-2">لا توجد صور للموديل</span>
                    </div>
                </div>
                @if($images->count() > 1)
                    <p class="text-[11px] text-[#8A6E78] mt-2">اختاري أي صورة من المعرض تحت عشان تبقى هي الرئيسية.</p>
                @endif
            </div>

            {{-- معرض الصور الحالية --}}
            <div class="bg-[#FFF7F5] p-5 rounded-[20px] border border-[#F0D9DF]">
                <h4 class="text-sm font-bold text-[#3B2530] mb-3 flex items-center gap-2">
                    <iconify-icon icon="lucide:images"></iconify-icon> صور الموديل ({{ $images->count() }})
                </h4>

                @if($images->count())
                    <div class="grid grid-cols-3 gap-3 mb-4">
                        @foreach($images as $image)
                            <div class="relative aspect-[3/4] rounded-[14px] overflow-hidden border border-[#F0D9DF] bg-white">
                                <label class="block w-full h-full cursor-pointer">
                                    <input type="radio" name="main_image_id" value="{{ $image->id }}" data-url="{{ $imgUrl($image) }}"
                                        class="peer hidden gallery-radio" {{ (string) $selectedMain === (string) $image->id ? 'checked' : '' }}>
                                    <img src="{{ $imgUrl($image) }}" alt="" loading="lazy" class="w-full h-full object-cover object-top">
                                    <span class="absolute inset-0 ring-4 ring-inset ring-[#C2577A] rounded-[14px] opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity"></span>
                                    <span class="absolute bottom-1.5 right-1.5 bg-[#C2577A] text-white text-[10px] font-bold px-2 py-0.5 rounded-full hidden peer-checked:block">رئيسية</span>
                                </label>

                                {{-- زر حذف الصورة: بيستخدم form خارجي (تحت) عشان مفيش فورم جوه فورم --}}
                                <button type="submit" form="delete-image-{{ $image->id }}" title="حذف الصورة"
                                        class="absolute top-1.5 left-1.5 z-10 p-1.5 bg-white/95 rounded-full text-red-500 shadow hover:scale-110 transition-transform">
                                    <iconify-icon icon="lucide:trash-2" width="16"></iconify-icon>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- إضافة صور جديدة --}}
                <input type="file" name="gallery_images[]" id="gallery_images" accept="image/*" multiple class="hidden" onchange="updateFilesCount(this)">
                <label for="gallery_images" class="cursor-pointer block w-full py-6 bg-white border-2 border-dashed border-[#F0D9DF] rounded-[14px] flex flex-col items-center justify-center hover:bg-[#FDEEF0] transition-colors">
                    <iconify-icon icon="lucide:plus-circle" width="28" class="text-[#8A6E78]"></iconify-icon>
                    <span class="text-xs font-bold mt-2 text-[#8A6E78]" id="gallery-files-text">إضافة صور جديدة (0)</span>
                </label>
            </div>

            <div class="flex justify-end gap-4 pt-4 border-t border-[#F0D9DF]">
                <a href="{{ route('admin.dashboard') }}#products-section" class="px-8 py-3 text-[#8A6E78] font-bold hover:bg-[#FDEEF0] rounded-[14px] transition-all">إلغاء</a>
                <button type="submit" class="px-10 py-3 bg-[#C2577A] text-white rounded-[14px] font-bold shadow-lg shadow-[#C2577A]/20 hover:bg-[#A9456A] transition-all">حفظ التعديلات</button>
            </div>
        </div>
    </form>

    {{-- فورمات حذف الصور: بره فورم التعديل تماماً --}}
    @foreach($images as $image)
        <form id="delete-image-{{ $image->id }}" action="{{ route('admin.products.images.destroy', $image->id) }}" method="POST" class="hidden"
              onsubmit="return confirm('هل أنت متأكد من حذف هذه الصورة؟');">
            @csrf
            @method('DELETE')
        </form>
    @endforeach
</section>

<script>
    let varianceIndex = {{ $nextIndex }};

    function addVarianceRow() {
        const container = document.getElementById('variances-container');
        const newRow = container.querySelector('.variance-row').cloneNode(true);

        newRow.querySelectorAll('select, input').forEach(el => {
            el.name = el.name.replace(/\[\d+\]/, '[' + varianceIndex + ']');
            if (el.tagName === 'INPUT') el.value = '';
            if (el.tagName === 'SELECT') el.selectedIndex = 0;
        });

        container.appendChild(newRow);
        varianceIndex++;
    }

    function removeVarianceRow(button) {
        const container = document.getElementById('variances-container');
        if (container.querySelectorAll('.variance-row').length > 1) {
            button.closest('.variance-row').remove();
        }
    }

    // تغيير الصورة الرئيسية في المعاينة عند اختيار صورة من المعرض
    document.querySelectorAll('.gallery-radio').forEach(radio => {
        radio.addEventListener('change', () => {
            const preview = document.getElementById('main-preview');
            preview.src = radio.dataset.url;
            preview.classList.remove('hidden');
            document.getElementById('main-preview-empty').classList.add('hidden');
        });
    });

    function updateFilesCount(input) {
        const text = document.getElementById('gallery-files-text');
        if (input.files && input.files.length > 0) {
            text.innerText = `تم تحديد (${input.files.length}) صور جديدة`;
            text.classList.add('text-[#C2577A]');
        } else {
            text.innerText = 'إضافة صور جديدة (0)';
            text.classList.remove('text-[#C2577A]');
        }
    }
</script>
</main>
</body>
</html>