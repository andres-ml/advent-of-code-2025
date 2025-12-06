<?php

namespace App\Days;

use function Aml\Fpl\{index, partial as _, reduce};

class Day1
{

    protected function solve(string $input, callable $turn)
    {
        return $input
            |> _(explode(...), PHP_EOL)
            |> reduce($turn, [
                'hits' => 0,
                'dial' => 50
            ])
            |> index('hits');
    }

    public function one(string $input)
    {
        return $this->solve(
            $input,
            function(array $carry, string $turn) {
                $amount = (int) substr($turn, 1);
                $carry['dial'] = match($turn[0]) {
                    'L' => $carry['dial'] - $amount,
                    'R' => $carry['dial'] + $amount,
                } % 100;
                return [
                    'dial' => $carry['dial'],
                    'hits' => $carry['hits'] + (int) ($carry['dial'] === 0)
                ];
            }
        );
    }

    public function two(string $input)
    {
        return $this->solve(
            $input,
            function(array $carry, string $turn) {
                $amount = (int) substr($turn, 1);
                $dial = match($turn[0]) {
                    'L' => $carry['dial'] - $amount % 100,
                    'R' => $carry['dial'] + $amount % 100,
                };
                return [
                    'dial' => ($dial + 100) % 100,
                    'hits' => $carry['hits']
                        + floor(abs($amount) / 100) // full turns
                        + (int) ($dial >= 100) // leftover cross right
                        + (int) ($dial <= 0 && $carry['dial'] > 0), // leftover cross left
                ];
            }
        );
    }

}