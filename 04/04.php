<?php

require_once '../vendor/autoload.php';

use Anarchitecture\pipe as p;

$key = file_get_contents('input');

function find(string $key, string $needle) : string {
    return p\iterable_ticker()
            |> p\iterable_filter(fn($number) => str_starts_with(md5($key . $number), $needle))
            |> p\iterable_current();
}

$number = find($key, "00000");
$number2 = find($key, "000000");

echo $number . PHP_EOL;
echo $number2 . PHP_EOL;