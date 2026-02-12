<?php

namespace App\Actions\Customers;

use App\DTOs\Customer\CustomerData;
use App\Models\Customer;

class UpdateCustomerAction
{
    public function execute(Customer $customer, CustomerData $data): Customer
    {
        $customer->update($data->toArray());
        return $customer;
    }
}
