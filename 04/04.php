<?php

require_once '../vendor/autoload.php';

use Anarchitecture\pipe as p;

$key = file_get_contents('input');

function find(string $key, string $needle) : int {
    return p\iterable_ticker()
        |> p\iterable_filter(fn($number) => $key.$number |> md5(...) |> p\str_starts_with($needle))
        |> p\iterable_first(...);
}

echo find($key, "00000") . PHP_EOL;
echo find($key, "000000") . PHP_EOL;