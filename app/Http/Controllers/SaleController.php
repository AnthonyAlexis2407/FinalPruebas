<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\ProductSize;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleController extends Controller
{
    public function index()
    {
        $products = \App\Models\Product::with('sizes')->get();
        return view('sales.pos', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_size_id' => 'required|exists:product_sizes,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $total = 0;

        DB::beginTransaction();
        try {
            $sale = Sale::create(['total' => 0]);

            foreach ($request->items as $item) {
                // Lock for update to prevent race conditions
                $productSize = ProductSize::lockForUpdate()->find($item['product_size_id']);

                if ($productSize->stock < $item['quantity']) {
                    throw new \Exception("Stock insuficiente para " . $productSize->product->name . " (" . $productSize->size->number . ")");
                }

                // 1. Reduce main product stock
                $productSize->stock -= $item['quantity'];
                $productSize->save();

                // 2. Reduce recipe components stock (e.g. cups, straws)
                foreach ($productSize->recipes as $recipe) {
                    $component = ProductSize::lockForUpdate()->find($recipe->component_product_size_id);
                    $neededQuantity = $recipe->quantity * $item['quantity'];

                    if ($component->stock < $neededQuantity) {
                        throw new \Exception("Insumo insuficiente: " . $component->product->name . " (Faltan: " . ($neededQuantity - $component->stock) . ")");
                    }

                    $component->stock -= $neededQuantity;
                    $component->save();
                }

                $unitPrice = $productSize->product->price;
                $subtotal = $unitPrice * $item['quantity'];
                $total += $subtotal;

                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_size_id' => $productSize->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal
                ]);
            }

            $sale->update(['total' => $total]);
            DB::commit();
            $sale->update(['total' => $total]);
            DB::commit();
            return response()->json([
                'success' => true,
                'sale_id' => $sale->id,
                'ticket_url' => route('sales.ticket', $sale->id),
                'a4_url' => route('sales.print', $sale->id)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function print(Sale $sale)
    {
        $sale->load('details.productSize.product', 'details.productSize.size');
        $pdf = Pdf::loadView('sales.invoice_a4', compact('sale'));
        return $pdf->stream('factura-' . $sale->id . '.pdf');
    }

    public function ticket(Sale $sale)
    {
        $sale->load('details.productSize.product', 'details.productSize.size');
        // Custom paper size for ticket (80mm width => approx 226pt)
        // Reduce slightly to 210pt to ensure margins fit well
        $customPaper = array(0, 0, 210, 1000);
        $pdf = Pdf::loadView('sales.invoice_ticket', compact('sale'));
        $pdf->setPaper($customPaper);
        return $pdf->stream('ticket-' . $sale->id . '.pdf');
    }
}
