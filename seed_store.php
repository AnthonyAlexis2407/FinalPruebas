<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;
use App\Models\Size;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

echo "Cleaning database...\n";
DB::statement('SET FOREIGN_KEY_CHECKS=0;');
DB::table('sale_details')->truncate();
DB::table('sales')->truncate();
DB::table('product_sizes')->truncate();
DB::table('products')->truncate();
DB::table('categories')->truncate();
DB::table('sizes')->truncate();
DB::statement('SET FOREIGN_KEY_CHECKS=1;');

echo "Seeding Categories...\n";
$categories = [
    ['name' => 'Bebidas', 'active' => true],
    ['name' => 'Snacks', 'active' => true],
    ['name' => 'Golosinas', 'active' => true],
    ['name' => 'Lácteos', 'active' => true],
    ['name' => 'Insumos', 'active' => true], // Vasos, popotes, etc.
];

foreach ($categories as $cat) {
    Category::create($cat);
}

echo "Seeding Sizes (Presentaciones)...\n";
$sizes = ['Individual', '250ml', '500ml', '1L', '1.5L', '2L', 'Pequeño', 'Mediano', 'Grande', 'Familiar', 'Unidad'];
foreach ($sizes as $s) {
    Size::create(['number' => $s]);
}

echo "Seeding Products...\n";
$bebidasId = Category::where('name', 'Bebidas')->first()->id;
$snacksId = Category::where('name', 'Snacks')->first()->id;
$insumosId = Category::where('name', 'Insumos')->first()->id;

$products = [
    [
        'category_id' => $bebidasId,
        'code' => 'BEB001',
        'name' => 'Coca-Cola Original',
        'price' => 1.50,
        'presentations' => [
            ['size' => '500ml', 'stock' => 50],
            ['size' => '1L', 'stock' => 30],
        ]
    ],
    [
        'category_id' => $insumosId,
        'code' => 'INS001',
        'name' => 'Vaso Desechable 500ml',
        'price' => 0.00,
        'presentations' => [
            ['size' => 'Unidad', 'stock' => 1000],
        ]
    ],
    [
        'category_id' => $insumosId,
        'code' => 'INS002',
        'name' => 'Sorbete (Popote) Bio',
        'price' => 0.00,
        'presentations' => [
            ['size' => 'Unidad', 'stock' => 500],
        ]
    ],
    [
        'category_id' => $snacksId,
        'code' => 'SNA001',
        'name' => 'Alitas BBQ (6 unidades)',
        'price' => 5.50,
        'presentations' => [
            ['size' => 'Individual', 'stock' => 20],
        ]
    ],
    [
        'category_id' => $insumosId,
        'code' => 'INS003',
        'name' => 'Bandeja Descartable',
        'price' => 0.00,
        'presentations' => [
            ['size' => 'Unidad', 'stock' => 100],
        ]
    ],
];

foreach ($products as $pData) {
    $presentations = $pData['presentations'];
    unset($pData['presentations']);

    $product = Product::create($pData);

    foreach ($presentations as $pres) {
        $size = Size::where('number', $pres['size'])->first();
        if ($size) {
            $product->sizes()->attach($size->id, ['stock' => $pres['stock']]);
        }
    }
}

echo "Seeding Recipes (Automated Stock Reduction)...\n";
// Recipe 1: Coca-Cola 500ml -> 1 Vaso + 1 Sorbete
$coke500 = \App\Models\ProductSize::whereHas('product', fn($q) => $q->where('code', 'BEB001'))
    ->whereHas('size', fn($q) => $q->where('number', '500ml'))
    ->first();

$vaso = \App\Models\ProductSize::whereHas('product', fn($q) => $q->where('code', 'INS001'))->first();
$sorbete = \App\Models\ProductSize::whereHas('product', fn($q) => $q->where('code', 'INS002'))->first();

if ($coke500 && $vaso && $sorbete) {
    \App\Models\ProductRecipe::create([
        'product_size_id' => $coke500->id,
        'component_product_size_id' => $vaso->id,
        'quantity' => 1
    ]);
    \App\Models\ProductRecipe::create([
        'product_size_id' => $coke500->id,
        'component_product_size_id' => $sorbete->id,
        'quantity' => 1
    ]);
}

// Recipe 2: Alitas BBQ -> 1 Bandeja
$alitas = \App\Models\ProductSize::whereHas('product', fn($q) => $q->where('code', 'SNA001'))->first();
$bandeja = \App\Models\ProductSize::whereHas('product', fn($q) => $q->where('code', 'INS003'))->first();

if ($alitas && $bandeja) {
    \App\Models\ProductRecipe::create([
        'product_size_id' => $alitas->id,
        'component_product_size_id' => $bandeja->id,
        'quantity' => 1
    ]);
}

echo "Seeding Admin User...\n";
\App\Models\User::updateOrCreate(
    ['email' => 'admin@bobacat.com'],
    [
        'name' => 'Administrador BobaCat',
        'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
    ]
);

echo "Database successfully reseeded with Recipes for BobaCat!\n";
