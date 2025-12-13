<?php

namespace App\Days;

use function Aml\Fpl\filter;
use function Aml\Fpl\head;
use function Aml\Fpl\identity;
use function Aml\Fpl\last;
use function Aml\Fpl\map;
use function Aml\Fpl\partial;
use function Aml\Fpl\sortBy;
use function App\Utils\list_combinations;
use function App\Utils\parse_lines;
use function App\Utils\toInteger;

class Day9
{

    public function parse(string $input)
    {
        return $input
            |> parse_lines(...)
            |> map(
                fn($line) => $line
                    |> partial(explode(...), ',')
                    |> map(toInteger(...))
                    |> (fn($coordinates) => (object) ['x' => $coordinates[0], 'y' => $coordinates[1]])
            );
    }

    public function one(array $positions)
    {
        return $positions
            |> list_combinations(...)
            |> map(fn($pair) => (1 + abs($pair[0]->x - $pair[1]->x)) * (1 + abs($pair[0]->y - $pair[1]->y)))
            |> sortBy(fn($area) => -$area)
            |> head();
    }

    public function two(array $positions)
    {
        $vertical = $horizontal = [];
        $previous = last($positions);
        foreach ($positions as $position) {
            if ($position->x === $previous->x) {
                $vertical[$position->x] []= [$previous->y, $position->y];
            }
            else {
                $horizontal[$position->y] []= [$previous->x, $position->x];
            }
            $previous = $position;
        }

        // sort for performance during later checks
        ksort($vertical);
        foreach ($vertical as &$ranges) {
            $ranges = $ranges
                |> map(sortBy(fn($y) => $y))
                |> sortBy(fn($range) => $range[0]);
        }

        // sort for performance during later checks
        ksort($horizontal);
        foreach ($horizontal as &$ranges) {
            $ranges = $ranges
                |> map(sortBy(fn($x) => $x))
                |> sortBy(fn($range) => $range[0]);
        }
        
        return $positions
            |> list_combinations(...)
            |> filter(
                function($pair) use($horizontal, $vertical) {
                    [$l, $r] = [$pair[0]->x, $pair[1]->x] |> sortBy(identity(...));
                    [$u, $d] = [$pair[0]->y, $pair[1]->y] |> sortBy(identity(...));
                    // fail if any of the boundaries of the inner (thus the ± 1's) rectangle intersect any of the lines
                    if (
                        $this->intersects($u + 1, [$l + 1, $r - 1], $vertical)
                        || $this->intersects($d - 1, [$l + 1, $r - 1], $vertical)
                        || $this->intersects($l + 1, [$u + 1, $d - 1], $horizontal)
                        || $this->intersects($r - 1, [$u + 1, $d - 1], $horizontal)
                    ) {
                        return false;
                    }
                    return true;
                }
            )
            |> map(fn($pair) => (1 + abs($pair[0]->x - $pair[1]->x)) * (1 + abs($pair[0]->y - $pair[1]->y)))
            |> sortBy(fn($area) => -$area)
            |> head()
            ;
    }

    /**
     * @param integer $height
     * @param array $pair
     * @param array $vertical
     * @return void
     */
    protected function intersects(int $height, array $pair, array $rangesByHeight)
    {
        [$left, $right] = $pair;
        foreach ($rangesByHeight as $x => $ranges) {
            // not within range yet
            if ($x < $left) {
                continue;
            }
            // we can stop since it's sorted
            if ($x > $right) {
                break;
            }
            foreach ($ranges as $range) {
                [$up, $down] = $range;
                // intersection found
                if ($up <= $height && $height <= $down) {
                    return false;
                }
                // we can stop since it's sorted
                if ($up > $height) {
                    break;
                }
            }
        }

        return true;
    }


}