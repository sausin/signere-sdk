<?php

namespace Sausin\Signere\Http\Controllers\Admin;

use Sausin\Signere\Events;
use Sausin\Signere\Http\Controllers\Controller;

class EventsController extends Controller
{
    /** @var \Sausin\Signere\Events */
    protected $events;

    /**
     * Create a new controller instance.
     *
     * @param  \Sausin\Signere\Events $events
     */
    public function __construct(Events $events)
    {
        parent::__construct();

        $this->events = $events;
    }

    /**
     * Returns the EventsQueue encryptionKey
     * as a base64 encoded string.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        return $this->events->getEncryptionKey()
                ->getBody()
                ->getContents();
    }
}
