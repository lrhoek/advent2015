<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use Anarchitecture\pipe as p;

function include_all(object $data) : array {
    return get_object_vars($data);
}

function exclude_red(object $data) : array {
    return $data
        |> get_object_vars(...)
        |> p\when(fn ($x) => in_array("red", $x, true), fn ($_) => [])
        |> array_values(...);
}

function sum($data, callable $normalizer) : int {
    return $data
        |> p\when(is_object(...), $normalizer)
        |> p\when(is_array(...), p\array_reduce(fn ($sum, $x) => $sum + sum($x, $normalizer), 0))
        |> intval(...);
}

$input = file_get_contents('input')
    |> json_decode(...);

echo sum($input, include_all(...)) . PHP_EOL;
echo sum($input, exclude_red(...)) . PHP_EOL;