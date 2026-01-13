<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use Anarchitecture\pipe as p;

function ingredient(string $ingredient) : array {
    return $ingredient
        |> p\preg_match_all("/([a-z]+) (-?\d+)/", PREG_SET_ORDER)
        |> p\array_reduce(fn ($properties, $property) => [$property[1] => $property[2]] + $properties, [])
        |> p\array_map(intval(...))
        |> p\collect(...);
}

function cookie(array $recipe, array $ingredients) : array {
    return $recipe
        |> p\iterable_zip($ingredients)
        |> p\iterable_map(p\apply(fn ($amount, $ingredient) => $ingredient |> p\array_map(fn ($property) => $amount * $property)))
        |> p\collect(...)
        |> p\array_transpose()
        |> p\array_map(fn ($property) => max(0, array_sum($property)))
        |> p\collect(...);
}

function score(array $cookie) : int {
    return $cookie
        |> p\array_dissoc("calories")
        |> array_product(...)
        |> intval(...);
}

function best(array $ingredients, int $teaspoons, ?int $calorie_target = null) : int {
    return $ingredients
        |> p\iterable_allocate($teaspoons)
        |> p\iterable_map(fn($recipe) => cookie($recipe, $ingredients))
        |> p\iterable_filter(fn ($properties) => !is_int($calorie_target) || $properties["calories"] === $calorie_target)
        |> p\iterable_map(score(...))
        |> p\iterable_reduce(fn ($max, $score) => max($max, $score), 0)
        |> intval(...);
}

/** @var array $ingredients */
$ingredients = file_get_contents('input')
    |> p\explode(PHP_EOL)
    |> p\array_map(ingredient(...));

echo best($ingredients, 100) . PHP_EOL;
echo best($ingredients, 100, 500) . PHP_EOL;