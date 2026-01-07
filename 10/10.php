<?php

ini_set('memory_limit', "512M"); // brute force - look into RLE later

require_once '../vendor/autoload.php';

use Anarchitecture\pipe as p;

function look_and_say(string $string) : string {

    $previous = "";
    $count = null;
    $result = "";

    foreach ($string |> str_split(...) as $character) {

        if ($character !== $previous) {
            $result .= $count . $previous;
            $count = 1;
        }

        else {
            $count++;
        }

        $previous = $character;

    }

    $result .= $count . $previous;

    return $result;

}

$input = file_get_contents('input');

$gen40 = range(1, 40)
    |> p\array_reduce(look_and_say(...), $input)
    |> strval(...);

$gen50 = range(1, 10)
    |> p\array_reduce(look_and_say(...), $gen40)
    |> strval(...);

echo strlen($gen40) . PHP_EOL;
echo strlen($gen50) . PHP_EOL;