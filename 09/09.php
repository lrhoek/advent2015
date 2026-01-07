<?php

require_once '../vendor/autoload.php';

use Anarchitecture\pipe as p;

function paths(array $paths, string $route) : array {
    [$locations, $distance] = explode(" = ", $route);
    [$a, $b] = explode(" to ", $locations);

    $paths[$a][$b] = (int) $distance;
    $paths[$b][$a] = (int) $distance;

    return $paths;
}

function total_distance(array $paths, array $route) : int {
    return [array_slice($route, 0, -1), array_slice($route, 1)]
        |> p\zip_map(fn ($from, $to) => $paths[$from][$to])
        |> array_sum(...);
}

function distance(array $paths, callable $selector) : int {
    return array_keys($paths)
        |> p\iterable_permutation(...)
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