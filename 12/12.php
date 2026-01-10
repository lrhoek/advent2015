<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use Anarchitecture\pipe as p;

function include_all(object $data) : array {
    return $data
        |> get_object_vars(...);
}

function exclude_red(object $data) : array {
    return $data
        |> get_object_vars(...)
        |> p\when(p\array_any(p\equals("red")), p\value([]))
        |> array_values(...);
}

function sum($data, callable $normalizer) : int {
    return $data
        |> p\when(is_object(...), $normalizer)
        |> p\when(is_array(...), p\array_sum(fn ($x) => sum($x, $normalizer)))
        |> intval(...);
}

$input = file_get_contents('input')
    |> json_decode(...);

echo sum($input, include_all(...)) . PHP_EOL;
echo sum($input, exclude_red(...)) . PHP_EOL;