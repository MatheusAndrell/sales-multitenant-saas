<?php

namespace App\Actions\Customers;

use App\Models\Customer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListCustomersAction
{
    public function execute(): LengthAwarePaginator
    {
        return Customer::paginate(10);
    }
}
