<?php

namespace App\Service;

use App\Service\AOC2020PassportValidator;

class AdventOfCode2020Service
{
    public function getAOCInput(string $filename, bool $asArray = true)
    {
        $path = __DIR__ . "/../Input/" . $filename;

        if ($asArray) {
            return file($path, FILE_IGNORE_NEW_LINES);
        } else {
            return file_get_contents($path);
        }
    }

    public function checkPasswordsCompliance(array $pass_to_check, int $compliance = 1): array
    {
        $good_pass = array_filter($pass_to_check, function ($pass) use ($compliance) {
            $pass_data = explode(" ", $pass);
            $pass_to_check = $pass_data[2];
            $expected_char = str_replace(":", "", $pass_data[1]);

            switch ($compliance) {
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
            if ($row >= count($slope)) {
                break;
            }

            if ($slope[$row][$col] === '#') {
                $number_of_trees++;
            }
        }

        // Ouch !
        return $number_of_trees;
    }

    // Sure we can do better here...
    public function parsePassportsInput(string $input): array
    {
        $passports = $this->getAOCInput($input, false);

        // Step 1 - Split string in blank lines and get an array of passports
        $step1 = explode(PHP_EOL . PHP_EOL, $passports);

        // Step 2 - Cleanup passports data and get a non-associative array of each passport data
        $step2 = array_map(function ($passport) {
            return explode(" ", str_replace(PHP_EOL, " ", $passport));
        }, $step1);

        // Step 3 - Rebuild passports data as an associative array
        $parsed_passports = [];
        foreach ($step2 as $pass_data) {
            foreach ($pass_data as $data) {
                $data_array[explode(":", $data)[0]] = explode(":", $data)[1];
            }
            ksort($data_array);
            $parsed_passports[] = $data_array;
            $data_array = [];
        }

        return $parsed_passports;
    }

    public function checkPassportsCompleteness(array $passports): array
    {
        $passport_data = [
            "byr", // (Birth Year)
            "iyr", // (Issue Year)
            "eyr", // (Expiration Year)
            "hgt", // (Height)
            "hcl", // (Hair Color)
            "ecl", // (Eye Color)
            "pid", // (Passport ID),
            "cid"  // (Country ID)
        ];
        sort($passport_data);
        $optional_data = ["cid"];

        return array_filter($passports, function ($passport) use ($passport_data, $optional_data) {
            $diff = array_values(array_diff($passport_data, array_keys($passport)));
            return (empty($diff) || $diff === $optional_data);
        });
    }

    public function checkPassportsValidity(array $passports): array
    {
        $passports = $this->checkPassportsCompleteness($passports);
        $validator = new AOC2020PassportValidator();

        return array_filter($passports, function ($passport) use ($validator) {
            $check = true;
            foreach ($passport as $key => $pass_data) {
                if (!$validator->{strtoupper($key) . "Validator"}($pass_data)) {
                    $check = false;
                    break;
                }
            }
            return $check;
        });
    }


}