<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use Anarchitecture\pipe as p;

$n = file_get_contents('input')
    |> p\preg_match_all("/\d+/")
    |> array_first(...)
    |> p\apply(fn ($y, $x) => intdiv(($y + $x - 2) * ($y + $x - 1), 2) + $x)
    |> intval(...);

$code = 20151125
    |> p\iterate(fn ($code) => ($code * 252533) % 33554393)
    |> p\iterable_nth($n - 1)
    |> intval(...);

echo $code . PHP_EOL;