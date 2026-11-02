<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الطلب #{{ $order->id }}</title>
    
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Aref+Ruqaa:wght@400;700&family=Tajawal:wght@300;400;500;700&display=swap');
        body { font-family: 'Tajawal', sans-serif; background-color: #FFF7F5; color: #3B2530; }
        .font-heading { font-family: 'Aref Ruqaa', serif; }
    </style>
</head>
<body class="pb-10">

    <!-- شريط علوي مع زر الرجوع -->
    <header class="bg-white border-b border-[#F0D9DF] p-6 sticky top-0 z-50 shadow-sm flex items-center gap-4">
        <a href="{{ (route('admin.dashboard'). '#orders-section') }}" class="w-10 h-10 flex items-center justify-center bg-[#FDEEF0] text-[#C2577A] rounded-full hover:scale-105 transition-transform" title="رجوع للوحة التحكم">
            <iconify-icon icon="lucide:arrow-right" width="24"></iconify-icon>
        </a>
        <h1 class="text-2xl font-bold font-heading text-[#C2577A]">تفاصيل الطلب #{{ $order->id }}</h1>
        <span class="mr-auto bg-[#EDE7F8] text-[#9B87C9] px-4 py-1.5 rounded-full text-sm font-bold border border-[#9B87C9]/30">
            الحالة: {{ $order->status }}
        </span>
    </header>

    <main class="max-w-5xl mx-auto mt-8 px-6 grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <!-- العمود الأيمن: بيانات العميل والشحن -->
        <div class="md:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-[20px] shadow-sm border border-[#F0D9DF]">
                <h3 class="text-lg font-bold text-[#C2577A] mb-4 flex items-center gap-2">
                    <iconify-icon icon="lucide:user"></iconify-icon> بيانات العميلة
                </h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] font-bold text-[#8A6E78] uppercase">الاسم</p>
                        <p class="font-bold text-sm">{{ $order->customer_name ?? $order->name }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-[#8A6E78] uppercase">رقم الهاتف</p>
                        <p class="font-medium text-sm" dir="ltr">{{ $order->customer_phone }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-[#8A6E78] uppercase">البريد الإلكتروني</p>
                        <p class="font-medium text-sm" dir="ltr">{{ $order->email ?? 'غير متوفر' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-[20px] shadow-sm border border-[#F0D9DF]">
                <h3 class="text-lg font-bold text-[#3B2530] mb-4 flex items-center gap-2">
                    <iconify-icon icon="lucide:map-pin"></iconify-icon> عنوان الشحن
                </h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] font-bold text-[#8A6E78] uppercase">المحافظة</p>
                        <p class="font-bold text-sm">{{ $order->governorate->name ?? 'غير محدد' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-[#8A6E78] uppercase">العنوان بالتفصيل</p>
                        <p class="text-sm leading-relaxed text-[#8A6E78]">{{ $order->address_details }}</p>
                    </div>
                    @if($order->notes)
                    <div class="pt-4 border-t border-[#F0D9DF]">
                        <p class="text-[10px] font-bold text-[#C2577A] uppercase">ملاحظات الطلب</p>
                        <p class="text-sm leading-relaxed">{{ $order->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- العمود الأيسر: المنتجات والإجمالي -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-[20px] shadow-sm border border-[#F0D9DF] overflow-hidden">
                <div class="p-6 border-b border-[#F0D9DF]">
                    <h3 class="text-lg font-bold text-[#3B2530] flex items-center gap-2">
                        <iconify-icon icon="lucide:shopping-bag"></iconify-icon> محتويات الطلب
                    </h3>
                </div>
                <!-- العمود الأيسر: المنتجات والإجمالي -->
        <div class="md:col-span-2 space-y-6">
            
            <!-- كارت محتويات الطلب والفاتورة (الكود القديم موجود هنا) -->
            <div class="bg-white rounded-[20px] shadow-sm border border-[#F0D9DF] overflow-hidden">
                <!-- ... محتويات الطلب والإجمالي ... -->
            </div>

            <!-- المكان الجديد لكارت الدفع -->
            <div class="bg-white rounded-[20px] shadow-sm border border-[#F0D9DF] overflow-hidden flex flex-col md:flex-row">
                <!-- طريقة الدفع -->
                <div class="p-6 flex-1 border-b md:border-b-0 md:border-l border-[#F0D9DF]">
                    <h3 class="text-lg font-bold text-[#C2577A] mb-4 flex items-center gap-2">
                        <iconify-icon icon="lucide:credit-card"></iconify-icon> تفاصيل الدفع
                    </h3>
                    <div>
                        <p class="text-[10px] font-bold text-[#8A6E78] uppercase">طريقة الدفع</p>
                        <p class="font-bold text-xl mt-2 text-[#3B2530]">
                            {{ $order->pay_method ?? 'غير محدد' }}
                        </p>
                    </div>
                </div>
                
                <!-- صورة الإيصال (إن وجدت) -->
                @if($order->payment_photo)
                <div class="p-6 flex-1 flex flex-col justify-center items-center bg-[#FFF7F5]">
                    <p class="text-[10px] font-bold text-[#8A6E78] uppercase mb-3 text-center">إيصال التحويل (اضغط للتكبير)</p>
                    
                    <!-- الصورة المصغرة التي تفتح النافذة -->
                    <div onclick="openReceiptModal('{{ $order->payment_photo }}')" class="relative group cursor-pointer rounded-xl overflow-hidden border-2 border-[#F0D9DF] hover:border-[#C2577A] transition-all w-32 h-40 shadow-sm hover:shadow-md">
                        <img src="{{ $order->payment_photo }}" alt="إيصال الدفع" class="w-full h-full object-cover">
                        <!-- تأثير عند الوقوف بالماوس -->
                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <iconify-icon icon="lucide:zoom-in" class="text-white" width="32"></iconify-icon>
                        </div>
                    </div>
                </div>
                @endif
            </div>

        </div>
                <div class="p-6 space-y-4">
                    @if(isset($order->items) && $order->items->count() > 0)
                        @foreach($order->items as $item)
                        <div class="flex items-center gap-4 bg-[#FFF7F5] p-4 rounded-[14px] border border-[#F0D9DF]">
                            <!-- لو عندك صورة للمنتج -->
                            <div class="w-16 h-20 bg-white rounded-lg border border-[#F0D9DF] flex-shrink-0 overflow-hidden">
                                <img src="https://placehold.co/100x120/FDEEF0/C2577A?text=Abaya" class="w-full h-full object-cover">
                            </div>
                            
                            <div class="flex-1">
                                <h4 class="font-bold text-sm mb-1">{{ $item->product_name ?? 'اسم المنتج' }}  ||  {{ $item->product->code }}</h4>
                                <p class="text-xs text-[#8A6E78]">
                                    اللون: <span class="font-bold text-[#3B2530]">{{ $item->color ?? 'الأساسي' }}</span> | 
                                    المقاس: <span class="font-bold text-[#3B2530]">{{ $item->size ?? 'الأساسي' }}</span>
                                </p>
                                <p class="text-xs text-[#8A6E78] mt-1">الكمية: {{ $item->quantity }} × {{ number_format($item->unit_price, 0) }} ج.م</p>
                            </div>
                            
                            <div class="text-left">
                                <p class="font-bold text-[#C9A36B]">{{ number_format($item->quantity * $item->unit_price, 0) }} ج.م</p>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-center text-[#8A6E78] py-4">لا توجد منتجات مسجلة في هذا الطلب.</p>
                    @endif
                </div>

                <!-- الفاتورة / الإجمالي -->
                <div class="bg-[#FFF7F5] p-6 border-t border-[#F0D9DF]">
                    <div class="space-y-3 max-w-sm mr-auto text-sm">
                        <div class="flex justify-between text-[#8A6E78]">
                            <span>المجموع الفرعي:</span>
                            <span class="font-bold text-[#3B2530]">{{ number_format($order->subtotal ?? $order->total_amount, 0) }} ج.م</span>
                        </div>
                        <div class="flex justify-between text-[#8A6E78]">
                            <span>تكلفة الشحن:</span>
                            <span class="font-bold text-[#3B2530]">{{ number_format($order->shipping_cost ?? 0, 0) }} ج.م</span>
                        </div>
                        <div class="border-t border-[#F0D9DF] pt-3 mt-3 flex justify-between items-center">
                            <span class="font-bold text-[#C2577A] text-base">الإجمالي النهائي:</span>
                            <span class="font-bold text-[#C9A36B] text-xl">{{ number_format($order->total_amount, 0) }} ج.م</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
    <!-- نافذة تكبير إيصال الدفع (Modal) -->
    <div id="receiptModal" class="fixed inset-0 bg-black/90 z-[100] hidden flex items-center justify-center p-4 opacity-0 transition-opacity duration-300">
        <!-- زر الإغلاق -->
        <button onclick="closeReceiptModal()" class="absolute top-6 right-6 text-white bg-white/20 hover:bg-red-500 rounded-full p-2 backdrop-blur-sm transition-colors">
            <iconify-icon icon="lucide:x" width="28"></iconify-icon>
        </button>
        
        <!-- الصورة المكبرة -->
        <img id="modalReceiptImage" src="" class="max-w-full max-h-[90vh] rounded-xl shadow-2xl object-contain scale-95 transition-transform duration-300">
    </div>

    <!-- أكواد التحكم في النافذة -->
    <script>
        function openReceiptModal(imageSrc) {
            const modal = document.getElementById('receiptModal');
            const modalImg = document.getElementById('modalReceiptImage');
            
            // وضع رابط الصورة في النافذة
            modalImg.src = imageSrc;
            
            // إظهار النافذة
            modal.classList.remove('hidden');
            
            // تشغيل حركة الظهور الناعمة (Animation)
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalImg.classList.remove('scale-95');
                modalImg.classList.add('scale-100');
            }, 10);
        }

        function closeReceiptModal() {
            const modal = document.getElementById('receiptModal');
            const modalImg = document.getElementById('modalReceiptImage');
            
            // تشغيل حركة الاختفاء
            modal.classList.add('opacity-0');
            modalImg.classList.remove('scale-100');
            modalImg.classList.add('scale-95');
            
            // إخفاء النافذة بعد انتهاء الحركة
            setTimeout(() => {
                modal.classList.add('hidden');
                modalImg.src = '';
            }, 300);
        }

        // إغلاق النافذة لو الأدمن ضغط في أي مكان بره الصورة
        document.getElementById('receiptModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeReceiptModal();
            }
        });
    </script>
</body>
</html>