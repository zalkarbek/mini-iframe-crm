<?php
namespace App\Actions;

use App\Enum\TicketStatusEnum;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

readonly class CreateTicket
{
    public function __construct(
        private AttachTicketFiles $attachTicketFiles,
        private GetOrCreateCustomer $getOrCreateCustomer,
    ) {}

    /**
     * @throws \Throwable
     */
    public function create(array $validatedData): Ticket
    {
        return DB::transaction(function () use ($validatedData): Ticket {

            $customer = $this->getOrCreateCustomer->getOrCreate([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
            ]);

            $ticket = Ticket::query()->create([
                'customer_id' => $customer->id,
                'title' => $validatedData['title'],
                'content' => $validatedData['content'],
                'status' => TicketStatusEnum::New,
                'manager_replied_at' => null,
            ]);

            if (! empty($validatedData['attachments'])) {
                $this->attachTicketFiles->attach($ticket, $validatedData['attachments']);
            }

            return $ticket->loadMissing('customer');
        });
    }
}
