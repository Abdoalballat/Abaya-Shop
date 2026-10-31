<?php

// 1. تهيئة مسارات الـ Storage المؤقتة في بيئة Vercel
$storagePath = '/tmp/storage';
if (!file_exists($storagePath)) {
    @mkdir($storagePath . '/framework/views', 0755, true);
    @mkdir($storagePath . '/framework/sessions', 0755, true);
    @mkdir($storagePath . '/framework/cache', 0755, true);
    @mkdir($storagePath . '/bootstrap/cache', 0755, true);
    @mkdir($storagePath . '/logs', 0755, true);
}

// 2. إعادة توجيه متغيرة البيئة لارافيل للتخزين في /tmp
putenv("APP_STORAGE_PATH={$storagePath}");
$_ENV['APP_STORAGE_PATH'] = $storagePath;
$_SERVER['APP_STORAGE_PATH'] = $storagePath;

// 3. استدعاء ملف index الرئيسي لتشغيل لارافيل
require __DIR__ . '/../public/index.php';