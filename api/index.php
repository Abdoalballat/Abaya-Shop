<?php

// إعداد مجلدات التخزين المؤقتة لـ Vercel
$storagePath = '/tmp/storage';
if (!file_exists($storagePath)) {
    mkdir($storagePath . '/framework/views', 0755, true);
    mkdir($storagePath . '/framework/sessions', 0755, true);
    mkdir($storagePath . '/framework/cache', 0755, true);
    mkdir($storagePath . '/logs', 0755, true);
}

app()->useStoragePath($storagePath);

require __DIR__ . '/../public/index.php';