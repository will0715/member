<?php

namespace App\Listeners;

class MemberEventSubscriber
{
    public function handleMemberLogin($event) {}

    public function handleNewMember($event) {
        $customer = $event->customer;
        $member = $event->member;
        // TODO: 新增會員後的動作
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\MemberLogin',
            'App\Listeners\MemberEventSubscriber@handleUserLogin'
        );

        $events->listen(
            'App\Events\MemberRegistered',
            'App\Listeners\MemberEventSubscriber@handleNewMember'
        );
    }
}