<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Store;
use App\Models\Category;
use App\Models\Product;
use App\Models\Size;

try {
    echo "Creating second store 'CatCoffee'...\n";
    $store = Store::firstOrCreate(['name' => 'CatCoffee'], [
        'primary_color' => '#6366f1', // Indigo color for contrast
    ]);

    // Set active store in session for the trait to work during creation
    session(['active_store_id' => $store->id]);

    echo "Seeding categories for CatCoffee...\n";
    $coffeeCat = Category::create(['name' => 'Café', 'active' => true]);
    $bakeryCat = Category::create(['name' => 'Repostería', 'active' => true]);

    echo "Seeding sizes for CatCoffee...\n";
    $mediano = Size::create(['number' => 'Mediano', 'category_id' => $coffeeCat->id]);
    $grande = Size::create(['number' => 'Grande', 'category_id' => $coffeeCat->id]);

    echo "Seeding products for CatCoffee...\n";
    $latte = Product::updateOrCreate(
        ['code' => 'CC001'],
        [
            'category_id' => $coffeeCat->id,
            'name' => 'Caffé Latte',
            'price' => 3.50,
            'store_id' => $store->id
        ]
    );
    $latte->sizes()->updateOrCreate(
        ['size_id' => $mediano->id],
        ['stock' => 100]
    );
    $latte->sizes()->updateOrCreate(
        ['size_id' => $grande->id],
        ['stock' => 80]
    );

    echo "Success! Second store 'CatCoffee' created with sample products.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
