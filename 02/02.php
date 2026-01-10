<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use Anarchitecture\pipe as p;

function wrapping_paper(int $x, int $y, int $z) : int {
    return (3 * $x * $y) + (2 * $y * $z) + (2 * $z * $x);
}

function ribbon(int $x, int $y, int $z) : int {
    return (2 * $x) + (2 * $y) + ($x * $y * $z);
}

function total(array $input, callable $mapper) : int {
    return $input
        |> p\array_map(p\sort())
        |> p\array_map(p\apply($mapper))
        |> array_sum(...);
}

function dimensions(string $input) : array {
    return $input
        |> p\explode('x')
        |> p\array_map(intval(...))
        |> array_values(...);
}

$input = file_get_contents('input')
    |> p\explode(PHP_EOL)
    |> p\array_map(dimensions(...))
    |> array_values(...);

echo total($input, wrapping_paper(...)) . PHP_EOL;
echo total($input, ribbon(...)) . PHP_EOL;