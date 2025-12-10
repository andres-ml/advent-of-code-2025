<?php

namespace App\Days;

use function Aml\Fpl\eq;
use function Aml\Fpl\filter;
use function Aml\Fpl\flatten;
use function Aml\Fpl\map;
use function App\Utils\memoize;
use function App\Utils\parse_grid;

class Day7
{

    public function parse(string $input)
    {
        return parse_grid($input);
    }

    public function one(array $grid)
    {
        $splits = 0;
        $beams = [];
        $beams []= array_find_key($grid[0], eq('S'));
        foreach (array_slice($grid, 1) as $row) {
            $splitters = array_keys(filter(eq('^'), $row));
            $split = array_intersect($beams, $splitters);
            $beams = array_unique([
                ...array_diff($beams, $splitters),
                ...$split
                    |> map(fn($n) => [$n - 1, $n + 1])
                    |> flatten(1)
                    |> filter(fn($n) => $n >= 0 && $n < count($row))
            ]);
            $splits += count($split);
        }
        return $splits;
    }

    public function two(array $grid)
    {
        $timelines = memoize(function(int $depth, int $column) use($grid, &$timelines) {
            // end, 1 beam
            if ($depth >= count($grid)) {
                return 1;
            }
            // out of bounds, no beams
            if ($column < 0 || $column >= count($grid[$depth])) {
                return 0;
            }
            if ($grid[$depth][$column] === '.') {
                // beam keeps falling (splitters are never on consecutive rows so jump 2)
                return $timelines($depth + 2, $column);
            }
            else {
                // beam splits (splitters are never on consecutive rows so jump 2)
                $result = $timelines($depth + 2, $column - 1) + $timelines($depth + 2, $column + 1);
                return $result;
            }
        });
        return $timelines(0, array_find_key($grid[0], eq('S')));
    }


}