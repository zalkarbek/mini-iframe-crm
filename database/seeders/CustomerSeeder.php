<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::factory(rand(200, 300))
            ->create()
            ->each(function ($customer) {
                Ticket::factory(rand(2, 7))->newStatus()->create([
                    'customer_id' => $customer->id,
                ]);
                Ticket::factory(rand(2, 7))->inProgress()->create([
                    'customer_id' => $customer->id,
                ]);
                Ticket::factory(rand(2, 7))->resolved()->create([
                    'customer_id' => $customer->id,
                ]);
            });
    }
}
