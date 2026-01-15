<?php

require_once '../vendor/autoload.php';

use Anarchitecture\combinatorics as c;
use Anarchitecture\pipe as p;

function paths(array $paths, string $route) : array {
    [$a, $b, $distance] = preg_split("/ (to|=) /", $route);

    $paths[$a][$b] = (int) $distance;
    $paths[$b][$a] = (int) $distance;

    return $paths;
}

function total_distance(array $paths, array $route) : int {
    return $route
        |> p\iterable_window(2)
        |> p\iterable_map(p\apply(fn ($from, $to) => $paths[$from][$to]))
        |> iterator_to_array(...)
        |> array_sum(...);
}

function distance(array $paths, callable $selector) : int {
    return array_keys($paths)
        |> c\iterable_permutations()
        |> p\iterable_map(fn ($route) => total_distance($paths, $route))
        |> p\iterable_reduce(fn ($carry, $distance) => $selector($carry ?? $distance, $distance))
        |> intval(...);
}

/** @var array $paths */
$paths = file_get_contents('input')
    |> p\explode(PHP_EOL)
    |> p\array_reduce(paths(...), []);

echo distance($paths, min(...)) . PHP_EOL;
echo distance($paths, max(...)) . PHP_EOL;