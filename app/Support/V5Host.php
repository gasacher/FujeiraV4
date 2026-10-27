<?php

namespace App\Support;

use Illuminate\Http\Request;

class V5Host
{
    public const HOST = 'v5.fujeiraretrostore.com';

    public static function matches(?Request $request = null): bool
    {
        $host = ($request ?? request())->getHost();

        return strcasecmp($host, self::HOST) === 0;
    }
}
