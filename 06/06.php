<?php

require_once '../vendor/autoload.php';

use Anarchitecture\pipe as p;

function instruction(string $instruction) : array {
    [$task, $coordinates] = $instruction
        |> p\str_replace([" o", " through "], ["o", ','])
        |> p\explode(" ");

    $coordinates = explode(",", $coordinates)
        |> p\array_map(intval(...));

    return [$task, $coordinates];
}

function coordinates($ay, $ax, $by, $bx) : Generator {
    for ($y = $ay; $y <= $by; $y++) {
        for ($x = $ax; $x <= $bx; $x++) {
            yield $y . ":" . $x;
        }
    }
}

function apply(callable $rules) : Closure {
    return function (array $grid, array $instruction) use ($rules) : array {
        [$instruction, $coordinates] = $instruction;

        foreach (coordinates(...$coordinates) as $coordinate) {
            $grid[$coordinate] = $rules($instruction, $grid[$coordinate] ?? 0);
        }

        return $grid;
    };
}

function on_off(string $instruction, bool $val) : bool {
    return match($instruction) {
        "turnon" => true,
        "turnoff" => false,
        "toggle" => !$val
    };
}

function brightness(string $instruction, int $val) : int {
    return match($instruction) {
        "turnon" => $val + 1,
        "turnoff" => max($val - 1, 0),
        "toggle" => $val + 2
    };
}

function solve(array $instructions, callable $rules) : int {
    return $instructions
        |> p\array_reduce(apply($rules), [])
        |> array_sum(...);
}

$instructions = file_get_contents('input')
    |> p\explode(PHP_EOL)
    |> p\array_map(instruction(...))
    |> array_values(...);

echo solve($instructions, on_off(...)) . PHP_EOL;
echo solve($instructions, brightness(...)) . PHP_EOL;