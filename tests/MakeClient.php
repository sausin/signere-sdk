<?php

namespace Sausin\Signere\Tests;

use GuzzleHttp\Client;
use BadMethodCallException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;

trait MakeClient
{
    /**
     * Create and return the guzzle mock client.
     *
     * @param  string|array $content
     * @param  int          $number
     * @param  bool|bool $reuse
     * @return \GuzzleHttp\Client
     */
    protected function makeClient($content, int $number = 1, bool $reuse = true)
    {
        // if not going to reuse, then the setup should match
        if ($reuse === false && $number !== count($content) && $number > 1) {
            throw new BadMethodCallException('Invalid data', 1);
        }

        $responses = [];

        for ($i = 0; $i < $number; $i++) {
            $responses[] = new Response(200, [], $reuse ? $content : $content[$i]);
        }

        // setup a mock handler
        $mock = new MockHandler($responses);

        // setup the client
        $handler = HandlerStack::create($mock);

        return new Client(['handler' => $handler]);
    }
}
