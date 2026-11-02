<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - عبايات المصطفى</title>
    
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Aref+Ruqaa:wght@400;700&family=Tajawal:wght@300;400;500;700&display=swap');

        :root {
            --bg-page: #FFF7F5; --bg-soft: #FDEEF0; --surface: #FFFFFF;
            --primary: #C2577A; --primary-hover: #A9456A; --primary-soft: #F7D6DF;
            --secondary: #9B87C9; --secondary-soft: #EDE7F8; --accent-gold: #C9A36B;
            --text-main: #3B2530; --text-muted: #8A6E78; --border-rose: #F0D9DF;
            --shadow-rose: 0 10px 30px -10px rgba(194, 87, 122, 0.15);
        }

        body {
            font-family: 'Tajawal', sans-serif; background-color: var(--bg-page); color: var(--text-main); margin: 0;
        }

        .font-heading { font-family: 'Aref Ruqaa', serif; }
        .custom-shadow { box-shadow: var(--shadow-rose); }
        
        .sidebar-active { background-color: var(--primary-soft) !important; color: var(--primary) !important; border-radius: 14px; font-weight: bold;}
        .sidebar-link { color: var(--text-muted); padding: 0.75rem 1rem; border-radius: 14px; transition: all 0.3s; display: flex; align-items: center; gap: 0.75rem; cursor: pointer; }
        .sidebar-link:hover { background-color: var(--bg-soft); }

        .status-badge { padding: 4px 12px; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; }
        .floral-pattern { background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 0c2.5 5 7.5 5 10 0s5-7.5 0-10-7.5-5-10 0-7.5 5-10 0-5 7.5 0 10 7.5 5 10 0z' fill='%23C2577A' fill-opacity='0.04' fill-rule='evenodd'/%3E%3C/svg%3E"); }

        input:not([type="color"]), select, textarea {
            border: 1px solid var(--border-rose); border-radius: 14px; padding: 10px 16px; outline: none; transition: all 0.2s; width: 100%; background: #FFF7F5;
        }
        input:focus, select:focus, textarea:focus { border-color: var(--primary); background: #FFF; box-shadow: 0 0 0 3px rgba(194, 87, 122, 0.1); }
        ::-webkit-scrollbar { display: none; } * { scrollbar-width: none; }
    </style>
</head>
<body>

    <div class="flex min-h-screen">
        
        <!-- القائمة الجانبية (Sidebar) -->
        <aside class="w-[280px] bg-white border-l border-[#F0D9DF] flex flex-col sticky top-0 h-screen z-50 overflow-y-auto">
            <div class="p-8 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-[#FDEEF0] flex items-center justify-center text-[#C2577A] shrink-0">
                    <iconify-icon icon="lucide:flower-2" width="24"></iconify-icon>
                </div>
                <h1 class="text-xl font-bold font-heading text-[#C2577A]">عبايات المصطفى</h1>
            </div>

            <nav class="flex-1 px-4 space-y-1" id="sidebar-nav">
                <a onclick="switchTab('dashboard-section', this)" class="sidebar-link sidebar-active">
                    <iconify-icon icon="lucide:home" width="20"></iconify-icon><span>الرئيسية</span>
                </a>
                <a onclick="switchTab('orders-section', this)" class="sidebar-link">
                    <iconify-icon icon="lucide:package" width="20"></iconify-icon><span>الطلبيات</span>
                </a>
                <a onclick="switchTab('add-product-section', this)" class="sidebar-link">
                    <iconify-icon icon="lucide:shirt" width="20"></iconify-icon><span>إضافة موديل جديد</span>
                </a>
                <!-- الموديلات (جديد) -->
                <a onclick="switchTab('products-section', this)" class="sidebar-link">
                    <iconify-icon icon="lucide:shopping-bag" width="20"></iconify-icon><span>الموديلات</span>
                </a>
                <div class="h-px bg-[#F0D9DF] my-4 mx-2"></div>
                <!-- الأقسام الجديدة -->
                <a onclick="switchTab('departments-section', this)" class="sidebar-link">
                    <iconify-icon icon="lucide:layers" width="20"></iconify-icon><span>الأقسام</span>
                </a>
                <a onclick="switchTab('governorates-section', this)" class="sidebar-link">
                    <iconify-icon icon="lucide:map-pin" width="20"></iconify-icon><span>المحافظات والشحن</span>
                </a>
                <a onclick="switchTab('colors-section', this)" class="sidebar-link">
                    <iconify-icon icon="lucide:palette" width="20"></iconify-icon><span>الألوان</span>
                </a>
                <a onclick="switchTab('sizes-section', this)" class="sidebar-link">
                    <iconify-icon icon="lucide:ruler" width="20"></iconify-icon><span>المقاسات</span>
                </a>
            </nav>

            <div class="p-6 border-t border-[#F0D9DF] mt-auto bg-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-[#EDE7F8] flex items-center justify-center text-[#9B87C9] font-bold shrink-0">
                        {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-bold truncate">{{ auth()->user()->name ?? 'أدمن المصطفى' }}</p>
                        <p class="text-[10px] text-[#8A6E78]">مدير المتجر</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- المحتوى الرئيسي -->
        <main class="flex-1 flex flex-col floral-pattern min-w-0">
            <!-- الهيدر العلوي -->
            <header class="h-16 bg-white border-b border-[#F0D9DF] px-8 flex items-center justify-between sticky top-0 z-40">
                <h2 class="text-xl font-bold font-heading" id="page-title">لوحة المعلومات</h2>

                <!-- فورم تسجيل الخروج (مستقل تماماً وليس داخل أي فورم آخر) -->
                <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 text-red-500 hover:text-red-700 font-bold transition-colors bg-transparent border-0 cursor-pointer">
                        خروج
                        <iconify-icon icon="lucide:log-out" width="20"></iconify-icon>
                    </button>
                </form>
            </header>

            <div class="p-8">

                <!-- رسائل النجاح والأخطاء -->
                @if(session('success'))
                    <div class="bg-[#e6f4ea] text-[#1e8e3e] p-4 rounded-xl mb-6 font-bold border border-[#ceead6]">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-50 text-red-500 p-4 rounded-xl mb-6 border border-red-100">
                        <ul class="list-disc list-inside text-sm font-bold">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- لو فيه خطأ يفتح قسم الإضافة تلقائياً -->
                    <script>
                        window.addEventListener('DOMContentLoaded', () => {
                            const tabButton = document.querySelector(`a[onclick*="add-product-section"]`);
                            if (tabButton) tabButton.click();
                        });
                    </script>
                @endif
                
                <!-- ============================================== -->
                <!-- 1. قسم الرئيسية -->
                <!-- ============================================== -->
                <section id="dashboard-section" class="tab-content block space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-white p-6 rounded-[20px] custom-shadow border border-[#F0D9DF] flex items-center gap-4 hover:scale-[1.02] transition-transform">
                            <div class="w-14 h-14 bg-[#FDEEF0] text-[#C2577A] rounded-full flex items-center justify-center shrink-0"><iconify-icon icon="lucide:shopping-bag" width="28"></iconify-icon></div>
                            <div><p class="text-sm text-[#8A6E78]">إجمالي الطلبات</p><p class="text-2xl font-bold">{{ isset($orders) ? $orders->count() : 0 }}</p></div>
                        </div>
                        <div class="bg-white p-6 rounded-[20px] custom-shadow border border-[#F0D9DF] flex items-center gap-4 hover:scale-[1.02] transition-transform">
                            <div class="w-14 h-14 bg-[#EDE7F8] text-[#9B87C9] rounded-full flex items-center justify-center shrink-0"><iconify-icon icon="lucide:dollar-sign" width="28"></iconify-icon></div>
                            <div><p class="text-sm text-[#8A6E78]">المبيعات التقريبية</p><p class="text-2xl font-bold">{{ isset($orders) ? number_format($orders->sum('subtotal')) : 0 }} ج.م</p></div>
                        </div>
                        <div class="bg-white p-6 rounded-[20px] custom-shadow border border-[#F0D9DF] flex items-center gap-4 hover:scale-[1.02] transition-transform">
                            <div class="w-14 h-14 bg-[#FFF3E0] text-[#C9A36B] rounded-full flex items-center justify-center shrink-0"><iconify-icon icon="lucide:shirt" width="28"></iconify-icon></div>
                            <div><p class="text-sm text-[#8A6E78]">عدد الموديلات</p><p class="text-2xl font-bold">{{ isset($products) ? $products->count() : 0 }}</p></div>
                        </div>
                    </div>
                </section>

                <!-- ============================================== -->
                <!-- 2. قسم الطلبيات -->
                <!-- ============================================== -->
                <section id="orders-section" class="tab-content hidden">
                    <div class="bg-white rounded-[20px] custom-shadow border border-[#F0D9DF] overflow-hidden">
                        <div class="p-6 border-b border-[#F0D9DF]">
                            <h3 class="text-lg font-bold font-heading">الطلبيات الأخيرة</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-right whitespace-nowrap">
                                <thead>
                                    <tr class="bg-[#FFF7F5] text-[#8A6E78] text-xs font-bold border-b border-[#F0D9DF]">
                                        <th class="px-6 py-4">رقم الطلب</th>
                                        <th class="px-6 py-4">سيريال الطلب</th>
                                        <th class="px-6 py-4">العميل</th>
                                        <th class="px-6 py-4">التاريخ</th>
                                        <th class="px-6 py-4">الإجمالي</th>
                                        <th class="px-6 py-4">الحالة</th>
                                        <th class="px-6 py-4">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#F0D9DF]">
                                    @if(isset($orders) && $orders->count() > 0)
                                        @foreach($orders as $order)
                                        <tr class="hover:bg-[#FFF7F5] transition-colors">
                                            <td class="px-6 py-4 font-mono text-[#C2577A] text-sm">#{{ $order->id }}</td>
                                            <td class="px-6 py-4 font-mono text-[#C2577A] text-sm">#{{ $order->order_serial }}</td>
                                            <td class="px-6 py-4 font-bold text-sm">{{ $order->customer_name }}</td>
                                            <td class="px-6 py-4 text-xs text-[#8A6E78]">{{ $order->created_at->format('Y/m/d') }}</td>
                                            <td class="px-6 py-4 font-bold text-[#C9A36B]">{{ number_format($order->total_amount, 0) }} ج.م</td>
                                            <td class="px-6 py-4"><span class="status-badge bg-[#EDE7F8] text-[#9B87C9]">{{ $order->status }}</span></td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-2">
                                                    <!-- زر عرض التفاصيل -->
                                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="p-2 hover:bg-[#FDEEF0] rounded-full text-[#C2577A] inline-block transition-all" title="عرض التفاصيل">
                                                        <iconify-icon icon="lucide:eye" width="18"></iconify-icon>
                                                    </a>

                                                    <!-- فورم تغيير الحالة السريع -->
                                                    <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="m-0">
                                                        @csrf
                                                        @method('PATCH')
                                                        <select name="status" onchange="this.form.submit()" class="text-xs border border-[#F0D9DF] rounded-lg px-2 py-1.5 bg-white text-[#3B2530] focus:border-[#C2577A] outline-none cursor-pointer w-28">
                                                            <option value="new" {{ $order->status == 'new' ? 'selected' : '' }}>جديد</option>
                                                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>تحت المعالجة</option>
                                                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>تم الشحن</option>
                                                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>مكتمل</option>
                                                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                                                        </select>
                                                    </form>

                                                    <!-- زر الحذف (السلة) -->
                                                    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="m-0" onsubmit="return confirm('هل أنت متأكد من حذف هذا الطلب نهائياً؟');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-2 hover:bg-red-50 rounded-full text-red-500 transition-all flex items-center justify-center" title="حذف الطلب">
                                                            <iconify-icon icon="lucide:trash-2" width="18"></iconify-icon>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr><td colspan="7" class="text-center py-8 text-[#8A6E78]">لا توجد طلبيات حتى الآن.</td></tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <!-- ============================================== -->
                <!-- 3. قسم إضافة موديل جديد -->
                <!-- ============================================== -->
                <section id="add-product-section" class="tab-content hidden">
    <div class="bg-white rounded-[20px] custom-shadow border border-[#F0D9DF] p-8">
        <h3 class="text-lg font-bold font-heading mb-6 border-b border-[#F0D9DF] pb-4">إضافة موديل جديد</h3>
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="text-xs font-bold text-[#8A6E78] mb-2 block">اسم الموديل</label>
                    <input type="text" name="name" required placeholder="مثال: عباية ملكي كريب">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-[#8A6E78] mb-2 block">كود الموديل</label>
                        <input type="text" name="code" required placeholder="مثال: 909">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-[#8A6E78] mb-2 block">السعر (ج.م)</label>
                        <input type="number" name="price" required placeholder="950">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-[#8A6E78] mb-2 block">التصنيف</label>
                        <select name="department_id" required>
                            <option value="" disabled selected>اختاري التصنيف...</option>
                            @if(isset($departments))
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-[#8A6E78] mb-2 block">نوع الخامة</label>
                        <input type="text" name="material_type" placeholder="قطن">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold text-[#8A6E78] mb-2 block">الوصف</label>
                    <textarea name="description" rows="4" placeholder="اكتبي وصف الموديل هنا..."></textarea>
                </div>
                @include('admin.products._variants-picker', ['colors' => $colors, 'sizes' => $sizes])
                <!-- قسم الألوان والمقاسات والكميات -->
            </div>
            
            <div class="space-y-6">
                <div class="bg-[#FFF7F5] p-5 rounded-[20px] border border-[#F0D9DF]">
                    <h4 class="text-sm font-bold text-[#C2577A] mb-2 flex items-center gap-2"><iconify-icon icon="lucide:image"></iconify-icon> الصورة الرئيسية</h4>
                    <input type="file" name="primary_image" id="primary_image" accept="image/*" required class="hidden" onchange="previewImage(this, 'primary-preview')">
                    <label for="primary_image" class="cursor-pointer block w-full aspect-[4/3] bg-white border-2 border-dashed border-[#C2577A]/50 rounded-[14px] flex flex-col items-center justify-center hover:bg-[#FDEEF0] transition-colors relative overflow-hidden">
                        <div id="primary-preview-text" class="flex flex-col items-center text-[#C2577A]">
                            <iconify-icon icon="lucide:upload-cloud" width="32"></iconify-icon><span class="text-xs font-bold mt-2">اختيار صورة الهيد</span>
                        </div>
                        <img id="primary-preview" class="absolute inset-0 w-full h-full object-cover hidden">
                    </label>
                </div>
                <div class="bg-[#FFF7F5] p-5 rounded-[20px] border border-[#F0D9DF]">
                    <h4 class="text-sm font-bold text-[#3B2530] mb-2 flex items-center gap-2"><iconify-icon icon="lucide:images"></iconify-icon> صور إضافية</h4>
                    <input type="file" name="additional_images[]" id="additional_images" accept="image/*" multiple class="hidden" onchange="updateFilesCount(this)">
                    <label for="additional_images" class="cursor-pointer block w-full py-8 bg-white border-2 border-dashed border-[#F0D9DF] rounded-[14px] flex flex-col items-center justify-center hover:bg-[#FDEEF0] transition-colors">
                        <iconify-icon icon="lucide:plus-circle" width="28" class="text-[#8A6E78]"></iconify-icon>
                        <span class="text-xs font-bold mt-2 text-[#8A6E78]" id="additional-files-text">تحديد صور إضافية (0)</span>
                    </label>
                </div>
                <div class="flex justify-end gap-4 mt-8 pt-4 border-t border-[#F0D9DF]">
                    <button type="submit" class="px-10 py-3 bg-[#C2577A] text-white rounded-[14px] font-bold shadow-lg shadow-[#C2577A]/20 hover:bg-[#A9456A]">حفظ ونشر الموديل</button>
                </div>
            </div>
        </form>
    </div>
</section>

                <!-- ============================================== -->
                <!-- 3.1 قسم الموديلات (عرض / تعديل / حذف) -->
                <!-- ============================================== -->
                <section id="products-section" class="tab-content hidden">
                    <div class="bg-white rounded-[20px] custom-shadow border border-[#F0D9DF] overflow-hidden">
                        <div class="p-6 border-b border-[#F0D9DF] flex items-center justify-between gap-4 flex-wrap">
                            <h3 class="text-lg font-bold font-heading">
                                الموديلات
                                <span class="text-sm text-[#8A6E78] font-normal">({{ isset($products) ? $products->count() : 0 }})</span>
                            </h3>
                            <div class="relative w-full sm:w-72">
                                <input type="text" id="products-search" onkeyup="filterProducts()" placeholder="ابحثي بالاسم أو الكود..." class="!py-2 !text-sm !pr-10">
                                <iconify-icon icon="lucide:search" width="18" class="absolute right-3 top-1/2 -translate-y-1/2 text-[#8A6E78] pointer-events-none"></iconify-icon>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-right whitespace-nowrap" id="products-table">
                                <thead>
                                    <tr class="bg-[#FFF7F5] text-[#8A6E78] text-xs font-bold border-b border-[#F0D9DF]">
                                        <!-- <th class="px-6 py-4">الصورة</th> -->
                                        <th class="px-6 py-4">اسم الموديل</th>
                                        <th class="px-6 py-4">الكود</th>
                                        <th class="px-6 py-4">مفعل-غير مفعل</th>
                                        <th class="px-6 py-4">القسم</th>
                                        <th class="px-6 py-4">الخامة</th>
                                        <th class="px-6 py-4">السعر</th>
                                        <th class="px-6 py-4">الألوان</th>
                                        <th class="px-6 py-4">المخزون</th>
                                        <th class="px-6 py-4">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#F0D9DF]">
                                    @if(isset($products) && $products->count() > 0)
                                        @foreach($products as $product)
                                        @php
                                            $totalStock = $product->variances->sum('stock');
                                        @endphp
                                        <tr class="product-row hover:bg-[#FFF7F5] transition-colors" data-search="{{ $product->name }} {{ $product->code }}">
                                            <!-- <td class="px-6 py-3">
                                                <div class="w-14 h-[74px] rounded-xl overflow-hidden bg-[#FDEEF0] border border-[#F0D9DF]">
                                                    @if($product->primaryImage)
                                                        <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" alt="{{ $product->name }}" loading="lazy" class="w-full h-full object-cover object-top">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center text-[#C2577A]/50">
                                                            <iconify-icon icon="lucide:image-off" width="22"></iconify-icon>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td> -->
                                            <td class="px-6 py-3 font-bold text-sm max-w-[220px] truncate" title="{{ $product->name }}">{{ $product->name }}</td>
                                            <td class="px-6 py-3"><span class="font-mono text-[#C2577A] text-sm">#{{ $product->code }}</span></td>
<td class="px-6 py-3">
    @if($product->is_active)
        <span class="bg-green-100 text-green-700 px-2.5 py-1 rounded-full text-xs font-bold">مفعل</span>
    @else
        <span class="bg-red-100 text-red-700 px-2.5 py-1 rounded-full text-xs font-bold">غير مفعل</span>
    @endif
</td>                                            <td class="px-6 py-3 text-sm text-[#8A6E78]">{{ $product->department->name ?? '—' }}</td>
                                            <td class="px-6 py-3 text-sm text-[#8A6E78]">{{ $product->material_type ?: 'x' }}</td>
                                            <td class="px-6 py-3 font-bold text-[#C9A36B]">{{ number_format($product->price, 0) }} ج.م</td>
                                            <td class="px-6 py-3">
                                                <div class="flex gap-1 flex-wrap max-w-[120px]">
                                                    @forelse($product->variances->unique('color_id') as $variant)
                                                        @if($variant->color)
                                                            <span class="w-4 h-4 rounded-full border border-black/10" style="background-color: {{ $variant->color->hex_code }}" title="{{ $variant->color->name }}"></span>
                                                        @endif
                                                    @empty
                                                        <span class="text-xs text-[#8A6E78]">—</span>
                                                    @endforelse
                                                </div>
                                            </td>
                                            <td class="px-6 py-3">
                                                @if($totalStock > 5)
                                                    <span class="status-badge bg-[#e6f4ea] text-[#1e8e3e]">{{ $totalStock }} قطعة</span>
                                                @elseif($totalStock > 0)
                                                    <span class="status-badge bg-[#FFF3E0] text-[#C9A36B]">{{ $totalStock }} قطعة</span>
                                                @else
                                                    <span class="status-badge bg-red-50 text-red-500">نفذ</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-3">
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="p-2 hover:bg-[#EDE7F8] rounded-full text-[#9B87C9] inline-flex items-center justify-center transition-all" title="تعديل">
                                                        <iconify-icon icon="lucide:pencil" width="18"></iconify-icon>
                                                    </a>
                                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="m-0" onsubmit="return confirm('هل أنت متأكد من حذف هذا الموديل نهائياً؟');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-2 hover:bg-red-50 rounded-full text-red-500 transition-all flex items-center justify-center" title="حذف">
                                                            <iconify-icon icon="lucide:trash-2" width="18"></iconify-icon>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                        <tr id="products-no-result" class="hidden"><td colspan="9" class="text-center py-8 text-[#8A6E78]">مفيش موديل بالاسم أو الكود ده.</td></tr>
                                    @else
                                        <tr><td colspan="9" class="text-center py-8 text-[#8A6E78]">لا توجد موديلات حتى الآن.</td></tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <!-- ============================================== -->
                <!-- 4. قسم الأقسام (Departments) -->
                <!-- ============================================== -->
                <section id="departments-section" class="tab-content hidden space-y-6">
                    <!-- فورم إضافة قسم -->
                    <div class="bg-white rounded-[20px] custom-shadow border border-[#F0D9DF] p-6">
                        <h3 class="text-lg font-bold font-heading mb-4 border-b border-[#F0D9DF] pb-4">إضافة قسم جديد</h3>
                        <form action="{{ route('admin.departments.store') }}" method="POST" class="flex gap-4 items-end">
                            @csrf
                            <div class="flex-1">
                                <label class="text-xs font-bold text-[#8A6E78] mb-2 block">اسم القسم</label>
                                <input type="text" name="name" required placeholder="مثال: عبايات صيفي">
                            </div>
                            <button type="submit" class="px-8 py-3 h-[46px] bg-[#C2577A] text-white rounded-[14px] font-bold shadow-md hover:bg-[#A9456A] shrink-0">إضافة</button>
                        </form>
                    </div>
                    <!-- جدول الأقسام -->
                    <div class="bg-white rounded-[20px] custom-shadow border border-[#F0D9DF] overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-right">
                                <thead>
                                    <tr class="bg-[#FFF7F5] text-[#8A6E78] text-xs font-bold border-b border-[#F0D9DF]">
                                        <th class="px-6 py-4">#</th>
                                        <th class="px-6 py-4">الاسم</th>
                                        <th class="px-6 py-4 w-24">إجراء</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#F0D9DF]">
                                    @if(isset($departments) && $departments->count() > 0)
                                        @foreach($departments as $dept)
                                        <tr class="hover:bg-[#FFF7F5] transition-colors">
                                            <td class="px-6 py-4 text-sm">{{ $dept->id }}</td>
                                            <td class="px-6 py-4 font-bold text-sm">{{ $dept->name }}</td>
                                            <td class="px-6 py-4">
                                                <form action="{{ route('admin.departments.destroy', $dept->id) }}" method="POST" onsubmit="return confirm('تأكيد الحذف؟')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-500 p-2 hover:bg-red-50 rounded-full"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr><td colspan="3" class="text-center py-6 text-[#8A6E78]">لا توجد أقسام.</td></tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <!-- ============================================== -->
                <!-- 5. قسم المحافظات والشحن (Governorates) -->
                <!-- ============================================== -->
                <section id="governorates-section" class="tab-content hidden space-y-6">
                    <div class="bg-white rounded-[20px] custom-shadow border border-[#F0D9DF] p-6">
                        <h3 class="text-lg font-bold font-heading mb-4 border-b border-[#F0D9DF] pb-4">إضافة محافظة</h3>
                        <form action="{{ route('admin.governorates.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            @csrf
                            <div class="md:col-span-2">
                                <label class="text-xs font-bold text-[#8A6E78] mb-2 block">اسم المحافظة</label>
                                <input type="text" name="name" required placeholder="مثال: الإسكندرية">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-[#8A6E78] mb-2 block">تكلفة الشحن (ج.م)</label>
                                <input type="number" name="shipping_cost" required placeholder="50">
                            </div>
                            <div class="md:col-span-3 flex justify-end">
                                <button type="submit" class="px-8 py-3 bg-[#C2577A] text-white rounded-[14px] font-bold shadow-md hover:bg-[#A9456A]">إضافة وحفظ</button>
                            </div>
                        </form>
                    </div>
                    <div class="bg-white rounded-[20px] custom-shadow border border-[#F0D9DF] overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-right">
                                <thead>
                                    <tr class="bg-[#FFF7F5] text-[#8A6E78] text-xs font-bold border-b border-[#F0D9DF]">
                                        <th class="px-6 py-4">#</th>
                                        <th class="px-6 py-4">المحافظة</th>
                                        <th class="px-6 py-4">تكلفة الشحن</th>
                                        <th class="px-6 py-4 w-24">إجراء</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#F0D9DF]">
                                    @if(isset($governorates) && $governorates->count() > 0)
                                        @foreach($governorates as $gov)
                                        <tr class="hover:bg-[#FFF7F5] transition-colors">
                                            <td class="px-6 py-4 text-sm">{{ $gov->id }}</td>
                                            <td class="px-6 py-4 font-bold text-sm">{{ $gov->name }}</td>
                                            <td class="px-6 py-4 text-[#C9A36B] font-bold">{{ $gov->shipping_cost }} ج.م</td>
                                            <td class="px-6 py-4">
                                                <form action="{{ route('admin.governorates.destroy', $gov->id) }}" method="POST" onsubmit="return confirm('تأكيد الحذف؟')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-500 p-2 hover:bg-red-50 rounded-full"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr><td colspan="4" class="text-center py-6 text-[#8A6E78]">لا توجد محافظات.</td></tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <!-- ============================================== -->
                <!-- 6. قسم الألوان (Colors) -->
                <!-- ============================================== -->
                <section id="colors-section" class="tab-content hidden space-y-6">
                    <div class="bg-white rounded-[20px] custom-shadow border border-[#F0D9DF] p-6">
                        <h3 class="text-lg font-bold font-heading mb-4 border-b border-[#F0D9DF] pb-4">إضافة لون جديد</h3>
                        <form action="{{ route('admin.colors.store') }}" method="POST" class="flex flex-wrap md:flex-nowrap gap-4 items-end">
                            @csrf
                            <div class="flex-1 min-w-[200px]">
                                <label class="text-xs font-bold text-[#8A6E78] mb-2 block">اسم اللون (بالعربي)</label>
                                <input type="text" name="name" required placeholder="مثال: أسود ملكي">
                            </div>
                            <div class="w-32">
                                <label class="text-xs font-bold text-[#8A6E78] mb-2 block">درجة اللون</label>
                                <input type="color" name="hex_code" required class="h-[46px] w-full p-1 cursor-pointer bg-white">
                            </div>
                            <button type="submit" class="px-8 py-3 h-[46px] bg-[#C2577A] text-white rounded-[14px] font-bold shadow-md hover:bg-[#A9456A]">إضافة اللون</button>
                        </form>
                    </div>
                    <div class="bg-white rounded-[20px] custom-shadow border border-[#F0D9DF] overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-right">
                                <thead>
                                    <tr class="bg-[#FFF7F5] text-[#8A6E78] text-xs font-bold border-b border-[#F0D9DF]">
                                        <th class="px-6 py-4">#</th>
                                        <th class="px-6 py-4">اللون</th>
                                        <th class="px-6 py-4 w-24">إجراء</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#F0D9DF]">
                                    @if(isset($colors) && $colors->count() > 0)
                                        @foreach($colors as $color)
                                        <tr class="hover:bg-[#FFF7F5] transition-colors">
                                            <td class="px-6 py-4 text-sm">{{ $color->id }}</td>
                                            <td class="px-6 py-4 font-bold text-sm">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-6 h-6 rounded-full border border-gray-200" style="background-color: {{ $color->hex_code }}"></div>
                                                    {{ $color->name }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <form action="{{ route('admin.colors.destroy', $color->id) }}" method="POST" onsubmit="return confirm('تأكيد الحذف؟')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-500 p-2 hover:bg-red-50 rounded-full"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr><td colspan="3" class="text-center py-6 text-[#8A6E78]">لا توجد ألوان.</td></tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <!-- ============================================== -->
                <!-- 7. قسم المقاسات (Sizes) -->
                <!-- ============================================== -->
                <section id="sizes-section" class="tab-content hidden space-y-6">
                    <div class="bg-white rounded-[20px] custom-shadow border border-[#F0D9DF] p-6">
                        <h3 class="text-lg font-bold font-heading mb-4 border-b border-[#F0D9DF] pb-4">إضافة مقاس جديد</h3>
                        <form action="{{ route('admin.sizes.store') }}" method="POST" class="flex gap-4 items-end">
                            @csrf
                            <div class="flex-1">
                                <label class="text-xs font-bold text-[#8A6E78] mb-2 block">المقاس</label>
                                <input type="text" name="name" required placeholder="مثال: M/L أو One Size">
                            </div>
                            <button type="submit" class="px-8 py-3 h-[46px] bg-[#C2577A] text-white rounded-[14px] font-bold shadow-md hover:bg-[#A9456A]">إضافة مقاس</button>
                        </form>
                    </div>
                    <div class="bg-white rounded-[20px] custom-shadow border border-[#F0D9DF] overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-right">
                                <thead>
                                    <tr class="bg-[#FFF7F5] text-[#8A6E78] text-xs font-bold border-b border-[#F0D9DF]">
                                        <th class="px-6 py-4">#</th>
                                        <th class="px-6 py-4">المقاس</th>
                                        <th class="px-6 py-4 w-24">إجراء</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#F0D9DF]">
                                    @if(isset($sizes) && $sizes->count() > 0)
                                        @foreach($sizes as $size)
                                        <tr class="hover:bg-[#FFF7F5] transition-colors">
                                            <td class="px-6 py-4 text-sm">{{ $size->id }}</td>
                                            <td class="px-6 py-4 font-bold text-sm">{{ $size->name }}</td>
                                            <td class="px-6 py-4">
                                                <form action="{{ route('admin.sizes.destroy', $size->id) }}" method="POST" onsubmit="return confirm('تأكيد الحذف؟')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-500 p-2 hover:bg-red-50 rounded-full"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr><td colspan="3" class="text-center py-6 text-[#8A6E78]">لا توجد مقاسات.</td></tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

            </div>
        </main>
    </div>

    <!-- كود الجافاسكريبت للتبديل بين الأقسام ومعاينة الصور -->
    <script>
        let varianceIndex = {{ $nextIndex }};

    

        function switchTab(tabId, element) {
            // إخفاء كل المحتوى
            document.querySelectorAll('.tab-content').forEach(section => {
                section.classList.add('hidden');
                section.classList.remove('block');
            });
            
            // إظهار المحتوى المطلوب
            document.getElementById(tabId).classList.remove('hidden');
            document.getElementById(tabId).classList.add('block');
            
            // تحديث تصميم الأزرار في القائمة
            document.querySelectorAll('.sidebar-link').forEach(link => {
                link.classList.remove('sidebar-active');
            });
            element.classList.add('sidebar-active');

            // تغيير العنوان في الأعلى
            document.getElementById('page-title').innerText = element.querySelector('span').innerText;
        }

        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const text = document.getElementById('primary-preview-text');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    text.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function updateFilesCount(input) {
            const text = document.getElementById('additional-files-text');
            if (input.files && input.files.length > 0) {
                text.innerText = `تم تحديد (${input.files.length}) صور إضافية`;
                text.classList.add('text-[#C2577A]');
            } else {
                text.innerText = 'تحديد صور إضافية (0)';
                text.classList.remove('text-[#C2577A]');
            }
        }

        // البحث في جدول الموديلات
        function filterProducts() {
            const q = document.getElementById('products-search').value.trim().toLowerCase();
            const rows = document.querySelectorAll('#products-table .product-row');
            let visible = 0;
            rows.forEach(row => {
                const match = row.dataset.search.toLowerCase().includes(q);
                row.classList.toggle('hidden', !match);
                if (match) visible++;
            });
            const empty = document.getElementById('products-no-result');
            if (empty) empty.classList.toggle('hidden', visible > 0);
        }

        window.addEventListener('DOMContentLoaded', () => {
            // فتح القسم المطلوب لو فيه هاش في الرابط (مثال: /admin#products-section)
            const hash = window.location.hash;
            if (hash) {
                const sectionId = hash.substring(1);
                const tabButton = document.querySelector(`a[onclick*="${sectionId}"]`);
                if (tabButton) tabButton.click();
            }
        });
    </script>
</body>
</html>