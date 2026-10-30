<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'عبايات المصطفى')</title>
    
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Aref+Ruqaa:wght@400;700&family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        :root {
            --bg-page: #FFF7F5;
            --bg-soft: #FDEEF0;
            --surface: #FFFFFF;
            --primary: #C2577A;
            --primary-hover: #A9456A;
            --primary-soft: #F7D6DF;
            --secondary: #9B87C9;
            --secondary-soft: #EDE7F8;
            --accent-gold: #C9A36B;
            --text-main: #3B2530;
            --text-muted: #8A6E78;
            --border-rose: #F0D9DF;
            --whatsapp: #25D366;
            --shadow-rose: 0 10px 30px -10px rgba(194, 87, 122, 0.15);
        }
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: var(--bg-page);
            color: var(--text-main);
            margin: 0;
            padding: 0;
        }
        h1, h2, h3, h4 { font-family: 'Aref Ruqaa', serif; }
        
        .floral-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 0c2.5 5 7.5 5 10 0s5-7.5 0-10-7.5-5-10 0-7.5 5-10 0-5 7.5 0 10 7.5 5 10 0z' fill='%23C2577A' fill-opacity='0.04' fill-rule='evenodd'/%3E%3C/svg%3E");
        }
        .custom-shadow { box-shadow: var(--shadow-rose); }
        ::-webkit-scrollbar { display: none; }
        * { scrollbar-width: none; }
    </style>
    @stack('styles')
</head>
<body class="floral-pattern flex flex-col min-h-screen">
    
    @if(!isset($hideNavbar))
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-[#F0D9DF] px-5 pt-14 pb-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            @yield('header_left')
            <a href="{{ route('home') }}" class="text-2xl font-bold text-[#C2577A] hover:text-[#A9456A] transition-colors">عبايات المصطفى</a>
        </div>
        
        <div class="flex items-center gap-4">
            @auth
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-[#8A6E78]">{{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs text-[#C2577A] hover:text-[#A9456A]">تسجيل الخروج</button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-sm font-medium text-[#C2577A] hover:text-[#A9456A]">تسجيل الدخول</a>
            @endauth

            <div class="relative">
                <a href="{{ route('cart') }}">
                    <iconify-icon icon="lucide:shopping-bag" width="24" class="text-[#C2577A]"></iconify-icon>
                    <span id="global-cart-badge" class="absolute -top-2 -right-2 bg-[#C2577A] text-white text-[10px] w-5 h-5 flex items-center justify-center rounded-full border-2 border-white">0</span>
                </a>
            </div>
        </div>
    </header>
    @endif

    <main class="flex-1 px-5 py-6 pb-32">
        @yield('content')
    </main>

    @if(!isset($hideFooter))
    <footer class="fixed bottom-0 left-0 right-0 z-[60] bg-white border-t border-[#F0D9DF] p-4 pb-[34px] custom-shadow">
        <div class="max-w-md mx-auto space-y-3">
            @yield('footer_buttons')
        </div>
    </footer>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            let badge = document.getElementById('global-cart-badge');
            if(badge) {
                badge.innerText = cart.length;
            }
        });
    </script>
    @stack('scripts')
</body>
</html>