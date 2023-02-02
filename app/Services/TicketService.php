<?php

namespace App\Services;

use App\Models\Ticket;
use Log;

class TicketService
{

    public function redeemTicket(string $code): ?Ticket
    {
        $ticket = Ticket::where('code', '=', $code)
            ->where('status', '=', 'not_redeemed')
            ->first();

        if(is_null($ticket)) {
            return null;
        }

        if($this->markAsRedeemed($ticket)) {
            $ticket->refresh();
            return $ticket;
        } else {
            Log::error("Marking code as redeemed failed", ['code' => $ticket]);

            return null;
        }
    }

    public function markAsRedeemed(Ticket $ticket): bool
    {
        return $ticket->update([
            'status' => 'redeemed',
            'redeemed_at' => now()->format('Y-m-d H:i:s')
        ]);
    }

}
