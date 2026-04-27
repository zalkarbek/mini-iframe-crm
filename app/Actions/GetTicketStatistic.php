<?php

namespace App\Actions;

use App\DTO\DateRangeDTO;
use App\Enum\TicketPeriodEnum;
use App\Enum\TicketStatusEnum;
use App\Models\Ticket;
use Illuminate\Support\Collection;

class GetTicketStatistic
{
    public function execute(TicketPeriodEnum $period): array
    {
        $dateRange = new DateRangeDTO($period);
        $tickets = $this->fetchTickets($dateRange);
        $groupByStatus = $this->groupByStatus($tickets);

        $newCount = data_get($groupByStatus, TicketStatusEnum::New->value, collect())->count();
        $inProgressCount = data_get($groupByStatus, TicketStatusEnum::InProgress->value, collect())->count();
        $processed = data_get($groupByStatus, TicketStatusEnum::Processed->value, collect());

        return [
            'period' => $period,
            'date_range' => ['start' => $dateRange->start, 'end' => $dateRange->end],
            'total' => $tickets->count(),
            'by_status' => [
                TicketStatusEnum::New->value => $newCount,
                TicketStatusEnum::InProgress->value => $inProgressCount,
                TicketStatusEnum::Processed->value => $processed->count(),
            ],
            'average_response_time' => $this->averageResponseTime($processed),
            'average_per_day' => $this->averagePerDay($tickets->count(), $dateRange->days),
        ];
    }

    private function fetchTickets(DateRangeDTO $dateRange): Collection
    {
        return Ticket::query()
            ->where('created_at', '>=', $dateRange->start)
            ->get();
    }

    private function groupByStatus(Collection $tickets): Collection
    {
        return $tickets->groupBy(function (Ticket $ticket) {
            return $ticket->status->value;
        });
    }

    private function averageResponseTime(Collection $processed): ?string
    {
        if ($processed->isEmpty()) {
            return null;
        }

        $avg = round(
            $processed->avg(function (Ticket $t) {
                return $t->created_at->diffInHours($t->manager_replied_at);
            }),
            2
        );

        return "{$avg} часов";
    }

    private function averagePerDay(int $total, int $days): float
    {
        return $total > 0 ? round($total / $days, 2) : 0.0;
    }
}
