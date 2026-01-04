<?php

require_once '../vendor/autoload.php';

use Anarchitecture\pipe as p;

function rule_vowels(string $string) : bool {
    preg_match_all('/[aeiou]/', $string, $matches);
    return count($matches[0]) >= 3;
}

function rule_twice(string $string) : bool {
    return str_split($string)
        |> p\array_unique()
        |> p\array_any(fn ($char) => str_contains($string, $char . $char))
        |> boolval(...);
}

function rule_forbidden(string $string) : bool {
    return ["ab", "cd", "pq", "xy"]
        |> p\array_all(fn ($word) => !str_contains($string, $word))
        |> boolval(...);
}

function rule_pairs(string $string) : bool {
    return range(0, strlen($string) - 2)
        |> p\array_map(fn ($char) => substr($string, $char, 2))
        |> p\array_any(fn ($pair) => substr_count($string, $pair) > 1)
        |> boolval(...);
}

function rule_sandwich(string $string) : bool {
    return range(0, strlen($string) - 3)
        |> p\array_any(fn ($char) => substr($string, $char, 1) === substr($string, $char + 2, 1))
        |> boolval(...);
}

$input = file_get_contents('input')
    |> p\explode(PHP_EOL);

$nice = $input
    |> p\iterable_filter(rule_vowels(...))
    |> p\iterable_filter(rule_twice(...))
    |> p\iterable_filter(rule_forbidden(...))
    |> iterator_count(...);

$nicer = $input
    |> p\iterable_filter(rule_pairs(...))
    |> p\iterable_filter(rule_sandwich(...))
    |> iterator_count(...);

echo $nice . PHP_EOL;
echo $nicer . PHP_EOL;