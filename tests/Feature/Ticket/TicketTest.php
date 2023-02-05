<?php

namespace Tests\Feature\Ticket;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUserCanVisitTicketRedemptionPage()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->get(route('tickets.redeem'));

        $response->assertOk();
    }

    public function testUserCanRedeemTicket()
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory()->create();

        $response = $this
            ->withoutMiddleware()
            ->actingAs($user)
            ->patch(route('tickets.process-redemption'), [
                'code' => $ticket->code,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('tickets.redeem'));

        $ticket->refresh();

        $this->assertSame('redeemed', $ticket->status, "Ticket must be marked as redeemed after it is redeemed");
        $this->assertNotNull($ticket->redeemed_at, "Ticket must have a redeemed date time after it is redeemed");
    }

    public function testUserCanViewRedemptionHistory() {
        $user = User::factory()->create();

        $response = $this
            ->withoutMiddleware()
            ->actingAs($user)
            ->get(route('tickets.redemption-history'));

        $response->assertOk();
    }

    public function testRateLimiterBlocksExcessRequests() {
        $user = User::factory()->create();
        $ticket = Ticket::factory()->create();

        foreach(range(1, 5) as $i) {
            $this
                ->actingAs($user)
                ->patch(route('tickets.redeem'), [
                    'code' => $ticket->code,
                ])
                ->assertRedirect(route('tickets.redeem'))
                ->assertHeader('X-Ratelimit-Remaining', 5 - $i);
        }

        $this
            ->actingAs($user)
            ->patch(route('tickets.redeem'), [
                'code' => $ticket->code,
            ])
            ->assertStatus(429);
    }

    public function testTicketsCanBeGenerated() {
        $user = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($user)
            ->post(route('tickets.process-ticket-generation', [
                'number_of_tickets' => 10,
            ]));

        $this->assertSame(10, Ticket::count(), 'There must be 10 tickets generated');
    }

}
