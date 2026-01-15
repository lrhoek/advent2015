<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use Anarchitecture\combinatorics as c;
use Anarchitecture\pipe as p;

function attendees(array $attendees, string $change) : array {

    [$a, $change, $amount, $b] = $change
        |> p\preg_match("/([A-Z][a-z]*) .* (lose|gain) (\\d*) .* ([A-Z][a-z]*)/")
        |> p\array_slice(1)
        |> array_values(...);

    $attendees[$a][$b] = $change === "gain" ? $amount : -$amount;

    return $attendees;
}

function happiness(iterable $arrangement, array $attendees, bool $add_me) : int {

    return $arrangement
        |> p\iterable_window(2, true)
        |> p\iterable_map(p\apply(fn ($left, $right) => $attendees[$left][$right] + $attendees[$right][$left]))
        |> iterator_to_array(...)
        |> p\when(p\value($add_me), add_me(...))
        |> array_sum(...);
}

function optimal_seating(array $attendees, bool $add_me = false) : int {

    return $attendees
        |> array_keys(...)
        |> c\iterable_permutations()
        |> p\iterable_map(fn ($arrangement) => happiness($arrangement, $attendees, $add_me))
        |> iterator_to_array(...)
        |> max(...);
}

function add_me(array $increases) : array {

    return $increases
        |> p\sort()
        |> p\array_slice(1)
        |> array_values(...);
}

/** @var array $attendees */
$attendees = file_get_contents('input')
    |> p\explode(PHP_EOL)
    |> p\array_reduce(attendees(...), []);

echo optimal_seating($attendees) . PHP_EOL;
echo optimal_seating($attendees, true) . PHP_EOL;