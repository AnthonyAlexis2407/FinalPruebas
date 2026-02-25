<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToStore;

class Product extends Model
{
    use BelongsToStore;

    protected $fillable = ['category_id', 'code', 'name', 'price', 'image_path', 'store_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function sizes()
    {
        return $this->hasMany(ProductSize::class);
    }
}
