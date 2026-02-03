<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use Anarchitecture\pipe as p;

class State {

    public int $mana_consumed = 0;
    public int $shield = 0;
    public int $poison = 0;
    public int $recharge = 0;
    public int $turn = 0;

    public function __construct(public int $hp, public int $mana, public int $boss_hp, public int $boss_damage, public bool $hard_mode = false)
    {

    }
}

class LowestMana extends SplHeap {
    protected function compare($value1, $value2) : int
    {
        return $value2->mana_consumed <=> $value1->mana_consumed;
    }
}

function turn(SplHeap $states) {

    /** @var State $state */
    $state = $states->extract();
    $armor = 0;

    if ($state->hard_mode === true && $state->turn % 2 === 0) {
        $state->hp--;
    }

    if ($state->hp <= 0) {
        return $states;
    }

    if ($state->shield > 0) {
        $armor = 7;
        $state->shield--;
    }

    if ($state->poison > 0) {
        $state->boss_hp -= 3;
        $state->poison--;
    }

    if ($state->recharge > 0) {
        $state->mana += 101;
        $state->recharge--;
    }

    if ($state->boss_hp < 1) {
        $states->insert($state);
        return $states;
    }

    if ($state->turn % 2 === 1) {
        $state->hp -= max(1, $state->boss_damage - $armor);
        $state->turn++;
        $states->insert($state);
        return $states;
    }

    $state->turn++;

    if ($state->mana < 53) {
        return $states;
    }

    if ($state->shield === 0 && $state->mana >= 113) {
        $shield = clone($state, ["shield" => 6]);
        $shield->mana -= 113;
        $shield->mana_consumed += 113;
        $states->insert($shield);
    }

    if ($state->poison === 0 && $state->mana >= 173) {
        $poison = clone($state, ["poison" => 6]);
        $poison->mana -= 173;
        $poison->mana_consumed += 173;
        $states->insert($poison);
    }

    if ($state->recharge === 0 && $state->mana >= 229) {
        $recharge = clone($state, ["recharge" => 5]);
        $recharge->mana -= 229;
        $recharge->mana_consumed += 229;
        $states->insert($recharge);
    }

    if ($state->mana >= 53) {
        $magic_missile = clone($state);
        $magic_missile->mana -= 53;
        $magic_missile->mana_consumed += 53;
        $magic_missile->boss_hp -= 4;
        $states->insert($magic_missile);
    }

    if ($state->mana >= 73) {
        $drain = clone($state);
        $drain->mana -= 73;
        $drain->mana_consumed += 73;
        $drain->boss_hp -= 2;
        $drain->hp += 2;
        $states->insert($drain);
    }

    return $states;
}

function lowest_mana_win(State $state): State
{
    $lowest_mana = new LowestMana();
    $lowest_mana->insert($state);

    return $lowest_mana
        |> p\iterate(turn(...))
        |> p\iterable_map(fn(LowestMana $states) => $states->top())
        |> p\iterable_filter(fn(State $state) => $state->hp > 0 && $state->boss_hp <= 0)
        |> p\iterable_first(...);
}

[$boss_hp, $boss_damage] = file_get_contents('input')
    |> trim(...)
    |> p\preg_match_all("/\d+/")
    |> array_first(...)
    |> p\array_map(intval(...));

echo lowest_mana_win(new State(50, 500, $boss_hp, $boss_damage))->mana_consumed . PHP_EOL;
echo lowest_mana_win(new State(50, 500, $boss_hp, $boss_damage, true))->mana_consumed . PHP_EOL;