<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToStore;

class SaleDetail extends Model
{
    use BelongsToStore;

    protected $fillable = ['sale_id', 'product_size_id', 'quantity', 'unit_price', 'subtotal', 'store_id'];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function productSize()
    {
        return $this->belongsTo(ProductSize::class);
    }
}
