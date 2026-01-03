<?php

require_once 'vendor/autoload.php';

use Anarchitecture\pipe as p;

$input = file_get_contents('input')
    |> p\explode(PHP_EOL)
    |> p\array_map(fn ($present) => $present |> p\explode('x') |> p\sort());

$paper = $input
    |> p\array_map((fn ($d) => 3 * $d[0] * $d[1] + 2 * $d[1] * $d[2] + 2 * $d[2] * $d[0]))
    |> array_sum(...);

$ribbon = $input
    |> p\array_map((fn ($s) => 2 * $s[0] + 2 * $s[1] + array_product($s)))
    |> array_sum(...);

echo $paper . PHP_EOL;
echo $ribbon . PHP_EOL;