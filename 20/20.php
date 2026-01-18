<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use MathPHP\Algebra as a;
use Anarchitecture\pipe as p;

function house (int $target, int $presents_per_house, ?int $presents_per_elf = null) : int {

    for ($house = 1, $presents = 0; $presents < $target / $presents_per_house; $house++) {
        $presents = $house
            |> a::factors(...)
            |> p\when(
                p\value($presents_per_elf !== null),
                p\array_filter(fn ($elf) => $house <= $elf * $presents_per_elf)
            )
            |> array_sum(...);
    }

    return $house - 1;
}

$target = file_get_contents('input')
    |> intval(...);

echo house($target, 10) . PHP_EOL;
echo house($target, 11, 50) . PHP_EOL;