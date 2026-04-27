<?php

namespace App\Actions;

use App\Models\Customer;

class CreateCustomer
{
    public function create(array $customer): Customer
    {
        return Customer::query()->create([
            'name' => $customer['name'],
            'phone' => $customer['phone'],
            'email' => $customer['email'],
        ]);
    }
}
