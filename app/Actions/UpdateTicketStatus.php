<?php
namespace App\Actions;

use App\Enum\TicketStatusEnum;
use App\Models\Ticket;

readonly class UpdateTicketStatus
{
    public function update(int $ticketId, TicketStatusEnum $status): void
    {
        $ticket = Ticket::query()->findOrFail($ticketId);
        $ticket->status = $status;
        $ticket->manager_replied_at = now();
        $ticket->save();
    }
}
