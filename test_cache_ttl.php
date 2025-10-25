<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Тест 1: Cache::put с 1 годом
echo "Тест 1: Cache::put с TTL = 31536000 секунд (1 год)\n";
Cache::put('test_one_year', 'value', 31536000);
echo "  Ключ создан\n";

// Тест 2: Cache::remember с 1 годом
echo "\nТест 2: Cache::remember с TTL = 31536000 секунд (1 год)\n";
Cache::remember('test_remember_year', 31536000, function() {
    return 'test value';
});
echo "  Ключ создан\n";

// Тест 3: Cache::put с 1 часом
echo "\nТест 3: Cache::put с TTL = 3600 секунд (1 час)\n";
Cache::put('test_one_hour', 'value', 3600);
echo "  Ключ создан\n";

echo "\nВсе тесты завершены!\n";
