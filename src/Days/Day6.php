<?php

namespace App\Days;

use function Aml\Fpl\filter;
use function Aml\Fpl\identity;
use function Aml\Fpl\last;
use function Aml\Fpl\map;
use function Aml\Fpl\partial;
use function Aml\Fpl\reduce;
use function App\Utils\parse_lines;
use function App\Utils\transpose;

class Day6
{

    protected function operate(string $operator, array $numbers): int
    {
        return reduce(
            fn(int $carry, int $next) => match ($operator) {
                '+' => $carry + $next,
                '*' => $carry * $next,
            },
            match ($operator) {
                '+' => 0,
                '*' => 1
            },
            $numbers
        );
    }

    public function one(string $input)
    {
        return $input
            |> parse_lines(...)
            |> map(
                fn(string $line) => $line
                    |> trim(...)
                    |> partial(explode(...), ' ')
                    |> map(fn($x) => trim($x))
                    |> filter(identity(...))
                    |> map(fn($item) => is_numeric($item) ? intval($item) : $item)
            )
            |> transpose(...)
            |> map(
                fn(array $column) => $this->operate(
                    last($column),
                    array_slice($column, 0, -1)
                )
            )
            |> array_sum(...);
    }

    public function two(string $input)
    {
        $lines = parse_lines($input);
        $numberLines = array_slice($lines, 0, -1);
        $operatorsLine = last($lines);

        // use operators line to get widths
        $streams = [];
        while (strlen($operatorsLine)) {
            $matches = [];
            preg_match('/^\S\s+/', $operatorsLine, $matches);
            $width = strlen($matches[0]);
            // last space is a separator between columns, except on last column
            if ($matches[0] !== $operatorsLine) {
                $width -= 1;
            }
            $streams []= [
                'width' => $width,
                'operator' => $operatorsLine[0],
            ];
            $operatorsLine = substr($operatorsLine, strlen($matches[0]));
        }

        $results = [];
        $globalColumnIndex = 0;
        foreach ($streams as $stream) {
            // read column by column
            $columns = array_fill(0, $stream['width'], []);
            foreach ($numberLines as $numberLine) {
                for ($columnIndex = 0; $columnIndex < $stream['width']; ++$columnIndex) {
                    $columns[$columnIndex] []= $numberLine[$columnIndex + $globalColumnIndex];
                }
            }
            // remove empty spaces, join, and parse int
            $numbers = array_map(
                fn($numbers) => $numbers
                    |> filter(fn($char) => $char !== ' ')
                    |> partial(implode(...), '')
                    |> intval(...),
                $columns
            );
            $results []= $this->operate($stream['operator'], $numbers);
            // stream width + 1 for column separation
            $globalColumnIndex += $stream['width'] + 1;
        }

        return array_sum($results);
    }


}