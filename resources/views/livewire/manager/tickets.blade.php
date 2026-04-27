<?php

use App\Actions\GetTicketsAction;
use App\Actions\GetTicketStatistic;
use App\Actions\UpdateTicketStatus;
use App\Enum\TicketPeriodEnum;
use App\Enum\TicketStatusEnum;
use App\Models\Ticket;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination;

    #[Url(as: 'email')]
    public string $email = '';

    #[Url(as: 'phone')]
    public string $phone = '';

    #[Url(as: 'status')]
    public string $status = '';

    #[Url(as: 'per_page')]
    public int $perPage = 15;

    #[Url(as: 'date_by')]
    public string $dateBy = '';

    #[Computed]
    public function tickets(): LengthAwarePaginator
    {
        return app(GetTicketsAction::class)->execute(
            filters: [
                'email' => $this->email,
                'phone' => $this->phone,
                'status' => $this->status,
                'period' => $this->dateBy
            ],
            perPage: $this->perPage,
            page: $this->getPage(),
        );
    }

    #[Computed]
    public function statusList(): array
    {
        return TicketStatusEnum::labels();
    }

    #[Computed]
    public function statusColor(): array
    {
        return [
            TicketStatusEnum::New->value => 'green',
            TicketStatusEnum::InProgress->value => 'amber',
            TicketStatusEnum::Processed->value => 'cyan',
        ];
    }

    #[Computed]
    public function statistics(): array
    {
        $action = app(GetTicketStatistic::class);
        return [
            $action->execute(TicketPeriodEnum::Day),
            $action->execute(TicketPeriodEnum::Week),
            $action->execute(TicketPeriodEnum::Month),
        ];
    }

    public function updatedEmail(): void
    {
        $this->resetPage();
    }

    public function updatedPhone(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->email = '';
        $this->phone = '';
        $this->status = '';
        $this->perPage = 15;
        $this->dateBy = '';

        $this->resetPage();
    }

    public function show(int $ticketId): void
    {
        $this->redirectRoute('manager.tickets.show',
            parameters: [
                'ticketId' => $ticketId
            ]
        );
    }

    public function processed(int $ticketId): void
    {
        app(UpdateTicketStatus::class)->update($ticketId, TicketStatusEnum::Processed);
        unset($this->tickets);
        unset($this->statistics);
    }

    public function filterPerDate(string $dateBy): void
    {
        $this->dateBy = $dateBy;
        $this->resetPage();
    }

    public function dateButtonVariant(string $value): string
    {
        return $this->dateBy === $value ? 'primary' : 'ghost';
    }
};
?>
<div>
    <flux:card>
        <div class="mb-6">
            <h2 class="mb-5 text-xl">Статистика по обращением</h2>
            <div class="flex gap-6 mb-6">
                @foreach ($this->statistics as $statistic)
                    <div
                        class="
                            relative
                            flex-1
                            rounded-lg
                            px-6 py-4
                            bg-zinc-50
                            dark:bg-zinc-700
                            {{ $loop->iteration > 1 ? 'max-md:hidden' : '' }}
                            {{ $loop->iteration > 3 ? 'max-lg:hidden' : '' }}
                        "
                    >
                        <flux:subheading>
                            {{ $statistic['period']->label() }}
                        </flux:subheading>
                        <flux:heading size="lg" class="mb-2">
                            @foreach($statistic['by_status'] as $key => $byStatusCount)
                                <flux:badge size="lg" color="{{ $this->statusColor[$key] }}">
                                    {{ TicketStatusEnum::tryFrom($key)->label() }} {{ $byStatusCount }}
                                </flux:badge>
                            @endforeach
                        </flux:heading>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mb-6">
            <h2 class="mb-5 text-xl">Фильтр</h2>

            <div class="flex flex-row gap-3">
                <flux:input
                    size="sm"
                    class="basis-64"
                    placeholder="Email"
                    wire:model.live.debounce.500ms="email"
                />

                <flux:input
                    size="sm"
                    class="basis-64"
                    placeholder="Телефон"
                    wire:model.live.debounce.500ms="phone"
                />

                <flux:select
                    size="sm"
                    class="basis-64"
                    wire:model.live="status"
                    placeholder="Статус"
                >
                    <flux:select.option value="">Все статусы</flux:select.option>

                    @foreach ($this->statusList as $statusValue => $statusLabel)
                        <flux:select.option value="{{ $statusValue }}">
                            {{ $statusLabel }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <flux:button.group>
                    <flux:button
                        size="sm"
                        wire:click="filterPerDate('day')"
                        variant="{{ $this->dateButtonVariant('day') }}"
                    >
                        За день
                    </flux:button>
                    <flux:button
                        size="sm"
                        wire:click="filterPerDate('week')"
                        variant="{{ $this->dateButtonVariant('week') }}"
                    >
                        За неделю
                    </flux:button>
                    <flux:button
                        size="sm"
                        wire:click="filterPerDate('month')"
                        variant="{{ $this->dateButtonVariant('month') }}"
                    >
                        За месяц
                    </flux:button>
                </flux:button.group>

                <flux:select
                    size="sm"
                    class="basis-32"
                    wire:model.live="perPage"
                >
                    <flux:select.option value="10">10</flux:select.option>
                    <flux:select.option value="15">15</flux:select.option>
                    <flux:select.option value="25">25</flux:select.option>
                    <flux:select.option value="50">50</flux:select.option>
                </flux:select>

                <flux:button
                    size="sm"
                    wire:click="resetFilters"
                >
                    Сбросить
                </flux:button>
            </div>
        </div>

        <div>
            <h2 class="mb-5 text-xl">Список обращений</h2>
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>ID</flux:table.column>
                    <flux:table.column>Имя клиента</flux:table.column>
                    <flux:table.column>Телефон</flux:table.column>
                    <flux:table.column>Почта</flux:table.column>
                    <flux:table.column>Тема</flux:table.column>
                    <flux:table.column>Статус</flux:table.column>
                    <flux:table.column>Дата создание</flux:table.column>
                    <flux:table.column>Действие</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($this->tickets as $ticket)
                        <flux:table.row :key="$ticket->id">
                            <flux:table.cell>
                                {{ $ticket->id }}
                            </flux:table.cell>
                            <flux:table.cell>
                                {{ $ticket->customer?->name }}
                            </flux:table.cell>

                            <flux:table.cell>
                                {{ $ticket->customer?->phone }}
                            </flux:table.cell>

                            <flux:table.cell>
                                {{ $ticket->customer?->email }}
                            </flux:table.cell>

                            <flux:table.cell>
                                {{ $ticket->title }}
                            </flux:table.cell>

                            <flux:table.cell>
                                <flux:badge color="{{ $this->statusColor[$ticket->status->value] }}">
                                    {{ $ticket->status->label() }}
                                </flux:badge>
                            </flux:table.cell>

                            <flux:table.cell>
                                {{ $ticket->created_at->format('d.m.Y H:i') }}
                            </flux:table.cell>

                            <flux:table.cell>
                                <flux:button
                                    wire:click="show({{ $ticket->id }})"
                                >
                                    Подробнее
                                </flux:button>
                                <flux:button
                                    variant="{{ $ticket->status !== TicketStatusEnum::Processed ? 'primary' : 'filled' }}"
                                    color="{{ $ticket->status !== TicketStatusEnum::Processed ? 'cyan' : 'white' }}"
                                    wire:click="processed({{ $ticket->id }})"
                                    wire:bind:disabled="{{ $ticket->status === TicketStatusEnum::Processed }}"
                                >
                                    @if($ticket->status === TicketStatusEnum::Processed)
                                        Обработано
                                    @else
                                        Обработать
                                    @endif
                                </flux:button>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="7">
                                Тикеты не найдены
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>

            <div class="mt-4">
                <flux:pagination :paginator="$this->tickets"/>
            </div>
        </div>
    </flux:card>
</div>
