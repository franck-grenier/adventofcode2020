<?php

namespace App\Service;

class AdventOfCode2020Service
{
    public function getAOCInput(string $filename): array
    {
        return file(__DIR__ . "/../Input/" . $filename, FILE_IGNORE_NEW_LINES);
    }


    public function checkPasswordsCompliance(array $pass_to_check, int $compliance = 1):array
    {
        $good_pass = array_filter($pass_to_check, function ($pass) use ($compliance) {
            $pass_data = explode(" ", $pass);
            $pass_to_check = $pass_data[2];
            $expected_char = str_replace(":", "", $pass_data[1]);

            switch($compliance) {
                case 1:
                    $expected_char_range = explode("-", $pass_data[0]);
                    $expected_char_count = substr_count($pass_to_check, $expected_char);
                    return in_array($expected_char_count, range($expected_char_range[0], $expected_char_range[1]));
                case 2:
                    $expected_char_pos = explode("-", $pass_data[0]);
                    return $expected_char === $pass_to_check[$expected_char_pos[0] - 1] xor
                        $expected_char === $pass_to_check[$expected_char_pos[1] - 1];
            }
        });

        return $good_pass;
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