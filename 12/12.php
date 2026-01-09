<?php

require_once '../vendor/autoload.php';

use Anarchitecture\pipe as p;

function exclude_red($data) : array {
    return in_array("red", $data) ? [] : array_values($data);
}

function get(?callable $transformer = null) {

    $transformer ??= array_values(...);

    return function (mixed $data) use ($transformer) {
        $data = is_object($data) ? get_object_vars($data) |> $transformer : $data;

        return match (true) {
            is_numeric($data) => $data,
            is_array($data) => $data
                |> p\array_map(get($transformer))
                |> array_sum(...),
            default => 0
        };
    };
}

$input = file_get_contents('input')
    |> json_decode(...);

$transformers = [null, exclude_red(...)]
    |> p\iterable_map(fn ($transformer) => get($transformer)($input));

foreach ($transformers as $result) {
    echo $result . PHP_EOL;
}