<?php

declare(strict_types=1);

namespace Krok\Glide\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string makeImage(string $path, array $params)
 */
class Glide extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'glide';
    }
}
