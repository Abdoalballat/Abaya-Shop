@extends('layouts.admin')

@section('title', 'تفاصيل الطلب - لوحة التحكم')
@section('header_title')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.orders.index') }}" class="w-8 h-8 rounded-full bg-[#FDEEF0] text-[#C2577A] flex items-center justify-center hover:bg-[#F0D9DF] transition-colors">
            <iconify-icon icon="lucide:chevron-right"></iconify-icon>
        </a>
        <span>تفاصيل الطلب: ORD-24-001</span>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- معلومات الطلب والعميل -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- بطاقة العميل -->
        <div class="bg-white rounded-[20px] border border-[#F0D9DF] custom-shadow p-6">
            <h4 class="font-bold text-[#C2577A] mb-4 border-b border-[#F0D9DF] pb-2 flex items-center gap-2">
                <iconify-icon icon="lucide:user"></iconify-icon> بيانات العميل والتوصيل
            </h4>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-[10px] font-bold text-[#8A6E78] uppercase tracking-wide">العميلة</p>
                    <p class="font-bold mt-1 text-[#3B2530]">سارة أحمد علي</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-[#8A6E78] uppercase tracking-wide">رقم الهاتف</p>
                    <p class="font-medium mt-1 text-[#3B2530]" dir="ltr">01012345678</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-[#8A6E78] uppercase tracking-wide">المحافظة</p>
                    <p class="font-bold mt-1 text-[#3B2530]">القاهرة (50 ج.م)</p>
                </div>
                <div class="col-span-2 md:col-span-3">
                    <p class="text-[10px] font-bold text-[#8A6E78] uppercase tracking-wide">العنوان بالتفصيل</p>
                    <p class="text-sm text-[#3B2530] mt-1 leading-relaxed bg-[#FFF7F5] p-3 rounded-[10px] border border-[#F0D9DF]">
                        حي المعادي، شارع 9، عمارة 44، الدور التالت
                    </p>
                </div>
            </div>
        </div>

        <!-- المنتجات المطلوبة -->
        <div class="bg-white rounded-[20px] border border-[#F0D9DF] custom-shadow p-6">
            <h4 class="font-bold text-[#C2577A] mb-4 border-b border-[#F0D9DF] pb-2 flex items-center gap-2">
                <iconify-icon icon="lucide:shopping-bag"></iconify-icon> المنتجات (2)
            </h4>
            <div class="space-y-4">
                {{-- @foreach($order->items as $item) --}}
                <div class="flex items-center gap-4 bg-[#FFF7F5] p-3 rounded-[14px] border border-[#F0D9DF]">
                    <img src="https://placehold.co/100x133/FDEEF0/C2577A?text=Item" class="w-14 h-20 object-cover rounded-lg border border-[#F0D9DF]">
                    <div class="flex-1">
                        <p class="text-sm font-bold text-[#3B2530]">عباية شتوي ملكي</p>
                        <p class="text-xs text-[#8A6E78] mt-1">كود 909 | لون: وردي | مقاس: One Size</p>
                    </div>
                    <div class="text-center px-4 border-r border-[#F0D9DF]">
                        <p class="text-[10px] text-[#8A6E78]">الكمية</p>
                        <p class="font-bold text-[#3B2530]">1</p>
                    </div>
                    <p class="font-bold text-[#C9A36B] text-lg w-24 text-left">950 ج.م</p>
                </div>
                {{-- @endforeach --}}
            </div>
            <!-- إجمالي الحساب -->
            <div class="mt-6 pt-4 border-t border-[#F0D9DF] flex flex-col items-end gap-2 text-sm">
                <div class="flex justify-between w-64 text-[#8A6E78]"><span>المجموع الفرعي:</span> <span class="font-medium text-[#3B2530]">2,650 ج.م</span></div>
                <div class="flex justify-between w-64 text-[#8A6E78]"><span>الشحن:</span> <span class="font-medium text-[#3B2530]">50 ج.م</span></div>
                <div class="flex justify-between w-64 text-[#C2577A] font-bold text-lg pt-2 border-t border-dashed border-[#F0D9DF]">
                    <span>الإجمالي:</span> <span>2,700 ج.م</span>
                </div>
            </div>
        </div>

    </div>

    <!-- الشريط الجانبي لتفاصيل الطلب (الإيصال وحالة الطلب) -->
    <div class="space-y-6">
        
        <!-- تحديث الحالة -->
        <div class="bg-[#EDE7F8] border border-[#9B87C9]/30 rounded-[20px] custom-shadow p-6">
            <h4 class="font-bold text-[#3B2530] mb-4">تحديث حالة الطلب</h4>
            <form action="#" method="POST" class="space-y-4">
                @csrf @method('PUT')
                <select name="status" class="w-full h-[52px] px-4 rounded-[14px] bg-white border border-[#9B87C9]/30 focus:border-[#9B87C9] transition-all font-bold text-[#3B2530]">
                    <option value="new" selected>جديد (New)</option>
                    <option value="processing">تحت المعالجة (Processing)</option>
                    <option value="shipped">تم الشحن (Shipped)</option>
                    <option value="delivered">تم التوصيل (Delivered)</option>
                    <option value="cancelled">ملغي (Cancelled)</option>
                </select>
                <button type="submit" class="w-full h-[52px] bg-[#9B87C9] hover:bg-[#8670B4] text-white rounded-[14px] font-bold transition-all shadow-md flex items-center justify-center gap-2">
                    <iconify-icon icon="lucide:save"></iconify-icon> حفظ التغييرات
                </button>
            </form>
        </div>

        <!-- إيصال الدفع -->
        <div class="bg-white rounded-[20px] border border-[#F0D9DF] custom-shadow p-6 text-center">
            <h4 class="font-bold text-[#3B2530] mb-4 flex items-center justify-center gap-2">
                <iconify-icon icon="lucide:image" class="text-[#C2577A]"></iconify-icon> إيصال الدفع
            </h4>
            <a href="https://placehold.co/600x800/FDEEF0/C2577A?text=Receipt" target="_blank" class="block relative border border-[#F0D9DF] rounded-[14px] overflow-hidden group mb-3">
                <img src="https://placehold.co/300x400/FDEEF0/C2577A?text=Receipt" class="w-full h-48 object-cover">
                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    <iconify-icon icon="lucide:external-link" width="24" class="text-white"></iconify-icon>
                </div>
            </a>
            <p class="text-[10px] text-[#8A6E78]">اضغطي على الصورة للتكبير</p>
        </div>

    </div>
</div>
@endsection