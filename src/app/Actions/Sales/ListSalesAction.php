<?php

namespace App\Actions\Sales;

use App\Models\Sale;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListSalesAction
{
    public function execute(): LengthAwarePaginator
    {
        return Sale::with('items')->paginate(10);
    }
}
