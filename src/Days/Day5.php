<?php

namespace App\Days;

use function Aml\Fpl\any;
use function Aml\Fpl\filter;
use function Aml\Fpl\map;
use function Aml\Fpl\partial;
use function Aml\Fpl\reduce;
use function Aml\Fpl\sort;
use function App\Utils\parse_lines;
use function App\Utils\toInteger;

class Day5
{
    public function one(array $input)
    {
        return $input['ids']
            |> filter(fn($id) => any(fn($range) => $range[0] <= $id && $id <= $range[1], $input['ranges']))
            |> count(...);
    }

    public function two(array $input)
    {
        return $input['ranges']
            // ensure only 1 reduce step is needed (no range will appear in the middle of 2 other ranges)
            |> sort(fn($a, $b) => $a[0] <=> $b[0])
            |> reduce(
                function(array $carry, array $next) {
                    $matchIndex = null;
                    foreach ($carry as $index => $candidate) {
                        // ranges overlap in any way -- merge them
                        if (
                            $candidate[0] <= $next[1] && $next[1] <= $candidate[1] ||
                            $next[0] <= $candidate[1] && $candidate[1] <= $next[1]
                        ) {
                            $matchIndex = $index;
                            break;
                        }
                        if ($candidate[0] > $next[1]) {
                            // sorted, so all remaining are too large
                            break;
                        }
                    }
                    // no matches, push and continue
                    if ($matchIndex === null) {
                        $carry []= $next;
                        return $carry;
                    }
                    // update range boundaries
                    $carry[$matchIndex][0] = min($carry[$matchIndex][0], $next[1]);
                    $carry[$matchIndex][1] = max($carry[$matchIndex][1], $next[1]);
                    return $carry;
                },
                []
            )
            |> map(fn(array $range) => $range[1] - $range[0] + 1)
            |> array_sum(...)
            ;
    }
    
    public function parse(string $input)
    {
        [$ranges, $ids] = explode(PHP_EOL . PHP_EOL, $input);
        return [
            'ranges' => $ranges
                |> parse_lines(...)
                |> map(
                    fn(string $range) => $range
                        |> partial(explode(...), '-')
                        |> map(toInteger(...))
                ),
            'ids' => $ids
                |> parse_lines(...)
                |> map(toInteger(...)),
        ];
    }

}