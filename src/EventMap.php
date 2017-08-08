<?php

namespace Sausin\Signere;

trait EventMap
{
    /**
     * All of the Signere event / listener mappings.
     *
     * @var array
     */
    protected $events = [
        'Sausin\Signere\Events\JobPushed' => [
            'Sausin\Signere\Listeners\StoreJob',
            'Sausin\Signere\Listeners\StoreMonitoredTags',
        ],
    ];
}
