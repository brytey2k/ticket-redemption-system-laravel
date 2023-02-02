<?php

namespace App\Http\Controllers\Tickets;

use App\Http\Controllers\Controller;
use App\Http\Requests\TicketRedemptionRequest;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class TicketsController extends Controller
{

    public function redeem()
    {
        return view('tickets.redeem');
    }

    public function processRedemption(TicketRedemptionRequest $request, TicketService $ticketService) {
        $data = $request->validated();

        if($ticketService->redeemTicket($data['code'])) {
            return redirect()
                ->route('tickets.redeem')
                ->with('success', __('Ticket redeemed successfully'));
        } else {
            return redirect()
                ->route('tickets.redeem')
                ->with('error', __('Failed to redeem ticket. Please try again'));
        }
    }

    public function history(Request $request) {
        $tickets = $request->user()->redeemedTickets()->paginate();
        return view('tickets.history', compact('tickets'));
    }

}
