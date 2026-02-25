<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToStore;

class ProductSize extends Model
{
    use BelongsToStore;

    protected $table = 'product_sizes';
    protected $fillable = ['product_id', 'size_id', 'stock', 'name', 'store_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    // Accessor to get name from column or fallback to relation
    // This allows us to use $productSize->name seamlessly
    public function getNameAttribute($value)
    {
        if ($value) {
            return $value;
        }
        return $this->size ? $this->size->number : 'N/A';
    }

    public function recipes()
    {
        return $this->hasMany(ProductRecipe::class, 'product_size_id');
    }
}
