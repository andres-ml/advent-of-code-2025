<?php

namespace App\Days;

use function Aml\Fpl\{counter, filter, flatten, map, partial as _, toArray};

class Day2
{

    protected function solve(string $input, callable $count)
    {
        return $input
            |> _(explode(...), ',')
            |> map(fn($line) => explode('-', $line))
            |> map(fn($pair) => $count($pair[0], $pair[1]))
            |> array_filter(...)
            |> flatten(1)
            |> array_sum(...)
            ;
    }

    public function one(string $input)
    {
        return $this->solve(
            $input,
            function(string $from, string $to): array {
                $n = (int) substr($from, 0, floor(strlen($from) / 2));
                $getInvalid = fn(int $n) => (int) ((string) $n . (string) $n);
                $nextInvalid = $getInvalid($n);
                $to = (int) $to;
                $invalids = [];
                while ($nextInvalid <= $to) {
                    if ($nextInvalid >= $from) {
                        $invalids []= $nextInvalid;
                    }
                    $n += 1;
                    $nextInvalid = $getInvalid($n);
                }
                return $invalids;
            }
        );
    }

    public function two(string $input)
    {
        return $this->solve(
            $input,
            fn(string $from, string $to) => counter((int) $from, (int) $to + 1)
                |> filter(fn(int $n) => preg_match('/^(\d+)\1+$/', (string) $n))
                |> toArray()
        );
    }

}