<?php

namespace App\Service;

class AdventOfCode2020Service
{
    public function getAOCInput(string $filename): array
    {
        return file(__DIR__ . "/../Input/" . $filename, FILE_IGNORE_NEW_LINES);
    }

    public function SkiOnSlopeAndHitTrees(array $slope = [], int $right = 0, int $down = 0): int
    {
        $row = $col = $number_of_trees = 0;

        while ($row < count($slope)) {
            $col = ($col + $right) % strlen($slope[0]);
            $row += $down;
            if ($row == count($slope)) {
                break;
            }

            if ($slope[$row][$col] === '#') {
                $number_of_trees++;
            }
        }

        // Ouch !
        return $number_of_trees;
    }
}