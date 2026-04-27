<?php
namespace App\Actions;

use App\Models\Ticket;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class AttachTicketFiles
{
    /**
     * @throws FileIsTooBig
     * @throws FileDoesNotExist
     */
    public function attach(Ticket $ticket, array $attachments): void
    {
        foreach ($attachments as $attachment) {
            $ticket
                ->addMedia($attachment)
                ->toMediaCollection('ticket_attachments');
        }
    }
}
