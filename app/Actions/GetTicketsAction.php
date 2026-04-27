<?php

namespace App\Actions;

use App\DTO\DateRangeDTO;
use App\Enum\TicketPeriodEnum;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class GetTicketsAction
{
    public function __construct() {}

    public function execute(array $filters, int $perPage = 15, $page = 1): LengthAwarePaginator
    {
        return Ticket::query()
            ->with('customer')
            ->when(data_get($filters, 'email'), function (Builder $query, $email) {
                $query->whereHas('customer', fn ($q) => $q->where('email', 'like', "%{$email}%"));
            })
            ->when(data_get($filters, 'phone'), function (Builder $query, $phone) {
                $query->whereHas('customer', fn ($q) => $q->where('phone', 'like', "%{$phone}%"));
            })
            ->when(data_get($filters, 'status'), function (Builder $query, $status) {
                $query->where('status', $status);
            })
            ->when(data_get($filters, 'period'), function (Builder $query, $period) {
                $dateRange = new DateRangeDTO(TicketPeriodEnum::tryFrom($period));
                $query->where('created_at', '>=', $dateRange->start);
            })
            ->orderByDesc('created_at')
            ->paginate($perPage, ['*'], 'page', $page);
    }
}
