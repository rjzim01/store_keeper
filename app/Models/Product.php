<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function sellProducts()
    {
        return $this->hasMany(SellProduct::class, 'product_id', 'id');
    }
}
