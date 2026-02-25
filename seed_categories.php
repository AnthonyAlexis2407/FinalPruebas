<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;

$categories = ['Caballeros', 'Damas', 'Niños', 'Deportivos', 'Formal', 'Casual'];

foreach ($categories as $cat) {
    if (!Category::where('name', $cat)->exists()) {
        Category::create(['name' => $cat, 'active' => true]);
        echo "Created category: $cat\n";
    } else {
        echo "Category already exists: $cat\n";
    }
}

echo "Categories seeded successfully.";
