@extends('layouts.admin')

@section('title', 'إدارة الموديلات')
@section('header_title', 'الموديلات')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h3 class="text-xl font-bold text-[#3B2530]">كل الموديلات</h3>
    <a href="{{ route('admin.products.create') }}" class="px-6 py-2 bg-[#C2577A] hover:bg-[#A9456A] text-white rounded-[14px] font-bold shadow-md transition-all flex items-center gap-2">
        <iconify-icon icon="lucide:plus"></iconify-icon> إضافة موديل جديد
    </a>
</div>

<section class="bg-white rounded-[20px] custom-shadow border border-[#F0D9DF] overflow-hidden">
    <!-- شريط البحث (اختياري) -->
    <div class="p-4 border-b border-[#F0D9DF] bg-[#FDEEF0]/30">
        <div class="relative max-w-md">
            <iconify-icon icon="lucide:search" class="absolute right-3 top-3 text-[#8A6E78]"></iconify-icon>
            <input type="text" placeholder="بحث باسم أو كود الموديل..." class="w-full h-10 pr-10 pl-4 rounded-[10px] bg-white border border-[#F0D9DF] focus:outline-none focus:border-[#C2577A] text-sm">
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-right">
            <thead>
                <tr class="bg-[#FFF7F5] text-[#8A6E78] text-xs font-bold">
                    <th class="px-6 py-4">صورة</th>
                    <th class="px-6 py-4">الكود</th>
                    <th class="px-6 py-4">اسم الموديل</th>
                    <th class="px-6 py-4">القسم</th>
                    <th class="px-6 py-4">السعر</th>
                    <th class="px-6 py-4">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#F0D9DF]">
                {{-- @forelse($products as $product) --}}
                <tr class="hover:bg-[#FFF7F5] transition-colors">
                    <td class="px-6 py-4">
                        <div class="w-12 h-16 bg-[#FDEEF0] rounded-[8px] overflow-hidden">
                            <img src="https://placehold.co/100x133/FDEEF0/C2577A?text=Abaya" class="w-full h-full object-cover">
                        </div>
                    </td>
                    <td class="px-6 py-4 font-mono font-bold text-[#C2577A]">909</td>
                    <td class="px-6 py-4 font-bold text-sm text-[#3B2530]">عباية شتوي ملكي</td>
                    <td class="px-6 py-4 text-xs text-[#8A6E78]">العبايات الشتوي</td>
                    <td class="px-6 py-4 font-bold text-[#C9A36B]">950 ج.م</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{-- route('admin.products.edit', $product->id) --}}" class="p-2 hover:bg-[#EDE7F8] rounded-full text-[#9B87C9] transition-all">
                                <iconify-icon icon="lucide:edit" width="18"></iconify-icon>
                            </a>
                            <form action="{{-- route('admin.products.destroy', $product->id) --}}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                @csrf @method('DELETE')
                                <button class="p-2 hover:bg-[#FDEEF0] rounded-full text-red-500 transition-all">
                                    <iconify-icon icon="lucide:trash-2" width="18"></iconify-icon>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                {{-- @empty ... @endforelse --}}
            </tbody>
        </table>
    </div>
</section>
@endsection