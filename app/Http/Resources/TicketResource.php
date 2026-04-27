<?php

namespace App\Http\Resources;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Ticket $ticket */
        $ticket = $this->resource;

        $customer = [];
        if ($ticket->customer) {
            $customer = [
                'id' => $ticket->customer->id,
                'name' => $ticket->customer->name,
                'email' => $ticket->customer->email,
            ];
        }

        return [
            'id' => $ticket->id,
            'title' => $ticket->title,
            'content' => $ticket->content,
            'status' => $ticket->status->value,
            'status_label' => $ticket->status->label(),
            'customer' => $customer,
            'attachments' => $ticket->getMedia('ticket_attachments')
                ->map(fn ($media): array => [
                    'id' => $media->id,
                    'name' => $media->file_name,
                    'url' => $media->getUrl(),
                    'size' => $media->size,
                    'mime_type' => $media->mime_type,
                ])
                ->values()
                ->all(),
            'manager_replied_at' => $ticket->manager_replied_at?->format('d.m.Y H:i'),
            'created_at' => $ticket->created_at->format('d.m.Y H:i'),
            'updated_at' => $ticket->updated_at->format('d.m.Y H:i'),
        ];
    }
}
