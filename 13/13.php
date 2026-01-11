<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use Anarchitecture\pipe as p;

function attendees(array $attendees, string $change) : array {
    preg_match("/([A-Z][a-z]*) would (lose|gain) (\\d*) happiness units by sitting next to ([A-Z][a-z]*)\./", $change, $matches);

    [,$a, $change, $amount, $b] = $matches;

    $attendees[$a][$b] = $change === "gain" ? $amount : -$amount;

    return $attendees;
}

function happiness(iterable $arrangement, array $attendees, bool $add_me) : int {

    return $arrangement
        |> p\iterable_window(2, true)
        |> p\iterable_map(p\apply(fn ($a, $b) => $attendees[$a][$b] + $attendees[$b][$a]))
        |> iterator_to_array(...)
        |> p\when(p\value($add_me), add_me(...))
        |> array_sum(...);
}

function optimal_seating(array $attendees, bool $add_me = false) : int {

    return $attendees
        |> array_keys(...)
        |> p\iterable_permutation(...)
        |> p\iterable_map(fn ($arrangement) => happiness($arrangement, $attendees, $add_me))
        |> iterator_to_array(...)
        |> max(...);
}

function add_me(array $increases) : array { // @todo pipe this
    rsort($increases);
    array_pop($increases); // @todo tail function

    return $increases;
}

/** @var array $attendees */
$attendees = file_get_contents('input')
    |> p\explode(PHP_EOL)
    |> p\array_reduce(attendees(...), []);

echo optimal_seating($attendees) . PHP_EOL;
echo optimal_seating($attendees, true) . PHP_EOL;