<?php

namespace Database\Factories;

use App\Enum\TicketStatusEnum;
use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'title' => fake()->sentence(),
            'content' => fake()->paragraph(),
            'status' => fake()->randomElement(TicketStatusEnum::values()),
            'manager_replied_at' => null,

            'created_at' => fake()->dateTimeBetween('-360 days'),
            'updated_at' => fake()->dateTimeBetween('-60 days'),
        ];
    }

    public function newStatus(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TicketStatusEnum::New,
            'manager_replied_at' => null,
        ]);
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TicketStatusEnum::InProgress,
            'manager_replied_at' => null,
        ]);
    }

    public function resolved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TicketStatusEnum::Processed,
            'manager_replied_at' => fake()->dateTimeBetween('-40 days'),
        ]);
    }
}
