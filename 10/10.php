<?php

require_once '../vendor/autoload.php';

use Anarchitecture\pipe as p;

function look_and_say(string $string) : string {

    if (strlen($string) === 1) {
        return "1" . $string;
    }

    $count = 1;
    $result = "";
    $next = "";

    $string = $string
        |> p\iterable_string()
        |> p\iterable_window(2);

    foreach ($string as [$character, $next]) {

         if ($character !== $next) {
            $result .= $count . $character;
            $count = 1;
        }

        else {
            $count++;
        }
    }

    $result .= $count . $next;

    return $result;
}

$sequence = file_get_contents('input')
    |> p\iterate(look_and_say(...))
    |> p\iterable_filter(fn ($_, $generation) => $generation === 40 || $generation === 50)
    |> p\iterable_take(2)
    |> p\iterable_map(strlen(...));

foreach ($sequence as $length) {
    echo $length . PHP_EOL;
}