<?php

namespace App\DTO;

use App\Enum\TicketPeriodEnum;
use Illuminate\Support\Carbon;

readonly class DateRangeDTO
{
    public string $start;
    public string $end;
    public int $days;

    public function __construct(TicketPeriodEnum $period)
    {
        $this->start = $period->startDate()->format('Y-m-d H:i:s');
        $this->end = Carbon::now()->format('Y-m-d H:i:s');
        $this->days = $period->toDays();
    }
}
