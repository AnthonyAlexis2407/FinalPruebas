<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToStore;

class Category extends Model
{
    use BelongsToStore;

    protected $fillable = ['name', 'active', 'store_id'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
