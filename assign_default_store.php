<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Store;
use Illuminate\Support\Facades\DB;

try {
    echo "Creating default store 'BobaCat'...\n";
    $store = Store::firstOrCreate(['name' => 'BobaCat'], [
        'primary_color' => '#f43f5e', // Current primary color
    ]);

    echo "Assigning data to Store ID: {$store->id}...\n";

    $tables = ['users', 'categories', 'products', 'sizes', 'sales', 'product_sizes', 'product_recipes', 'sale_details'];

    foreach ($tables as $table) {
        $count = DB::table($table)->whereNull('store_id')->update(['store_id' => $store->id]);
        echo "Updated {$count} rows in '{$table}' table.\n";
    }

    echo "Success!\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
