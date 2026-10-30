@extends('layouts.app')

@section('title', 'سلة المشتريات والدفع - عبايات المصطفى')

@section('header_left')
    <a href="{{ route('home') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-[#FDEEF0] text-[#C2577A] ml-3 transition-colors hover:bg-[#F0D9DF]">
        <iconify-icon icon="lucide:chevron-right" width="24"></iconify-icon>
    </a>
@endsection

@section('content')
    <section class="bg-[#FDEEF0] border border-[#F0D9DF] rounded-[20px] p-4 flex items-start gap-3 mb-8">
        <iconify-icon icon="lucide:shield-check" class="text-[#C2577A] shrink-0" width="24"></iconify-icon>
        <div>
            <h4 class="font-bold text-sm text-[#C2577A]">تحويل مبلغ قبل التسليم</h4>
            <p class="text-xs mt-1 leading-relaxed text-[#8A6E78]">تأكيد طلبك يتم فقط بعد إرفاق صورة التحويل عبر Vodafone Cash أو InstaPay لضمان جدية الحجز.</p>
        </div>
    </section>

    <!-- الفورم الأساسي المرتبط بالـ Route -->
    <form id="checkout-form" action="{{ route('orders.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <section class="bg-white rounded-[20px] p-5 border border-[#F0D9DF] custom-shadow mb-8">
            <h3 class="text-xl mb-4 font-bold border-b border-[#FDEEF0] pb-2">ملخص السلة</h3>
            
            <!-- حاوية المنتجات الديناميكية -->
            <div id="cart-items-container" class="space-y-5">
                <!-- Items will be injected here via JS -->
            </div>

            <div class="mt-6 pt-4 border-t border-dashed border-[#F0D9DF] space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-[#8A6E78]">المجموع الفرعي</span>
                    <span class="font-medium" id="subtotal-display">0 ج.م</span>
                </div>
                <div class="flex justify-between text-sm items-center">
                    <div class="flex items-center gap-1">
                        <span class="text-[#8A6E78]">الشحن</span>
                    </div>
                    <span class="font-medium" id="shipping-display">سيتم حسابه</span>
                </div>
                <div class="flex justify-between text-lg font-bold text-[#C2577A] pt-2">
                    <span>المبلغ المطلوب تحويله</span>
                    <span class="text-[#C9A36B]" id="total-display">0 ج.م</span>
                </div>
            </div>
        </section>

        <section class="space-y-5 mb-8">
            <h3 class="text-xl font-bold flex items-center gap-2">
                <iconify-icon icon="lucide:user" class="text-[#C2577A]"></iconify-icon> بيانات التوصيل
            </h3>
            <div class="space-y-4">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-[#8A6E78] mr-2">الاسم الكامل</label>
                    <input type="text" name="customer_name" value="{{ old('customer_name') }}" required placeholder="اكتبي اسمك الثلاثي..." class="w-full h-[52px] px-4 rounded-[14px] bg-white border border-[#F0D9DF] focus:outline-none focus:ring-2 focus:ring-[#C2577A]/20 transition-all">
                    @error('customer_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-[#8A6E78] mr-2">رقم الهاتف</label>
                        <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}" required placeholder="01xxxxxxxxx" class="w-full h-[52px] px-4 rounded-[14px] bg-white border border-[#F0D9DF] focus:outline-none focus:ring-2 focus:ring-[#C2577A]/20 text-left" dir="ltr">
                        @error('customer_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-[#8A6E78] mr-2">المحافظة</label>
                        <select name="governorate_id" id="governorate_select" required class="w-full h-[52px] px-4 rounded-[14px] bg-white border border-[#F0D9DF] focus:outline-none focus:ring-2 focus:ring-[#C2577A]/20 appearance-none">
                            <option value="" disabled selected>اختاري المحافظة</option>
                            @isset($governorates)
                                @foreach($governorates as $gov)
                                    <option value="{{ $gov->id }}" data-price="{{ $gov->shipping_cost }}" {{ old('governorate_id') == $gov->id ? 'selected' : '' }}>
                                        {{ $gov->name }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                        @error('governorate_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-[#8A6E78] mr-2">عنوان التفاصيل</label>
                    <textarea name="address_details" required placeholder="رقم المبنى، الشارع، علامة مميزة..." class="w-full p-4 rounded-[14px] bg-white border border-[#F0D9DF] focus:outline-none focus:ring-2 focus:ring-[#C2577A]/20 h-24 resize-none">{{ old('address_details') }}</textarea>
                    @error('address_details') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
        </section>

        <section class="space-y-5 mb-8">
            <h3 class="text-xl font-bold flex items-center gap-2">
                <iconify-icon icon="lucide:credit-card" class="text-[#C2577A]"></iconify-icon> طريقة الدفع
            </h3>
            <div class="space-y-3">
                <div class="relative">
                    <input type="radio" name="pay_method" value="vodafone_cash" id="pay-vodafone" class="hidden peer" checked {{ old('pay_method') == 'vodafone_cash' ? 'checked' : '' }}>
                    <label for="pay-vodafone" class="block p-4 rounded-[20px] border border-[#F0D9DF] bg-white peer-checked:bg-[#EDE7F8] peer-checked:border-[#9B87C9] transition-all cursor-pointer">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-bold">فودافون كاش</span>
                            <iconify-icon icon="lucide:check-circle" class="text-[#9B87C9] hidden peer-checked:block"></iconify-icon>
                        </div>
                        <div class="flex items-center justify-between bg-white/50 p-2 rounded-[10px] border border-[#F0D9DF]/50">
                            <code class="font-mono text-lg text-[#3B2530]" id="vodafone-number">01023456789</code>
                            <button type="button" onclick="copyToClipboard('vodafone-number')" class="text-[10px] bg-[#9B87C9] text-white px-3 py-1.5 rounded-full flex items-center gap-1 hover:bg-[#8670B4] transition-colors">
                                <iconify-icon icon="lucide:copy" width="12"></iconify-icon>نسخ
                            </button>
                        </div>
                    </label>
                </div>
                
                <div class="relative">
                    <input type="radio" name="pay_method" value="instapay" id="pay-instapay" class="hidden peer" {{ old('pay_method') == 'instapay' ? 'checked' : '' }}>
                    <label for="pay-instapay" class="block p-4 rounded-[20px] border border-[#F0D9DF] bg-white peer-checked:bg-[#EDE7F8] peer-checked:border-[#9B87C9] transition-all cursor-pointer">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-bold">إنستا باي (InstaPay)</span>
                            <iconify-icon icon="lucide:check-circle" class="text-[#9B87C9] hidden peer-checked:block"></iconify-icon>
                        </div>
                        <div class="flex items-center justify-between bg-white/50 p-2 rounded-[10px] border border-[#F0D9DF]/50">
                            <code class="font-mono text-lg text-[#3B2530]" id="instapay-number">mustafa@instapay</code>
                             <button type="button" onclick="copyToClipboard('instapay-number')" class="text-[10px] bg-[#9B87C9] text-white px-3 py-1.5 rounded-full flex items-center gap-1 hover:bg-[#8670B4] transition-colors">
                                <iconify-icon icon="lucide:copy" width="12"></iconify-icon>نسخ
                            </button>
                        </div>
                    </label>
                </div>
            </div>
        </section>

        <section class="space-y-4">
            <h3 class="text-xl font-bold">إرفاق إيصال الدفع</h3>
            <div class="relative border-2 border-dashed border-[#F0D9DF] rounded-[20px] bg-white p-8 flex flex-col items-center justify-center text-center gap-3 transition-colors hover:bg-[#FDEEF0]">
                <div id="upload-icon-box" class="w-14 h-14 rounded-full bg-[#FDEEF0] text-[#C2577A] flex items-center justify-center">
                    <iconify-icon icon="lucide:upload-cloud" width="28"></iconify-icon>
                </div>
                <div id="upload-text">
                    <p class="font-bold text-[#3B2530]" id="file-name">اضغطي لرفع صورة الإيصال</p>
                    <p class="text-xs text-[#8A6E78] mt-1">يرجى إرفاق لقطة شاشة واضحة لعملية التحويل</p>
                </div>
                <input type="file" name="payment_photo" id="receipt-upload" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full" required>
            </div>
            @error('payment_photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </section>
        
        <!-- الحقول المخفية للمنتجات سيتم حقنها هنا بواسطة JS -->
        <div id="hidden-inputs-container"></div>
    </form>
@endsection

@section('footer_buttons')
    <button type="submit" form="checkout-form" class="w-full h-[52px] bg-[#C2577A] hover:bg-[#A9456A] text-white rounded-[14px] font-bold text-lg shadow-lg shadow-[#C2577A]/20 transition-all flex items-center justify-center gap-2">
        <span>تأكيد الطلب وإرسال الإيصال</span>
    </button>
    
    <a href="https://wa.me/201023456789" target="_blank" class="w-full h-[52px] bg-[#25D366] hover:bg-[#20bd5c] text-white rounded-[14px] font-bold transition-all flex items-center justify-center gap-2 mt-3">
        <iconify-icon icon="logos:whatsapp-icon" width="20"></iconify-icon>
        <span>إرسال السلة للاستفسار عبر واتساب</span>
    </a>
@endsection

@push('scripts')
<script>
    function copyToClipboard(elementId) {
        var copyText = document.getElementById(elementId).innerText;
        navigator.clipboard.writeText(copyText).then(function() {
            alert('تم نسخ: ' + copyText);
        }, function(err) {
            console.error('Could not copy text: ', err);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('checkout-form');
        const cartItemsContainer = document.getElementById('cart-items-container');
        const subtotalDisplay = document.getElementById('subtotal-display');
        const shippingDisplay = document.getElementById('shipping-display');
        const totalDisplay = document.getElementById('total-display');
        const govSelect = document.getElementById('governorate_select');
        const hiddenInputsContainer = document.getElementById('hidden-inputs-container');
        
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        let subtotal = 0;

        if(cart.length === 0) {
            cartItemsContainer.innerHTML = '<p class="text-center text-sm text-[#8A6E78]">السلة فارغة. يرجى إضافة منتجات أولاً.</p>';
        } else {
            cart.forEach((item, index) => {
                let itemTotal = parseFloat(item.price) * parseInt(item.quantity);
                subtotal += itemTotal;
                
                cartItemsContainer.innerHTML += `
                    <div class="flex gap-4">
                        <div class="w-20 h-24 rounded-[14px] overflow-hidden shrink-0 relative bg-gray-100">
                            <img src="${item.image_url}" alt="${item.name}" class="w-full h-full object-cover">
                            <span class="absolute top-1 right-1 bg-white/90 text-[10px] px-2 py-0.5 rounded-full text-[#C2577A] font-bold border border-[#F0D9DF]">كود ${item.code}</span>
                        </div>
                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <h4 class="font-bold">${item.name}</h4>
                                <p class="text-xs text-[#8A6E78] mt-1">لون: ${item.color} • مقاس: ${item.size}</p>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-[#8A6E78]">الكمية: ${item.quantity}</span>
                                <span class="font-bold text-[#C9A36B]">${itemTotal} ج.م</span>
                            </div>
                        </div>
                    </div>
                `;
            });
        }

        subtotalDisplay.innerText = subtotal + ' ج.م';

        function updateTotals() {
            let shippingCost = 0;
            if(govSelect.options[govSelect.selectedIndex].value !== "") {
                shippingCost = parseFloat(govSelect.options[govSelect.selectedIndex].getAttribute('data-price') || 0);
                shippingDisplay.innerText = shippingCost + ' ج.م';
            }
            totalDisplay.innerText = (subtotal + shippingCost) + ' ج.م';
        }

        govSelect.addEventListener('change', updateTotals);
        if(govSelect.value) updateTotals(); 

        form.addEventListener('submit', function(e) {
            if(cart.length === 0) {
                e.preventDefault();
                alert("السلة فارغة، لا يمكن إتمام الطلب.");
                return;
            }

            hiddenInputsContainer.innerHTML = ''; 

            cart.forEach((item, index) => {
                hiddenInputsContainer.innerHTML += `
                    <input type="hidden" name="items[${index}][product_variance_id]" value="${item.product_variance_id}">
                    <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
                `;
            });
        });

        const fileInput = document.getElementById('receipt-upload');
        const fileNameDisplay = document.getElementById('file-name');
        fileInput.addEventListener('change', function() {
            if(this.files && this.files.length > 0) {
                fileNameDisplay.innerText = "تم إرفاق: " + this.files[0].name;
                fileNameDisplay.classList.add('text-[#C2577A]');
            } else {
                 fileNameDisplay.innerText = "اضغطي لرفع صورة الإيصال";
                 fileNameDisplay.classList.remove('text-[#C2577A]');
            }
        });
    });
</script>
@endpush