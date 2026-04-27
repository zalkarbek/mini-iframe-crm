<?php

use App\Actions\GetTicketAction;
use App\Models\Ticket;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;


new class extends Component {

    public int $ticketId;
    public Ticket $ticket;
    public MediaCollection $attachments;

    public function mount(int $ticketId): void
    {
        $this->ticketId = $ticketId;
        $this->ticket = app(GetTicketAction::class)->execute($this->ticketId);
        $this->attachments = $this->ticket->getMedia('ticket_attachments');
    }
};
?>

<div>
    <flux:card>
        <flux:heading class="text-xl">Данные о заявке</flux:heading>

        <div class="flex flex-row mt-5">
            <div class="mr-10 basis-1/4">
                <flux:heading class="text-xl">Клиент</flux:heading>
                <div>
                    <span class="text-orange-700 font-semibold">
                        Имя:
                    </span>
                    <span>{{ $ticket->customer->name }}</span>
                </div>

                <div>
                    <span class="text-orange-700 font-semibold">
                        Телефон:
                    </span>
                    <span>{{ $ticket->customer->phone }}</span>
                </div>

                <div>
                    <span class="text-orange-700 font-semibold">
                        Email:
                    </span>
                    <span>{{ $ticket->customer->email }}</span>
                </div>
            </div>

            <div class="basis-3/4">
                <flux:heading class="text-xl">Обращение</flux:heading>
                <div>
                    <div class="text-orange-700 text-lg font-semibold">
                        Тема:
                    </div>
                    <div>
                        {{ $ticket->title }}
                    </div>
                </div>

                <div>
                    <div class="text-orange-700 text-lg mt-3 font-semibold">
                        Содержание:
                    </div>
                    <div class="mt-2">
                        {{ $ticket->content }}
                    </div>
                </div>

                <div>
                    <div class="text-orange-700 text-lg mt-5 font-semibold">
                        Прикрепленные файлы:
                    </div>
                    <div>
                        <ul class="list-disk">
                            @foreach($attachments as $attachment)
                                <li :key="{{ $attachment->id }}" class="mt-2">
                                    <span>{{ $attachment->name }}</span>

                                    <flux:link href="{{ route('media.download', $attachment) }}">
                                        Скачать
                                    </flux:link>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <flux:button variant="primary" onclick="history.back()">
                Назад
            </flux:button>
        </div>
    </flux:card>
</div>
