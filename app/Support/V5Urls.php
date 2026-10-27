<?php

namespace App\Support;

class V5Urls
{
    public static function to(string $name, mixed $parameters = []): string
    {
        $prefix = V5Host::matches() ? 'v5.host.' : 'v5.';

        return route($prefix.$name, $parameters);
    }

    public static function catalogo(array $query = []): string
    {
        $query = array_filter(
            $query,
            fn ($value) => $value !== null && $value !== ''
        );

        return self::to('catalogo', $query);
    }
}
