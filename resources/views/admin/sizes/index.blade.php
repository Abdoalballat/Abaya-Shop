@extends('layouts.admin')

@section('title', 'إدارة المقاسات')
@section('header_title', 'المقاسات')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h3 class="text-xl font-bold text-[#3B2530]">كل المقاسات</h3>
    <a href="{{ route('admin.sizes.create') }}" class="px-6 py-2 bg-[#C2577A] hover:bg-[#A9456A] text-white rounded-[14px] font-bold shadow-md transition-all flex items-center gap-2">
        <iconify-icon icon="lucide:plus"></iconify-icon> إضافة مقاس جديد
    </a>
</div>

<section class="bg-white rounded-[20px] custom-shadow border border-[#F0D9DF] overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-right">
            <thead>
                <tr class="bg-[#FFF7F5] text-[#8A6E78] text-xs font-bold">
                    <th class="px-6 py-4">م</th>
                    <th class="px-6 py-4">اسم المقاس</th>
                    <th class="px-6 py-4">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#F0D9DF]">
                {{-- @forelse($sizes as $index => $size) --}}
                <tr class="hover:bg-[#FFF7F5] transition-colors">
                    <td class="px-6 py-4 font-bold text-sm">1</td>
                    <td class="px-6 py-4 font-bold text-sm">One Size</td>
                    <td class="px-6 py-4 flex items-center gap-2">
                        <a href="{{-- route('admin.sizes.edit', $size->id) --}}" class="p-2 hover:bg-[#EDE7F8] rounded-full text-[#9B87C9] transition-all">
                            <iconify-icon icon="lucide:edit" width="18"></iconify-icon>
                        </a>
                        <form action="{{-- route('admin.sizes.destroy', $size->id) --}}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                            @csrf @method('DELETE')
                            <button class="p-2 hover:bg-[#FDEEF0] rounded-full text-red-500 transition-all">
                                <iconify-icon icon="lucide:trash-2" width="18"></iconify-icon>
                            </button>
                        </form>
                    </td>
                </tr>
                {{-- @empty --}}
                {{-- <tr><td colspan="3" class="text-center py-8 text-[#8A6E78]">لا توجد مقاسات مضافة</td></tr> --}}
                {{-- @endforelse --}}
            </tbody>
        </table>
    </div>
</section>
@endsection