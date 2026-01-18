<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use MathPHP\Algebra as a;

$input = file_get_contents('input');
$sum = 0;

for ($i = 1; $sum < $input / 10; $i++) {
    $sum = $i
        |> a::factors(...)
        |> array_sum(...);
}

echo $i - 1 . PHP_EOL;