<?php

require_once '../vendor/autoload.php';

use Anarchitecture\pipe as p;

$input = file_get_contents('input');

$floor = substr_count($input, "(") - substr_count($input, ")");

$position = $input
    |> str_split(...)
    |> p\array_reduce_until(
        fn ($current, $instruction) => $current + ($instruction === "(" ? 1 : -1),
        fn ($current) => $current === -1
    )
    |> p\array_nth(1)
    |> p\increment()
    |> intval(...);

echo $floor . PHP_EOL;
echo $position . PHP_EOL;
