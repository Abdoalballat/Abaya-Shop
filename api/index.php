<?php

$tmp = '/tmp/storage';

foreach ([
    "$tmp/framework/views",
    "$tmp/framework/sessions",
    "$tmp/framework/cache/data",
    "$tmp/bootstrap/cache",
    "$tmp/logs",
] as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

$vars = [
    'VIEW_COMPILED_PATH' => "$tmp/framework/views",
    'APP_CONFIG_CACHE'   => "$tmp/bootstrap/cache/config.php",
    'APP_EVENTS_CACHE'   => "$tmp/bootstrap/cache/events.php",
    'APP_PACKAGES_CACHE' => "$tmp/bootstrap/cache/packages.php",
    'APP_ROUTES_CACHE'   => "$tmp/bootstrap/cache/routes.php",
    'APP_SERVICES_CACHE' => "$tmp/bootstrap/cache/services.php",
];

foreach ($vars as $key => $value) {
    putenv("$key=$value");
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
}

require __DIR__ . '/../public/index.php';