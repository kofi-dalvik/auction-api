<?php

namespace App\Listeners;

use App\Events\BidCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProcessAutoBidding implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BidCreated  $event
     * @return void
     */
    public function handle(BidCreated $event)
    {
        if ($event->bidding->is_auto_bid) return;
    }
}
