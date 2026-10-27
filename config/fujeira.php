<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Host de la vista previa v5
    |--------------------------------------------------------------------------
    |
    | En fujeiraretrostore.com el catálogo nuevo se ve en /v5.
    | v5.fujeiraretrostore.com muestra esa vista en /, /catalogo y
    | /producto/{codigo}, y ahí no hay checkout.
    |
    */

    'v5_hosts' => array_values(array_filter(array_map(
        static fn (string $host): string => strtolower(trim($host)),
        explode(',', (string) env('FUJEIRA_V5_HOSTS', 'v5.fujeiraretrostore.com'))
    ))),

];
