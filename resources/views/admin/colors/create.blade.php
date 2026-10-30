@extends('admin.layouts.admin') 

@section('title', 'إضافة لون')
@section('header_title', 'إضافة لون جديد')

@section('content')
<section class="max-w-2xl bg-white rounded-[20px] custom-shadow border border-[#F0D9DF] p-8 mx-auto mt-10">
    <form action="{{ route('admin.colors.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="space-y-2">
            <label class="text-xs font-bold text-[#8A6E78]">اسم اللون</label>
            <input type="text" name="name" required placeholder="مثال: وردي" class="w-full h-[52px] px-4 rounded-[14px] bg-white border border-[#F0D9DF] focus:outline-none focus:border-[#C2577A]">
        </div>

        <div class="space-y-2">
            <label class="text-xs font-bold text-[#8A6E78]">كود اللون (Hex)</label>
            <input type="color" name="hex_code" required class="w-full h-[52px] px-2 rounded-[14px] bg-white border border-[#F0D9DF] cursor-pointer">
        </div>

        <div class="flex justify-end gap-4 mt-8">
            <a href="{{ route('admin.colors.index') }}" class="px-8 py-3 text-[#8A6E78] font-bold hover:bg-[#FDEEF0] rounded-[14px] transition-all">إلغاء</a>
            <button type="submit" class="px-10 py-3 bg-[#C2577A] text-white rounded-[14px] font-bold shadow-lg shadow-[#C2577A]/20 transition-all hover:bg-[#A9456A]">حفظ</button>
        </div>
    </form>
</section>
@endsection