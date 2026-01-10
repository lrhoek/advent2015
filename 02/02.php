<?php

require_once '../vendor/autoload.php';

use Anarchitecture\pipe as p;

$input = file_get_contents('input')
    |> p\explode(PHP_EOL)
    |> p\array_map(p\explode('x'))
    |> p\array_map(p\sort());

$paper = $input
    |> p\array_map(p\apply(fn ($d1, $d2, $d3) => 3 * $d1 * $d2 + 2 * $d2 * $d3 + 2 * $d3 * $d1))
    |> array_sum(...);

$ribbon = $input
    |> p\array_map(p\apply(fn ($d1, $d2, $d3) => 2 * $d1 + 2 * $d2 + $d1 * $d2 * $d3))
    |> array_sum(...);

echo $paper . PHP_EOL;
echo $ribbon . PHP_EOL;