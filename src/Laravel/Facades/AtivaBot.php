<?php

namespace AtivaBot\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use AtivaBot\Endpoints\Messages;
use AtivaBot\Endpoints\Contacts;
use AtivaBot\Endpoints\Groups;
use AtivaBot\Endpoints\Templates;
use AtivaBot\Endpoints\Sessions;

/**
 * @method static Messages messages()
 * @method static Contacts contacts()
 * @method static Groups groups()
 * @method static Templates templates()
 * @method static Sessions sessions()
 * @method static array request(string $method, string $endpoint, array $options = [])
 *
 * @see \AtivaBot\Client
 */
class AtivaBot extends Facade
{
    /**
     * Obter o nome registrado do componente no container.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'ativabot';
    }
}
