<?php

namespace App\Listeners;

class PrepaidCardEventSubscriber
{

    public function handlePrepaidTopup($event) {
        $customer = $event->customer;
        $member = $event->member;
        $topup = $event->topup;
        // TODO: 儲值後的動作
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {

        $events->listen(
            'App\Events\PrepaidCardTopup',
            'App\Listeners\PrepaidCardEventSubscriber@handlePrepaidTopup'
        );
    }
}