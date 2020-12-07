<?php

namespace App\Service;

class AOC2020PassportValidator
{
    const FOUR_DIGITS_REGEXP = "/^\d{4}$/";

    // byr (Birth Year) - four digits; at least 1920 and at most 2002.
    public function BYRValidator(string $data): bool
    {
        return preg_match($this::FOUR_DIGITS_REGEXP, $data) &&
            ((int)$data >= 1920 && (int)$data <= 2002);
    }

    // iyr (Issue Year) - four digits; at least 2010 and at most 2020.
    public function IYRValidator(string $data): bool
    {
        return preg_match($this::FOUR_DIGITS_REGEXP, $data) &&
            ((int)$data >= 2010 && (int)$data <= 2020);
    }

    // eyr (Expiration Year) - four digits; at least 2020 and at most 2030.
    public function EYRValidator(string $data): bool
    {
        return preg_match($this::FOUR_DIGITS_REGEXP, $data) &&
            ((int)$data >= 2020 && (int)$data <= 2030);
    }

    // hgt (Height) - a number followed by either cm or in:
    //     If cm, the number must be at least 150 and at most 193.
    //     If in, the number must be at least 59 and at most 76.
    public function HGTValidator(string $data): bool
    {
        if (!preg_match("/(\d*)(cm|in)/", $data)) {
            return false;
        }

        if (str_ends_with($data, "cm")) {
            return (str_replace("cm", "", $data) >= 150 && str_replace("cm", "", $data) <= 193);
        } elseif (str_ends_with($data, "in")) {
            return (str_replace("in", "", $data) >= 59 && str_replace("in", "", $data) <= 76);
        }
    }

    // hcl (Hair Color) - a # followed by exactly six characters 0-9 or a-f.
    public function HCLValidator(string $data): bool
    {
        return preg_match("/#[0-9|a-f]{6}/", $data);
    }

    // ecl (Eye Color) - exactly one of: amb blu brn gry grn hzl oth.
    public function ECLValidator(string $data): bool
    {
        return in_array($data, ["amb", "blu", "brn", "gry", "grn", "hzl", "oth"]);
    }

    // pid (Passport ID) - a nine-digit number, including leading zeroes.
    public function PIDValidator(string $data): bool
    {
        return preg_match("/^[0-9]{9}$/", $data);
    }

    // cid (Country ID) - ignored, missing or not.
    public function CIDValidator(string $data): bool
    {
        return true;
    }
}