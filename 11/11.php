<?php

require_once '../vendor/autoload.php';

use Anarchitecture\pipe as p;

function alpha_decode(string $string) : int
{
    $digits = range("a", "z");
    $n = 0;

    foreach (str_split($string) as $value) {
        $n = $n * 26 + array_search($value, $digits);
    }

    return  $n;
}

function alpha_encode(int $number) : string {

    $digits = range("a", "z");
    $string = "";

    while ($number > 0) {
        $string = $digits[$number % 26] . $string;
        $number = intdiv($number, 26);
    }

    return $string;
}

function rule_straight(string $string) : bool {
    return $string
        |> p\iterable_string()
        |> p\iterable_map(alpha_decode(...))
        |> p\iterable_window(3)
        |> p\iterable_any(p\apply(fn ($a, $b, $c) => $a + 1 === $b && $a + 2 === $c))
        |> boolval(...);
}

function rule_letters(string $string) : bool {
    return !preg_match('/[iol]/', $string);
}

function rule_pairs(string $string) : bool {
    return $string
        |> p\iterable_string()
        |> p\iterable_window(2)
        |> p\iterable_filter(p\apply(fn ($a, $b) => $a === $b))
        |> iterator_to_array(...)
        |> p\array_flatten(...)
        |> p\array_unique()
        |> count(...) >= 2;
}

function password_generator(string $input) : Generator {
    yield from alpha_decode($input) + 1
        |> p\iterable_ticker(...)
        |> p\iterable_map(alpha_encode(...))
        |> p\iterable_filter(rule_pairs(...))
        |> p\iterable_filter(rule_straight(...))
        |> p\iterable_filter(rule_letters(...));
}

$generator = file_get_contents('input')
    |> password_generator(...)
    |> p\iterable_take(2);

foreach ($generator as $password) {
    echo $password . PHP_EOL;
}