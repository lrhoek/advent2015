<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use Anarchitecture\combinatorics as c;
use Anarchitecture\pipe as p;

function properties(string $line) : array {
    return $line
        |> p\preg_match_all("/\s\d+/")
        |> array_first(...)
        |> p\array_map(intval(...))
        |> p\collect(...);
}

function category(string $category) : array {
    return $category
        |> p\explode(PHP_EOL)
        |> p\array_slice(1)
        |> p\array_map(properties(...))
        |> p\collect(...);
}

function equipment(array $weapons, array $armors, array $rings) : Generator {
    foreach ($weapons as $weapon) {
        foreach ([...$armors, [0, 0, 0]] as $armor) {
            foreach ([...$rings, [0, 0, 0], [0, 0, 0]] |> c\iterable_combinations(2) as $fingers) {

                yield [$weapon, $armor, ...$fingers]
                    |> p\array_transpose()
                    |> p\array_map(array_sum(...));
            }
        }
    }
}

function fight(int $hp, array $equipment, array $boss) : bool {
    [$damage, $armor] = $equipment
        |> p\array_slice(1);

    [$boss_hp, $boss_damage, $boss_armor] = $boss;

    $damage_given = max(1, $damage - $boss_armor);
    $damage_taken = max(1, $boss_damage - $armor);

    return ceil($hp / $damage_taken) >= ceil($boss_hp / $damage_given);
}

function cost(array $shop, int $hp, array $boss, bool $win) : int {
    return $shop
        |> p\apply(equipment(...))
        |> p\iterable_filter(fn($equipment) => !$win XOR fight($hp, $equipment, $boss))
        |> p\iterable_map(p\apply(fn($cost) => $cost))
        |> p\collect(...)
        |> p\if_else(p\value($win), p\sort(), p\rsort())
        |> array_first(...)
        |> intval(...);
}

$boss = file_get_contents('input')
    |> trim(...)
    |> properties(...);

$shop = file_get_contents('shop')
    |> trim(...)
    |> p\explode(PHP_EOL . PHP_EOL)
    |> p\array_map(category(...))
    |> p\collect(...);

echo cost($shop, 100, $boss, true) . PHP_EOL;
echo cost($shop, 100, $boss, false) . PHP_EOL;
