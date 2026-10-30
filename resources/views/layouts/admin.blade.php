<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة التحكم - عبايات المصطفى')</title>
    
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
        }
        .font-heading { font-family: 'Aref Ruqaa', serif; }
        .custom-shadow { box-shadow: var(--shadow-rose); }
        .sidebar-active {
            background-color: var(--primary-soft);
            color: var(--primary);
            border-radius: 14px;
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .floral-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 0c2.5 5 7.5 5 10 0s5-7.5 0-10-7.5-5-10 0-7.5 5-10 0-5 7.5 0 10 7.5 5 10 0z' fill='%23C2577A' fill-opacity='0.04' fill-rule='evenodd'/%3E%3C/svg%3E");
        }
        input, select, textarea {
            border: 1px solid var(--border-rose);
            border-radius: 14px;
            padding: 10px 16px;
            outline: none;
            transition: all 0.2s;
        }
        input:focus, select:focus, textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(194, 87, 122, 0.1);
        }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border-rose); border-radius: 10px; }
    </style>
    @stack('styles')
</head>
<body