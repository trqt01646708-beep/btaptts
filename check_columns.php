<?php
use Illuminate\Support\Facades\Schema;
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$hasColumn = Schema::hasColumn('orders', 'vnpay_transaction_id');
echo $hasColumn ? 'EXISTS' : 'MISSING';
