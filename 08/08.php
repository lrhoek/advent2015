<?php

require_once '../vendor/autoload.php';

use Anarchitecture\pipe as p;

function decode(string $code) : string {
    return substr($code, 1, -1)
        |> p\str_replace(["\\\\", "\\\""], "_")
        |> p\preg_replace("/\\\x[0-9a-f]{2}/", "_")
        |> strval(...);
}

function encode(string $code) : string {
    return $code
        |> p\str_replace(["\"", "\\"], "__")
        |> (fn ($string) => "_" . $string . "_");
}

function solve(array $input, callable $operation) : int {
    return $input
        |> p\array_map(fn ($string) : int => abs(strlen($operation($string)) - strlen($string)))
        |> array_sum(...);
}

/** @var array $input */
$input = file_get_contents('input')
    |> p\explode(PHP_EOL);

echo solve($input, decode(...)) . PHP_EOL;
echo solve($input, encode(...)) . PHP_EOL;