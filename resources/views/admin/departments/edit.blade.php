@extends('layouts.admin')

@section('title', 'تعديل قسم')
@section('header_title', 'تعديل بيانات القسم')

@section('content')
<section class="max-w-2xl bg-white rounded-[20px] custom-shadow border border-[#F0D9DF] p-8 mx-auto mt-10">
    <div class="mb-6 border-b border-[#F0D9DF] pb-4">
        <h3 class="text-lg font-bold font-heading text-[#3B2530]">تعديل قسم: {{ $department->name ?? 'القسم' }}</h3>
    </div>

    <form action="{{ route('admin.departments.update', $department->id ?? 1) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="space-y-2">
            <label class="text-xs font-bold text-[#8A6E78]">اسم القسم</label>
            <input type="text" name="name" value="{{ old('name', $department->name ?? '') }}" required placeholder="مثال: الملاحف" class="w-full h-[52px] px-4 rounded-[14px] bg-white border border-[#F0D9DF] focus:outline-none focus:border-[#C2577A] transition-all">
            @error('name') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-end gap-4 mt-8 pt-4 border-t border-[#F0D9DF]">
            <a href="{{ route('admin.departments.index') }}" class="px-8 py-3 text-[#8A6E78] font-bold hover:bg-[#FDEEF0] rounded-[14px] transition-all flex items-center">إلغاء</a>
            <button type="submit" class="px-10 py-3 bg-[#C2577A] text-white rounded-[14px] font-bold shadow-lg shadow-[#C2577A]/20 transition-all hover:bg-[#A9456A] flex items-center gap-2">
                <iconify-icon icon="lucide:save"></iconify-icon> تحديث القسم
            </button>
        </div>
    </form>
</section>
@endsection