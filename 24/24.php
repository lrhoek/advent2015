<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use Anarchitecture\pipe as p;
use Anarchitecture\combinatorics as c;

function arrange(array $weights, int $compartments) {

    for ($i = 1;; $i++) {

        $qe = $weights
            |> c\iterable_combinations($i)
            |> p\iterable_filter(fn ($combination) => array_sum($combination) === array_sum($weights) / $compartments)
            |> p\iterable_map(array_product(...))
            |> p\collect(...)
            |> p\sort()
            |> array_first(...);

        if ($qe !== null) {
            return $qe;
        }

    }

}

$weights = file_get_contents('input')
    |> trim(...)
    |> p\preg_match_all("/\d+/")
    |> array_first(...)
    |> p\array_map(intval(...))
    |> p\collect(...);

echo arrange($weights, 3) . PHP_EOL;
echo arrange($weights, 4) . PHP_EOL;