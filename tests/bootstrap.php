<?php

$root = dirname(__DIR__);

if (! is_file($root.'/.env')) {
    copy($root.'/.env.example', $root.'/.env');
}

require $root.'/vendor/autoload.php';
