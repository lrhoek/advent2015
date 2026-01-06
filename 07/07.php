<?php

require_once '../vendor/autoload.php';

use Anarchitecture\pipe as p;

function wire(array $outputs, string $wire) : array {
    [$operation, $output] = explode(" -> ", $wire);

    $outputs[$output] = match (true) {
        str_contains($operation, "AND") => [
            explode(" AND ", $operation),
            fn($l, $r) => $l & $r
        ],
        str_contains($operation, "OR") => [
            explode(" OR ", $operation),
            fn($l, $r) => $l | $r],
        str_contains($operation, "LSHIFT") => [
            explode(" LSHIFT ", $operation),
            fn($l, $r) => $l << $r
        ],
        str_contains($operation, "RSHIFT") => [
            explode(" RSHIFT ", $operation),
            fn($l, $r) => $l >> $r
        ],
        str_starts_with($operation, "NOT") => [
            [substr($operation, 4)],
            fn($o) => (~(int) $o) & 0xFFFF
        ],
        default => [
            [$operation],
            fn($o) => $o
        ]
    };

    return $outputs;
}

function signal(array $circuit, string $search) : int {

    while (!isset($circuit[$search][2])) {

        $resolvable = $circuit
            |> p\iterable_filter(fn($wire) => array_all($wire[0], fn ($signal) => is_numeric($signal)))
            |> p\iterable_map(fn($wire) => $wire[1](...$wire[0]));

        foreach ($resolvable as $output => $signal) {
            $circuit[$output][2] = $signal;
        }

        $replaceable = $circuit
            |> p\iterable_filter(fn($wire) => isset($wire[2]))
            |> p\iterable_map(fn($wire) => $wire[2]);

        foreach ($replaceable as $candidate => $signal) {

            $replacements = $circuit
                |> p\iterable_filter(fn ($wire) => in_array($candidate, $wire[0]))
                |> p\iterable_map(fn ($wire) => $wire[0])
                |> p\iterable_map(p\array_map(fn ($operant) => $operant === $candidate ? $signal : $operant));

            foreach ($replacements as $output => $replacement) {
                $circuit[$output][0] = $replacement;
            }

        }
    }

    return $circuit[$search][2];
}

/** @var array $circuit */
$circuit = file_get_contents('input')
    |> p\explode(PHP_EOL)
    |> p\array_reduce(wire(...), []);

$first = signal($circuit, "a");

$circuit["b"] = [[$first], fn($o) => $o, $first];
$second = signal($circuit, "a");

echo $first . PHP_EOL;
echo $second . PHP_EOL;