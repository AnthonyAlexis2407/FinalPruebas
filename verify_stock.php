<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ProductSize;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\ProductRecipe;
use Illuminate\Support\Facades\DB;

// IDs for Coke 500ml and its components in the new seed:
$coke500 = ProductSize::whereHas('product', fn($q) => $q->where('code', 'BEB001'))
    ->whereHas('size', fn($q) => $q->where('number', '500ml'))
    ->first();

$vaso = ProductSize::whereHas('product', fn($q) => $q->where('code', 'INS001'))->first();
$sorbete = ProductSize::whereHas('product', fn($q) => $q->where('code', 'INS002'))->first();

echo "Initial Stocks:\n";
echo "Coke 500ml: " . $coke500->stock . "\n";
echo "Vaso: " . $vaso->stock . "\n";
echo "Sorbete: " . $sorbete->stock . "\n";

// Use the SaleController logic directly or simulate request
$controller = new \App\Http\Controllers\SaleController();
$request = new \Illuminate\Http\Request();
$request->replace([
    'items' => [
        ['product_size_id' => $coke500->id, 'quantity' => 2]
    ]
]);

echo "\nSelling 2 Coke 500ml...\n";
$response = $controller->store($request);

// Refresh stocks
$coke500->refresh();
$vaso->refresh();
$sorbete->refresh();

echo "Post-Sale Stocks:\n";
echo "Coke 500ml: " . $coke500->stock . " (Expected: 48)\n";
echo "Vaso: " . $vaso->stock . " (Expected: 998)\n";
echo "Sorbete: " . $sorbete->stock . " (Expected: 498)\n";

if ($coke500->stock == 48 && $vaso->stock == 998 && $sorbete->stock == 498) {
    echo "\n✅ VERIFICATION SUCCESSFUL: Automated stock reduction works!\n";
} else {
    echo "\n❌ VERIFICATION FAILED: Stock counts are incorrect.\n";
}
