<?php

namespace App\Http\Controllers\Tickets;

use App\Http\Controllers\Controller;
use App\Http\Requests\TicketGenerationRequest;
use App\Http\Requests\TicketRedemptionRequest;
use App\Jobs\GenerateTicketsJob;
use App\Models\Ticket;
use App\Services\TicketService;
use Auth;
use Illuminate\Http\Request;

class TicketsController extends Controller
{

    public function __construct(private TicketService $ticketService) {
        $this->authorizeResource(Ticket::class, 'ticket');
    }

    public function index(Request $request) {
        $tickets = Ticket::with(['redeemedBy']);

        if($request->query('code')) {
            $tickets->where('code', '=', $request->query('code'));
        }
        if($request->query('status')) {
            $tickets->where('status', '=', $request->query('status'));
        }

        $tickets = $tickets->paginate();

        return view('tickets.index', compact('tickets', 'request'));
    }

    public function generate() {
        return view('tickets.generate');
    }

    public function processTicketGeneration(TicketGenerationRequest $request) {
        $quantity = $request->validated('number_of_tickets');

        $this->ticketService->generateTickets($quantity, auth()->user()->email);

        return redirect()
            ->route('tickets.index')
            ->with('success', 'Ticket generation queued and processing');
    }

    public function processRedemption(TicketRedemptionRequest $request, TicketService $ticketService) {
        $data = $request->validated();

        if($ticketService->redeemTicket($data['code'], Auth::user())) {
            return redirect()
                ->route('tickets.index', ['status' => 'not_redeemed'])
                ->with('success', __('Ticket redeemed successfully'));
        } else {
            return redirect()
                ->route('tickets.index', ['status' => 'not_redeemed'])
                ->with('error', __('Failed to redeem ticket. Please try again'));
        }
    }

    public function history(Request $request) {
        $tickets = $request->user()->redeemedTickets();

        if($request->query('code')) {
            $tickets->where('code', '=', $request->query('code'));
        }

        $tickets = $tickets->paginate();

        return view('tickets.history', compact('tickets'));
    }

}
