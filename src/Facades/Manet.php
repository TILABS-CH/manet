<?php

declare(strict_types=1);

namespace Tilabs\Manet\Facades;

use Illuminate\Support\Facades\Facade;

class Manet extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'manet';
    }
}
