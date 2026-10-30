@extends('layouts.admin')

@section('title', 'الطلبيات - لوحة التحكم')
@section('header_title', 'إدارة الطلبيات')

@section('content')
<!-- Orders Table -->
<section class="bg-white rounded-[20px] custom-shadow border border-[#F0D9DF] overflow-hidden">
    <div class="p-6 border-b border-[#F0D9DF] flex items-center justify-between">
        <div class="flex items-center gap-3">
            <h3 class="text-lg font-bold font-heading">الطلبيات الأخيرة</h3>
            <span class="bg-[#FDEEF0] text-[#C2577A] px-2 py-0.5 rounded-full text-xs font-bold">{{ count($orders ?? []) }} طلب</span>
        </div>
        <div class="flex gap-3">
            <button class="flex items-center gap-2 px-4 py-2 bg-[#FDEEF0] text-[#C2577A] rounded-[14px] text-sm font-bold">
                <iconify-icon icon="lucide:filter" width="16"></iconify-icon> تصفية
            </button>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-right">
            <thead>
                <tr class="bg-[#FFF7F5] text-[#8A6E78] text-xs font-bold">
                    <th class="px-6 py-4">رقم الطلب</th>
                    <th class="px-6 py-4">العميلة</th>
                    <th class="px-6 py-4">التاريخ</th>
                    <th class="px-6 py-4">الإجمالي</th>
                    <th class="px-6 py-4">الحالة</th>
                    <th class="px-6 py-4">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#F0D9DF]">
                {{-- Example Row (Replace with @foreach($orders as $order) in real app) --}}
                <tr class="hover:bg-[#FFF7F5] transition-colors">
                    <td class="px-6 py-4 font-mono text-[#C2577A] text-sm">ORD-24-001</td>
                    <td class="px-6 py-4 font-bold text-sm">سارة أحمد علي</td>
                    <td class="px-6 py-4 text-xs text-[#8A6E78]">2024/05/12</td>
                    <td class="px-6 py-4 font-bold text-[#C9A36B]">2,700 ج.م</td>
                    <td class="px-6 py-4">
                        <span class="status-badge bg-[#EDE7F8] text-[#9B87C9]">جديد</span>
                    </td>
                    <td class="px-6 py-4">
                        <button onclick="openOrderModal('ORD-24-001')" class="p-2 hover:bg-[#FDEEF0] rounded-full text-[#C2577A] transition-all">
                            <iconify-icon icon="lucide:eye" width="18"></iconify-icon>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
@endsection

@push('modals')
<!-- Order Details Modal (Placeholder) -->
<div id="order-modal" class="fixed inset-0 bg-[#3B2530]/40 backdrop-blur-sm z-[100] hidden items-center justify-center p-6">
    <div class="bg-white w-full max-w-2xl rounded-[24px] custom-shadow max-h-[90vh] overflow-y-auto relative">
        <button onclick="closeOrderModal()" class="absolute top-6 left-6 text-[#8A6E78] hover:text-[#C2577A] z-10 bg-white rounded-full">
            <iconify-icon icon="lucide:x" width="24"></iconify-icon>
        </button>
        <div class="p-8" id="modal-content">
            <!-- سيتم حقن تفاصيل الطلب هنا عبر الـ AJAX أو وضع تفاصيل ثابتة للتجربة -->
            <h3 class="text-xl font-bold font-heading mb-8">تفاصيل الطلب</h3>
            <p>محتوى الطلب يظهر هنا...</p>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    function openOrderModal(orderId) {
        document.getElementById('order-modal').classList.remove('hidden');
        document.getElementById('order-modal').classList.add('flex');
    }
    function closeOrderModal() {
        document.getElementById('order-modal').classList.add('hidden');
        document.getElementById('order-modal').classList.remove('flex');
    }
</script>
@endpush