<?php

// إنشاء مجلدات التخزين المؤقتة في /tmp
$storagePath = '/tmp/storage';
if (!file_exists($storagePath)) {
    @mkdir($storagePath . '/framework/views', 0755, true);
    @mkdir($storagePath . '/framework/sessions', 0755, true);
    @mkdir($storagePath . '/framework/cache', 0755, true);
    @mkdir($storagePath . '/bootstrap/cache', 0755, true);
    @mkdir($storagePath . '/logs', 0755, true);
}

// تعيين المسارات للـ Storage والـ Compiled Views في البيئة
putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");
$_ENV['VIEW_COMPILED_PATH'] = "{$storagePath}/framework/views";

require __DIR__ . '/../public/index.php';