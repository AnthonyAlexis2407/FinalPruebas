<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Size;

$sizes = ['35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45'];

foreach ($sizes as $s) {
    if (!Size::where('number', $s)->exists()) {
        Size::create(['number' => $s]);
    }
}

echo "Sizes seeded successfully.";
