<?php

namespace App\Actions;

use App\Http\Resources\TicketResource;
use App\Models\Ticket;

readonly class GetTicketAction
{
    public function __construct() {}

    public function execute(int $ticketId): Ticket
    {
        return Ticket::query()
            ->with(['customer'])
            ->findOrFail($ticketId);
    }
}
