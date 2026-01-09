<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use Anarchitecture\pipe as p;

function include_all(array|object $data) : array {
    return (array) $data;
}

function exclude_red(array|object $data) : array {
    return match (is_object($data)) {
        true => in_array("red", (array) $data, true) ? [] : (array) $data,
        default => $data
    };
}

function sum(mixed $data, callable $policy) : int {
    return match (true) {
        is_int($data) || is_float($data) => (int) $data,
        is_array($data) || is_object($data) => $data
            |> $policy
            |> array_values(...)
            |> p\array_map(fn ($item) => sum($item, $policy))
            |> array_sum(...),
        default => 0
    };
}

$input = file_get_contents('input')
    |> json_decode(...);

$policies = [
    include_all(...),
    exclude_red(...)
];

foreach ($policies as $policy) {
    echo sum($input, $policy) . PHP_EOL;
}