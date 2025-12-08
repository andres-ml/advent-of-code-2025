<?php

namespace App\Utils;

use function Aml\Fpl\identity;
use function Aml\Fpl\map;
use function Aml\Fpl\partial;

class Parse
{

    public static function grid(string $grid, ?callable $parseCell = null): array
    {
        return $grid
            |> partial(explode(...), PHP_EOL)
            |> map(
                fn(string $line) => $line
                    |> str_split(...)
                    |> ($parseCell ?? identity(...))
            );
    }

}