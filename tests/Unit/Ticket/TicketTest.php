<?php

namespace Tests\Unit\Ticket;

use App\Jobs\GenerateTicketsJob;
use App\Models\Ticket;
use App\Models\User;
use App\Services\TicketService;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class TicketTest extends TestCase
{

    public function testNonRedeemedTicketsCanBeRedeemed() {
        $now = now();
        $ticketService = $this->createStub(TicketService::class);
        $ticketService->method('redeemTicket')
            ->willReturn(new Ticket([
                'status' => 'redeemed',
                'code' => 'TzJqodQT',
                'redeemed_at' => $now->format('Y-m-d H:i:s'),
                'user_id' => 1,
            ]));

        $ticket = $ticketService->redeemTicket('TzJqodQT', new User(['id' => 1]));
        $this->assertSame('redeemed', $ticket->getActualAttribute('status'), 'Ticket status must be "redeemed" after it is redeemed');
        $this->assertSame($now->format('Y-m-d H:i:s'), $ticket->redeemed_at, 'Ticket redeemed time must be set when ticket is redeemed');
    }

    public function testRedeemedTicketsCannotBeRedeemed() {
        $ticketService = $this->createStub(TicketService::class);
        $ticketService->method('redeemTicket')
            ->willReturn(null);

        $ticket = $ticketService->redeemTicket('TzJqodQT', new User(['id' => 1]));
        $this->assertSame(null, $ticket, 'Redeemed ticket must not be redeemable the second time');
    }

    public function testTicketsCanBeGenerated() {
        Bus::fake();

        $ticketService = new TicketService();
        $ticketService->generateTickets(5, 'admin@email.com');

        Bus::assertDispatched(GenerateTicketsJob::class);
    }

}
