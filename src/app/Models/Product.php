<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Product extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock_quantity',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
