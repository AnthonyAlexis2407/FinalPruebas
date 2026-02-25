<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToStore;

class Size extends Model
{
    use BelongsToStore;

    protected $fillable = ['number', 'category_id', 'store_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_sizes')->withPivot('stock', 'id');
    }

    public function productSizes()
    {
        return $this->hasMany(ProductSize::class);
    }
}
