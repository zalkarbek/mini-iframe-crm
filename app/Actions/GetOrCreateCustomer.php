<?php

namespace App\Actions;

use App\Models\Customer;

readonly class GetOrCreateCustomer
{
    public function __construct(
        private GetCustomer $getCustomer,
        private CreateCustomer $createCustomer,
    ) {}

    public function getOrCreate(array $customer): Customer
    {
        $findCustomer = $this->getCustomer->getOrCreate($customer);
        if ($findCustomer) {
            return $findCustomer;
        }
        return $this->createCustomer->create($customer);
    }
}
