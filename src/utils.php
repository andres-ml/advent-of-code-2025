<?php

namespace App\Utils;

use function Aml\Fpl\identity;
use function Aml\Fpl\map;
use function Aml\Fpl\partial;

/**
 * Parses a grid-like text into a matrix of cells.
 * Optionally pass a $parseCell callback to change how characters are parsed into the matrix.
 *
 * @param string $grid
 * @param callable|null $parseCell
 * @return array
 */
function parse_grid(string $grid, ?callable $parseCell = null): array
{
    return $grid
        |> partial(explode(...), PHP_EOL)
        |> map(
            fn(string $line) => $line
                |> str_split(...)
                |> ($parseCell ?? identity(...))
        );
}

/**
 * For easier composition
 * 
 * @param string $text
 * @return array
 */
function parse_lines(string $text): array
{
    return explode(PHP_EOL, $text);
}

/**
 * Q: Why not use (int) directly?
 * A: because it's not a function and it can't be composed
 * 
 * Q: Why not use intval()?
 * A: because it takes a 2nd param that makes composition difficult
 *
 * @param string $n
 * @return integer
 */
function toInteger(string $n): int
{
    return (int) $n;
}

/**
 * @param array $matrix
 * @return array
 */
function transpose(array $matrix): array
{
    return array_map(
        array_values(...),
        array_map(null, ...$matrix)
    );
}