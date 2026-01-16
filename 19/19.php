<?php

require_once '../vendor/autoload.php';

use Anarchitecture\pipe as p;

[$replacements, $molecule] = file_get_contents('input')
    |> trim(...)
    |> p\explode(PHP_EOL . PHP_EOL)
    |> p\collect(...);

$replacements = $replacements
    |> p\explode(PHP_EOL)
    |> p\array_map(p\explode(" => "))
    |> p\collect(...);

$distinct = replaced($replacements, $molecule);

echo count($distinct) . PHP_EOL;

// @TODO Iterate over this for part 2: molecule[] => molecule[][] => flatten
function replaced(array $replacements, string $molecule) : array {
    return $replacements
        |> p\array_map(p\apply(fn($token, $replacement) => replace($molecule, $token, $replacement) |> p\collect(...)))
        |> p\array_flatten(...)
        |> p\array_unique()
        |> p\collect(...);
}

function replace($molecule, $token, $replacement) : Generator {

    $molecule = explode($token, $molecule);

    if (count($molecule) === 1) {
        return;
    }

    $head = array_shift($molecule);

    $replaced = $molecule;

    $third = array_shift($replaced);

    array_unshift($replaced, $head . $replacement . $third);

    yield join($token, $replaced);

    foreach (replace(join($token, $molecule), $token, $replacement) as $rest) {
        yield $head . $token . $rest;
    }

}
