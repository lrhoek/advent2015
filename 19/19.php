<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use Anarchitecture\pipe as p;

function steps_to(mixed $molecules, array $replacements, string $target, int $steps = 1) : int {
    return $molecules
        |> p\when(p\not(is_array(...)), fn ($molecule) => [$molecule])
        |> p\array_map(fn($molecule) => replaced($replacements, $molecule))
        |> p\array_flatten(...)
        |> p\array_unique()
        |> p\usort(fn($a, $b) => strlen($a) <=> strlen($b))
        |> p\array_slice(0, 1)
        |> p\collect(...)
        |> p\if_else(
            fn ($molecules) => in_array($target, $molecules),
            p\value($steps),
            fn ($molecules) => steps_to($molecules, $replacements, $target, $steps + 1)
        )
        |> intval(...);
}

function replaced(array $replacements, string $molecule) : array {
    return $replacements
        |> p\array_map(p\apply(fn($token, $replacement) => replace($molecule, $token, $replacement) |> p\collect(...)))
        |> p\array_flatten(...)
        |> p\array_unique()
        |> p\collect(...);
}

function replace(string $molecule, string $token, string $replacement) : Generator {

    $position = strpos($molecule, $token);

    if ($position === false) {
        return;
    }

    $head = substr($molecule, 0, $position);
    $tail = substr($molecule, $position + strlen($token));

    yield $head . $replacement . $tail;

    foreach (replace($tail, $token, $replacement) as $rest) {
        yield $head . $token . $rest;
    }
}

[$replacements, $molecule] = file_get_contents('input')
    |> trim(...)
    |> p\explode(PHP_EOL . PHP_EOL)
    |> p\collect(...);

$replacements = $replacements
    |> p\explode(PHP_EOL)
    |> p\array_map(p\explode(" => "))
    |> p\collect(...);

$distinct = replaced($replacements, $molecule);

$replacements = $replacements
    |> p\array_map(array_reverse(...))
    |> p\collect(...);

echo count($distinct) . PHP_EOL;
echo steps_to($molecule, $replacements, "e") . PHP_EOL;