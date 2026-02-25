<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToStore;

class ProductRecipe extends Model
{
    use BelongsToStore;

    protected $fillable = ['product_size_id', 'component_product_size_id', 'quantity', 'store_id'];

    public function productSize()
    {
        return $this->belongsTo(ProductSize::class, 'product_size_id');
    }

    public function componentProductSize()
    {
        return $this->belongsTo(ProductSize::class, 'component_product_size_id');
    }
}
