<?php

namespace App\Actions;

use App\Models\Customer;

readonly class GetCustomer
{
    public function getOrCreate(array $customer): ?Customer
    {
        return Customer::query()
            ->where('email', $customer['email'])
            ->first();
    }
}
