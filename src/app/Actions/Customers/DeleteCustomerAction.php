<?php

namespace App\Actions\Customers;

use App\Models\Customer;

class DeleteCustomerAction
{
    public function execute(Customer $customer): void
    {
        $customer->delete();
    }
}
