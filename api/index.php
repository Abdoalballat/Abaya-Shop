<?php

// 1. إنشاء مجلدات التخزين المؤقتة في بيئة Vercel
$storagePath = '/tmp/storage';
if (!file_exists($storagePath)) {
    @mkdir($storagePath . '/framework/views', 0755, true);
    @mkdir($storagePath . '/framework/sessions', 0755, true);
    @mkdir($storagePath . '/framework/cache', 0755, true);
    @mkdir($storagePath . '/bootstrap/cache', 0755, true);
    @mkdir($storagePath . '/logs', 0755, true);
}

// 2. تعيين مسار Storage لبيئة العمل
putenv("APP_STORAGE_PATH={$storagePath}");
$_ENV['APP_STORAGE_PATH'] = $storagePath;
$_SERVER['APP_STORAGE_PATH'] = $storagePath;

// 3. تشغيل ملف التطبيق الرئيسي
require __DIR__ . '/../public/index.php';