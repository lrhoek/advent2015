<?php
require_once '../vendor/autoload.php';

use Anarchitecture\pipe as p;

const INITIAL = [[0, 0]];
const MOVER_COUNT = 2;
const MOVES = [
    "^" => [1, 0],
    "v" => [-1, 0],
    ">" => [0, 1],
    "<" => [0, -1]
];

function move(array $visited, string $move) : array {
    $visited[] = [array_last($visited), MOVES[$move]]
        |> p\zip_map(fn ($da, $db) => $da + $db);

    return $visited;
}

function multimove(array $movers, array $moves) : array {
    return [$movers, $moves] |> p\zip_map(move(...));
}

$input = file_get_contents('input')
    |> str_split(...);

$visited = $input
    |> p\array_reduce(move(...), INITIAL)
    |> p\array_unique(SORT_REGULAR)
    |> count(...);

$multivisited = $input
    |> p\array_chunk(MOVER_COUNT)
    |> p\array_reduce(multimove(...), array_fill(0, MOVER_COUNT, INITIAL))
    |> (fn ($movers) => array_merge(...$movers))
    |> p\array_unique(SORT_REGULAR)
    |> count(...);

echo $visited . PHP_EOL;
echo $multivisited . PHP_EOL;