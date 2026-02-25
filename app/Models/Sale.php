<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToStore;

class Sale extends Model
{
    use BelongsToStore;

    protected $fillable = ['total', 'store_id'];

    public function details()
    {
        return $this->hasMany(SaleDetail::class);
    }
}
