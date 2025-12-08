<?php

namespace App\Days;

use function Aml\Fpl\{map, partial};

class Day3
{

    public function one(string $input)
    {
        return $this->solve($input, 2);
    }

    public function two(string $input)
    {
        return $this->solve($input, 12);
    }

    public function solve(string $input, int $count)
    {
        return $input
            |> partial(explode(...), PHP_EOL)
            |> map(function(string $bank) use($count) {
                // finds position of highest number within string
                $getMax = function(string $string) {
                    $max = 0;
                    $i = 1;
                    while ($i < strlen($string) && $string[$max] !== '9') {
                        if ($string[$i] > $string[$max]) {
                            $max = $i;
                        }
                        $i += 1;
                    }
                    return $max;
                };

                // keep finding highest leftmost characters
                $stack = [];
                $index = 0;
                while ($count > 0) {
                    // reserve at least as many batteries are steps are left
                    $index += $getMax(
                        substr(
                            $bank,
                            $index,
                            $count > 1 ? 1 - $count : null
                        )
                    );
                    $stack []= $bank[$index];
                    $index += 1; // start on next
                    $count -= 1; // decrease remaining steps
                }

                return implode('', $stack);
            })
            |> map(fn($item) => (int) $item)
            |> array_sum(...)
            ;
    }


}