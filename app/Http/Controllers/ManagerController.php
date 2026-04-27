<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function dashboard(Request $request)
    {
        return view('pages.manager.dashboard');
    }

    public function tickets(Request $request)
    {
        return view('pages.manager.tickets.index');
    }

    public function ticketShow(int $ticketId)
    {
        return view('pages.manager.tickets.show', [
            'ticketId' => $ticketId,
        ]);
    }
}
