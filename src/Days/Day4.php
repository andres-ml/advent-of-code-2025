<?php

namespace App\Days;

use App\Utils\Parse;

use function Aml\Fpl\{map};

class Day4
{

    const PAPER = '@';
    const PAPER_WEIGHT = 4;

    public function one(string $input)
    {
        return $this->solve($input, 1);
    }

    public function two(string $input)
    {
        return $this->solve($input, null);
    }

    public function solve(string $input, ?int $maxSteps)
    {
        $total = 0;
        $steps = 0;
        $grid = Parse::grid($input);
        do {
            [$grid, $removed] = $this->step($grid);
            $total += $removed;
            $steps += 1;
        } while ($removed > 0 && $steps < ($maxSteps ?? INF));
        return $total;
    }

    public function step(array $grid)
    {
        // initialize weights matrix; non-paper will be null, others will be weighted
        $weights = map(map(fn(string $character) => $character === self::PAPER ? 0 : null), $grid);

        // go over map
        foreach ($grid as $i => $line) {
            foreach ($line as $j => $character) {
                // make each paper add 1 weight
                if ($character === self::PAPER) {
                    // to surroundings
                    foreach ([-1, 0, 1] as $ii) {
                        foreach ([-1, 0, 1] as $jj) {
                            // not self, ignore non-paper
                            if (($ii !== 0 || $jj !== 0) && isset($weights[$i + $ii][$j + $jj])) {
                                $weights[$i + $ii][$j + $jj] += 1;
                            }
                        }
                    }
                }
            }
        }

        // now rebuild map without paper, counting removed papers
        $removed = 0;
        foreach ($grid as $i => $line) {
            foreach ($line as $j => $character) {
                if ($weights[$i][$j] !== null && $weights[$i][$j] < self::PAPER_WEIGHT) {
                    $grid[$i][$j] = '.';
                    $removed += 1;
                }
            }
        }

        return [$grid, $removed];
    }


}