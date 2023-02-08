<?php

namespace App\Services;

use App\Jobs\GenerateTicketsJob;
use App\Models\Ticket;
use App\Models\User;
use Log;

class TicketService
{

    public function redeemTicket(string $code, User $user): ?Ticket
    {
        $ticket = Ticket::where('code', '=', $code)
            ->where('status', '=', 'not_redeemed')
            ->first();

        if(is_null($ticket)) {
            return null;
        }

        if($this->markAsRedeemed($ticket, $user)) {
            $ticket->refresh();
            return $ticket;
        } else {
            Log::error("Marking code as redeemed failed", ['code' => $ticket]);

            return null;
        }
    }

    public function markAsRedeemed(Ticket $ticket, User $user): bool
    {
        return $ticket->update([
            'status' => 'redeemed',
            'redeemed_at' => now()->format('Y-m-d H:i:s'),
            'user_id' => $user->id,
        ]);
    }

    public function generateTickets(int $quantity, string $email): void
    {
        dispatch(new GenerateTicketsJob($quantity, $email));
    }

}
