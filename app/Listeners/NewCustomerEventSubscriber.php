<?php

namespace App\Listeners;

use Arr;
use Artisan;
use App\Services\CustomerService;

class NewCustomerEventSubscriber
{
    public function __construct()
    {
        $this->customerService = app(CustomerService::class);
    }

    public function handleNewCustomer($event) {
        $customer = $event->customer;
        $data = $event->data;
        
        $this->customerService->initCustomer($data);

    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\NewCustomer',
            'App\Listeners\NewCustomerEventSubscriber@handleNewCustomer'
        );
    }
}