<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use Anarchitecture\pipe as p;

const NEIGHBOURS = [
    [-1, -1], [-1, 0], [-1, 1],
    [0, -1], [0, 1],
    [1, -1], [1, 0], [1, 1]
];

function corners_always_on(array $grid) : array {

    $min_y = array_key_first($grid);
    $max_y = array_key_last($grid);
    $min_x = array_key_first($grid[$min_y]);
    $max_x = array_key_last($grid[$max_y]);

    $grid[$min_y][$min_x] = "@";
    $grid[$min_y][$max_x] = "@";
    $grid[$max_y][$min_x] = "@";
    $grid[$max_y][$max_x] = "@";

    return $grid;
}

function count_on(array $grid, int $steps): int {
    return $grid
        |> p\iterate(step(...))
        |> p\iterable_nth($steps)
        |> p\array_flatten(...)
        |> p\array_filter(is_on(...))
        |> count(...);
}

function is_on(?string $value) : bool {
    return $value === "#" || $value === "@";
}

function step(array $grid) : array {
    return $grid
        |> p\array_map_recursive_with_path(fn ($value, $path) => state($grid, $value, ...$path))
        |> p\collect(...);
}

function state(array $grid, string $value, int $y, int $x) : string {
    $on = NEIGHBOURS
        |> p\array_map(p\apply(fn ($dy, $dx) => $grid[$y + $dy][$x + $dx] ?? null))
        |> p\array_filter(is_on(...))
        |> count(...);

    return match ($value) {
        "." => $on === 3 ? "#" : ".",
        "#" => $on === 2 || $on === 3 ? "#" : ".",
        default => $value
    };
}

$grid = file_get_contents('input')
    |> trim(...)
    |> p\explode(PHP_EOL)
    |> p\array_map(str_split(...))
    |> p\collect(...);

echo count_on($grid, 100) . PHP_EOL;
echo count_on($grid |> corners_always_on(...), 100) . PHP_EOL;