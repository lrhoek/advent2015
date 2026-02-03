<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use Anarchitecture\pipe as p;

function instruction(string $instruction): array {

    return $instruction
        |> p\preg_match_all("/(?<op>[a-z]{3}) (?<target>[ab])?,? ?\+?(?<amount>-?\d+)?/", PREG_SET_ORDER)
        |> p\array_flatten(...)
        |> p\array_dissoc(0, 1, 2, 3)
        |> p\collect(...);
}

function run(array $instructions, array $registers = ['a' => 0, 'b' => 0], int $current = 0): int {

    if (!isset($instructions[$current])) {
        return $registers["b"];
    }

    $instruction = $instructions[$current];

    $offset = 1;

    if ($instruction["op"] === "hlf") {
        $registers[$instruction["target"]] /= 2;
    }

    if ($instruction["op"] === "tpl") {
        $registers[$instruction["target"]] *= 3;
    }

    if ($instruction["op"] === "inc") {
        $registers[$instruction["target"]] += 1;
    }

    if ($instruction["op"] === "jie" && $registers[$instruction["target"]] % 2 === 0) {
        $offset = $instruction["amount"];
    }

    if ($instruction["op"] === "jio" && $registers[$instruction["target"]] === 1) {
        $offset = $instruction["amount"];
    }

    if ($instruction["op"] === "jmp") {
        $offset = $instruction["amount"];
    }

    return run($instructions, $registers, $current + $offset);

}

$instructions = file_get_contents('input')
    |> trim(...)
    |> p\explode(PHP_EOL)
    |> p\array_map(instruction(...))
    |> p\collect(...);

echo run($instructions) . PHP_EOL;
echo run($instructions, ["a" => 1, "b" => 0]) . PHP_EOL;