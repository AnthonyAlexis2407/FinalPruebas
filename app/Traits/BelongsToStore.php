<?php

namespace App\Traits;

use App\Models\Store;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;

trait BelongsToStore
{
    protected static function bootBelongsToStore()
    {
        static::creating(function ($model) {
            if (!$model->store_id && session()->has('active_store_id')) {
                $model->store_id = session('active_store_id');
            }
        });

        static::addGlobalScope('store', function (Builder $builder) {
            if (session()->has('active_store_id')) {
                // For the User model, we allow Administrators to bypass the store filter during authentication
                // or when explicitly requested. However, to avoid infinite recursion, we use a simple
                // role check ONLY if the user is already authenticated and loaded.
                if (static::class === \App\Models\User::class) {
                    $builder->where(function ($query) {
                        $query->where('store_id', session('active_store_id'))
                            ->orWhere('role', \App\Models\User::ROLE_ADMIN);
                    });
                } else {
                    // Other models (Products, Categories, etc.) are strictly isolated by store
                    $builder->where('store_id', session('active_store_id'));
                }
            }
        });
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
