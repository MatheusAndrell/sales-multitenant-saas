<?php

namespace App\Actions\Customers;

use App\DTOs\Customer\CustomerData;
use App\Models\Customer;

class CreateCustomerAction
{
    public function execute(CustomerData $data): Customer
    {
        return Customer::create($data->toArray());
    }
}
