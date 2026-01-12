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
    return [$recipe, $ingredients]
        |> p\zip_map(fn ($amount, $ingredient) => $ingredient |> p\array_map(fn ($property) => $amount * $property))
        |> p\array_transpose()
        |> p\array_map(fn ($property) => max(0, array_sum($property)))
        |> p\if_else(
            fn ($properties) => is_int($calorie_target) && array_last($properties) !== $calorie_target,
            fn ($properties) => ([array_key_last($properties) => 0] + $properties),
            fn ($properties) => ([array_key_last($properties) => 1] + $properties)
        )
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