<?php

namespace Tests\Feature\Ticket;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $response = $this->actingAs($user)
            ->patch(route('tickets.redeem'), [
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

        $response = $this->actingAs($user)
            ->get(route('tickets.redemption-history'));

        $response->assertOk();
    }

}
