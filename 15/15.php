<?php


declare(strict_types=1);

require_once '../vendor/autoload.php';

use Anarchitecture\pipe as p;

function ingredient(string $ingredient) : array {
    return $ingredient
        |> p\preg_match_all("/(-?\\d+)/")
        |> array_first(...)
        |> p\array_map(intval(...))
        |> array_values(...);
}

function score(array $recipe, array $ingredients, ?int $calorie_target) : int {

    $sums = [];

    foreach ($recipe as $ingredient => $amount) {

        foreach ($ingredients[$ingredient] as $property => $change) {

            $sums[$property] ??= 0;
            $sums[$property] += $amount * $change;
        }
    }

    $calories = array_pop($sums);

    if (is_int($calorie_target) && $calories !== $calorie_target) {
        return 0;
    }

    return $sums
        |> p\array_map(fn ($sum) => max(0, $sum))
        |> array_product(...);
}

function best(array $ingredients, ?int $calorie_target = null) : int {

    return $ingredients
        |> p\iterable_allocate(100)
        |> p\iterable_map(fn($recipe) => score($recipe, $ingredients, $calorie_target))
        |> p\iterable_reduce(fn ($max, $score) => max($max, $score), 0)
        |> intval(...);
}

/** @var array $ingredients */
$ingredients = file_get_contents('input')
    |> p\explode(PHP_EOL)
    |> p\array_map(ingredient(...));

echo best($ingredients) . PHP_EOL;
echo best($ingredients, 500) . PHP_EOL;