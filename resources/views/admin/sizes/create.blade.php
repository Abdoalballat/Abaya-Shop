@extends('layouts.admin')

@section('title', 'إضافة مقاس')
@section('header_title', 'إضافة مقاس جديد')

@section('content')
<section class="max-w-2xl bg-white rounded-[20px] custom-shadow border border-[#F0D9DF] p-8 mx-auto mt-10">
    <form action="{{ route('admin.sizes.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="space-y-2">
            <label class="text-xs font-bold text-[#8A6E78]">اسم المقاس</label>
            <input type="text" name="name" required placeholder="مثال: XL/XXL أو One Size" value="{{ old('name') }}" class="w-full h-[52px] px-4 rounded-[14px] bg-white border border-[#F0D9DF] focus:outline-none focus:border-[#C2577A] transition-all text-left" dir="ltr">
            @error('name') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-end gap-4 mt-8 pt-4 border-t border-[#F0D9DF]">
            <a href="{{ route('admin.sizes.index') }}" class="px-8 py-3 text-[#8A6E78] font-bold hover:bg-[#FDEEF0] rounded-[14px] transition-all flex items-center">إلغاء</a>
            <button type="submit" class="px-10 py-3 bg-[#C2577A] text-white rounded-[14px] font-bold shadow-lg shadow-[#C2577A]/20 transition-all hover:bg-[#A9456A] flex items-center gap-2">
                <iconify-icon icon="lucide:save"></iconify-icon> حفظ المقاس
            </button>
        </div>
    </form>
</section>
@endsection