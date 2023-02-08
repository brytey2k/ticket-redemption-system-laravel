<?php

namespace App\Jobs;

use App\Mail\TicketsGenerated;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;
use Str;

class GenerateTicketsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 1200;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public int $total, public string $email, public string $parentJobId = '')
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->parentJobId === '') {
            $this->parentJobId = $this->job->getJobId();
        }
        info('['. __CLASS__ . '] ' . 'Ticket creation job started. Job ID: ' . $this->parentJobId);

        $counter = 0;

        for($i = 0; $i < $this->total; $i++) {
            $counter++;

            Ticket::create([
                'status' => 'not_redeemed',
                'code' => Str::random(10),
            ]);

            // if we still have more after 100, send it to the next job, so we don't get memory leaks or timeout
            if($counter === config('tickets.numberToGeneratePerJobRun') && $this->total - $counter > 0) {
                dispatch(new static($this->total - $counter, $this->email, $this->parentJobId));
                return; // we are not breaking but aborting the function completely
            }
        }

        // at this point, all tickets have been generated... notify user by email
        // send email
        Mail::to($this->email)->queue(new TicketsGenerated());
        info('['. __CLASS__ . '] ' . 'Ticket creation job completed. Job ID: ' . $this->parentJobId);
    }
}
