<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $table = 'sizes';
    protected $guarded = [];
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}