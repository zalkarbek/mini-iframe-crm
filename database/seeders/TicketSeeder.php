<?php

namespace Database\Seeders;

use App\Models\Ticket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ticket::factory(rand(10, 50))->newStatus()->create();
        Ticket::factory(rand(10, 50))->inProgress()->create();
        Ticket::factory(rand(10, 50))->resolved()->create();
    }
}
