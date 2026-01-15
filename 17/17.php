<?php

require_once '../vendor/autoload.php';

use Anarchitecture\combinatorics as c;
use Anarchitecture\pipe as p;

function count_minimum(iterable $containers) : int {
    $counts = $containers
        |> p\iterable_map(count(...))
        |> p\collect(...);

    return $counts
        |> p\array_filter(p\equals(min($counts)))
        |> count(...);
}

$containers = file_get_contents('input')
    |> trim(...)
    |> p\explode(PHP_EOL)
    |> p\array_map(intval(...))
    |> c\iterable_powerset()
    |> p\iterable_filter(fn ($combination) => array_sum($combination) === 150)
    |> p\collect(...);

echo count($containers) . PHP_EOL;
echo count_minimum($containers) . PHP_EOL;