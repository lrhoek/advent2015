<?php

require_once '../vendor/autoload.php';

use Anarchitecture\pipe as p;

function compounds(string $compounds) : array {
    return $compounds
        |> p\preg_match_all("/(?<name>[a-z]+): (?<amount>\d+)/", PREG_SET_ORDER)
        |> p\array_map(fn ($match) => [$match["name"] => (int) $match["amount"]])
        |> p\array_flatten(...);
}

function aunt(string $aunt): array {
    return $aunt
        |> p\preg_match('/(?<id>Sue \d+): (?<compounds>.*)/')
        |> (fn ($aunt) => [$aunt['id'] => compounds($aunt["compounds"])]);
}

function matching_properties(array $aunt, array $compounds) : array {
    return $aunt
        |> p\iterable_filter(fn ($amount, $name) => $compounds[$name] === $amount)
        |> p\collect(...);
}

function matching_property_ranges(array $aunt, array $compounds) : array {
    return $aunt
        |> p\iterable_filter(fn ($amount, $name) =>
            (!in_array($name, ["cats", "trees", "pomeranians", "goldfish"]) && $compounds[$name] === $amount) ||
            (in_array($name, ["cats", "trees"]) && $compounds[$name] < $amount) ||
            (in_array($name, ["pomeranians", "goldfish"]) && $compounds[$name] > $amount))
        |> p\collect(...);
}

function find(array $aunts, array $compounds, callable $matcher) : string {
    return $aunts
        |> p\iterable_map(fn($aunt) => $matcher($aunt, $compounds))
        |> p\collect(...)
        |> p\uasort(fn($aunt1, $aunt2) => count($aunt2) <=> count($aunt1))
        |> array_key_first(...);
}

$compounds = file_get_contents('message')
    |> trim(...)
    |> compounds(...);

$aunts = file_get_contents('input')
    |> trim(...)
    |> p\explode(PHP_EOL)
    |> p\array_map(aunt(...))
    |> p\array_flatten(...)
    |> p\collect(...);

echo find($aunts, $compounds, matching_properties(...)) . PHP_EOL;
echo find($aunts, $compounds, matching_property_ranges(...)) . PHP_EOL;