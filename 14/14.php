<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use Anarchitecture\pipe as p;


function reindeer(string $reindeer) : array {
    return $reindeer
        |> p\preg_match("/(\d+) .* (\d+) .* (\d+)/")
        |> p\array_slice(1)
        |> array_values(...);
}

function fly($reindeer, $seconds) : int {

    [$speed, $flying_limit, $resting] = $reindeer;
    $distance = 0;

    while ($seconds > 0) {
        $distance += min($seconds, $flying_limit) * $speed;
        $seconds -= $flying_limit + $resting;
    }

    return $distance;
}

function distances($herd, $seconds) : array {
    return $herd
        |> p\array_map(fn ($reindeer) => fly($reindeer, $seconds))
        |> array_values(...);
}

function points($herd, $seconds) : array {

    $points = $herd
        |> p\array_map(p\value(0))
        |> array_values(...);

    while ($seconds > 0) {
        $distances = distances($herd, $seconds);

        $leaders = $distances
            |> p\array_filter(p\equals(max($distances)))
            |> array_keys(...);

        foreach ($leaders as $reindeer) {
            $points[$reindeer]++;
        }

        $seconds--;
    }

    return $points;
}

function winner(array $reindeer, callable $system, int $seconds) : int {
    return $system($reindeer, $seconds)
        |> max(...);
}

$herd = file_get_contents('input')
    |> p\explode(PHP_EOL)
    |> p\array_map(reindeer(...))
    |> array_values(...);

echo winner($herd, distances(...), 2503) . PHP_EOL;
echo winner($herd, points(...), 2503) . PHP_EOL;