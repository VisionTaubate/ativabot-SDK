<?php

namespace AtivaBot\Endpoints;

use AtivaBot\Client;

abstract class AbstractEndpoint
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}
