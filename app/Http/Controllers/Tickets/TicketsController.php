<?php

namespace App\Http\Controllers\Tickets;

use App\Http\Controllers\Controller;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class TicketsController extends Controller
{

    public function redeem()
    {
        return view('tickets.redeem');
    }

    public function processRedemption(Request $request, TicketService $ticketService) {
        $data = $request->validate([
            'code' => 'required',
        ]);

        if (RateLimiter::remaining('redeem-ticket:' . $request->user()->id, $perMinute = 1)) {
            if($ticketService->redeemTicket($data['code'])) {
                RateLimiter::hit('redeem-ticket:' . $request->user()->id);

                return redirect()
                    ->route('tickets.redeem')
                    ->with('success', __('Ticket redeemed successfully'));
            } else {
                return redirect()
                    ->route('tickets.redeem')
                    ->with('error', __('Failed to redeem ticket. Please try again'));
            }
        }

        if (RateLimiter::tooManyAttempts('redeem-ticket:' . $request->user()->id, $perMinute = 1)) {
            $seconds = RateLimiter::availableIn('redeem-ticket:' . $request->user()->id);
            // todo: log this attempt

            return redirect()
                ->route('tickets.redeem')
                ->with('error', __('You may try again in ' . $seconds . ' seconds.'));
        }
    }

    public function history(Request $request) {
        $tickets = $request->user()->redeemedTickets()->paginate();
        return view('tickets.history', compact('tickets'));
    }

}
