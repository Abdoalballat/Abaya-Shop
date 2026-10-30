@extends('layouts.admin')

@section('title', 'إضافة موديل - لوحة التحكم')
@section('header_title', 'إضافة موديل جديد')

@section('content')
<section class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Form Area -->
    <div class="lg:col-span-2 bg-white rounded-[20px] custom-shadow border border-[#F0D9DF] p-8">
        <h3 class="text-lg font-bold font-heading mb-6 border-b border-[#F0D9DF] pb-4">بيانات الموديل</h3>
        
        <form action="#" method="POST" enctype="multipart/form-data" class="grid grid-cols-2 gap-6">
            @csrf
            
            <div class="col-span-2 flex flex-col gap-2">
                <label class="text-xs font-bold text-[#8A6E78]">اسم الموديل</label>
                <input type="text" name="name" required placeholder="مثال: عباية ملكي كريب">
            </div>
            
            <div class="flex flex-col gap-2">
                <label class="text-xs font-bold text-[#8A6E78]">كود الموديل</label>
                <input type="text" name="code" required placeholder="909">
            </div>
            
            <div class="flex flex-col gap-2">
                <label class="text-xs font-bold text-[#8A6E78]">السعر الأساسي (ج.م)</label>
                <input type="number" name="price" required placeholder="950">
            </div>
            
            <div class="flex flex-col gap-2">
                <label class="text-xs font-bold text-[#8A6E78]">التصنيف</label>
                <select name="department_id" required>
                    <option value="">اختر التصنيف</option>
                    {{-- @foreach($departments as $dept) --}}
                    <option value="1">العبايات الشتوي</option>
                    {{-- @endforeach --}}
                </select>
            </div>
            
            <div class="flex flex-col gap-2">
                <label class="text-xs font-bold text-[#8A6E78]">نوع الخامة</label>
                <input type="text" name="fabric_type" placeholder="كريب كوري فاخر">
            </div>
            
            <div class="col-span-2 flex flex-col gap-2">
                <label class="text-xs font-bold text-[#8A6E78]">الوصف</label>
                <textarea name="description" rows="3" placeholder="اكتبي وصف الموديل هنا..."></textarea>
            </div>
            
            <div class="col-span-2">
                <label class="text-xs font-bold text-[#8A6E78] block mb-3">المقاسات المتاحة</label>
                <div class="flex flex-wrap gap-3">
                    {{-- @foreach($sizes as $size) --}}
                    <label class="cursor-pointer">
                        <input type="checkbox" name="sizes[]" value="1" class="hidden peer">
                        <span class="px-4 py-2 border border-[#F0D9DF] rounded-full text-sm peer-checked:bg-[#C2577A] peer-checked:text-white transition-all">One Size</span>
                    </label>
                    <label class="cursor-pointer">
                        <input type="checkbox" name="sizes[]" value="2" class="hidden peer">
                        <span class="px-4 py-2 border border-[#F0D9DF] rounded-full text-sm peer-checked:bg-[#C2577A] peer-checked:text-white transition-all">M/L</span>
                    </label>
                    {{-- @endforeach --}}
                </div>
            </div>
            
            <div class="col-span-2 flex justify-end gap-4 mt-4">
                <a href="{{ route('admin.products.index') }}" class="px-8 py-3 text-[#8A6E78] font-bold hover:bg-[#FDEEF0] rounded-[14px] transition-all">إلغاء</a>
                <button type="submit" class="px-10 py-3 bg-[#C2577A] text-white rounded-[14px] font-bold shadow-lg shadow-[#C2577A]/20 transition-all hover:bg-[#A9456A]">حفظ الموديل</button>
            </div>
        </form>
    </div>

    <!-- Image Upload Area (Can be integrated into the form above in actual logic) -->
    <div class="bg-white rounded-[20px] custom-shadow border border-[#F0D9DF] p-6 h-fit">
        <h3 class="text-sm font-bold text-[#3B2530] mb-4">صور الموديل (بحد أقصى 10)</h3>
        <div class="grid grid-cols-2 gap-3 mb-6">
            <label class="aspect-square border-2 border-dashed border-[#F0D9DF] rounded-[14px] flex flex-col items-center justify-center gap-2 cursor-pointer hover:bg-[#FDEEF0] transition-colors">
                <iconify-icon icon="lucide:plus" class="text-[#C2577A]" width="24"></iconify-icon>
                <span class="text-[10px] font-bold text-[#8A6E78]">إضافة صور</span>
                <input type="file" multiple accept="image/*" class="hidden">
            </label>
            <!-- Preview Example -->
            <div class="aspect-square bg-[#FFF7F5] rounded-[14px] relative overflow-hidden group">
                <img src="https://placehold.co/300x400/FDEEF0/C2577A?text=Preview" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                    <button class="p-1.5 bg-white rounded-full text-red-500 hover:scale-110 transition-transform">
                        <iconify-icon icon="lucide:trash-2"></iconify-icon>
                    </button>
                </div>
            </div>
        </div>
        <div class="bg-[#FDEEF0] p-4 rounded-[14px] border border-[#F0D9DF]">
            <p class="text-[10px] text-[#C2577A] font-bold mb-1">تنبيه:</p>
            <p class="text-[10px] text-[#8A6E78] leading-relaxed">يرجى رفع صور بدقة 3:4 واضحة للحصول على أفضل عرض في المتجر.</p>
        </div>
    </div>
</section>
@endsection