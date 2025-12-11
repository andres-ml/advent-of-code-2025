<?php

namespace App\Days;

use function Aml\Fpl\index;
use function Aml\Fpl\map;
use function Aml\Fpl\partial;
use function Aml\Fpl\slice;
use function Aml\Fpl\sortBy;
use function Aml\Fpl\zip;
use function App\Utils\parse_lines;
use function App\Utils\toInteger;

class Day8
{

    public function parse(string $input)
    {
        return $input
            |> parse_lines(...)
            |> map(
                fn($line) => $line
                    |> partial(explode(...), ',')
                    |> map(toInteger(...))
            );
    }

    public function one(array $boxes)
    {
        $connections = $this->getConnections($boxes);

        // store both the boxes in each circuit and the circuit each box currently belongs to
        $circuits = [];
        $circuitPointersByBox = [];
        foreach ($boxes as $i => $_) {
            $circuits[$i] = [$i];
            $circuitPointersByBox[$i] = $i;
        }

        $connections = $connections
            |> sortBy(index('distance'))
            |> slice(0, 1000);

        foreach ($connections as ['from' => $a, 'to' => $b]) {
            $ca = $circuitPointersByBox[$a];
            $cb = $circuitPointersByBox[$b];
            if ($ca === $cb) {
                continue;
            }
            // merge circuits and update pointers
            foreach ($circuits[$cb] as $other) {
                $circuits[$ca] []= $other;
                $circuitPointersByBox[$other] = $ca;
            }
            $circuits[$cb] = [];
        }

        return $circuits
            |> map(fn($x) => count($x))
            |> sortBy(fn($count) => -$count)
            |> slice(0, 3)
            |> array_product(...)
            ;
    }

    public function two(array $boxes)
    {
        $connections = $this->getConnections($boxes);

        // store both the boxes in each circuit and the circuit each box currently belongs to
        $circuits = [];
        $circuitPointersByBox = [];
        foreach ($boxes as $i => $_) {
            $circuits[$i] = [$i];
            $circuitPointersByBox[$i] = $i;
        }

        $connections = $connections |> sortBy(index('distance'));

        foreach ($connections as ['from' => $a, 'to' => $b]) {
            $ca = $circuitPointersByBox[$a];
            $cb = $circuitPointersByBox[$b];
            if ($ca === $cb) {
                continue;
            }
            // merge circuits and update pointers
            foreach ($circuits[$cb] as $other) {
                $circuits[$ca] []= $other;
                $circuitPointersByBox[$other] = $ca;
            }
            $circuits[$cb] = [];
            if (count($circuits[$ca]) === count($boxes)) {
                return $boxes[$a][0] * $boxes[$b][0];
            }
        }

        return -1;
    }

    /**
     * Compute distances between all pairs of points
     *
     * @param array $boxes
     * @return void
     */
    protected function getConnections(array $boxes)
    {
        $connections = [];
        for ($i = 0; $i < count($boxes); ++$i) {
            for ($j = $i + 1; $j < count($boxes); ++$j) {
                $connections []= [
                    'from' => $i,
                    'to' => $j,
                    'distance' => $this->euclideanDistance($boxes[$i], $boxes[$j]),
                ];
            }
        }
        return $connections;
    }

    /**
     * {@link https://en.wikipedia.org/wiki/Euclidean_distance#Higher_dimensions}
     *
     * @param array $a
     * @param array $b
     * @return float
     */
    protected function euclideanDistance(array $a, array $b): float
    {
        return sqrt(
            zip($a, $b)
                |> map(fn($ns) => pow(abs($ns[0] - $ns[1]), 2))
                |> array_sum(...)
        );
    }

}