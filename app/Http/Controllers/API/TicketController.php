<?php

namespace App\Http\Controllers\API;

use App\Actions\CreateTicket;
use App\Actions\GetTicketStatistic;
use App\Http\Controllers\Controller;
use App\Http\Requests\TicketStoreRequest;
use App\Http\Resources\TicketResource;
use App\Http\Resources\TicketStatisticResource;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TicketController extends Controller
{
    public function index()
    {
        return TicketResource::collection(
            Ticket::query()->orderByDesc('created_at')->paginate(10)
        );
    }

    /**
     * @throws \Throwable
     */
    public function store(
        TicketStoreRequest $request,
        CreateTicket $createTicket
    ) {
        $ticket = $createTicket->create($request->validated());

        return new TicketResource($ticket);
    }

    public function statistics(Request $request, GetTicketStatistic $getTicketStatistic)
    {
        $validated = $request->validate([
            'period' => ['required', Rule::in(['day', 'week', 'month'])],
        ]);
        $statistics = $getTicketStatistic->execute($validated['period']);

        return response()->json([
            'success' => true,
            'data' => new TicketStatisticResource($statistics),
        ]);
    }
}
