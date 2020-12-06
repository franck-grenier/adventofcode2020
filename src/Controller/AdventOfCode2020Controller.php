<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use App\Service\AdventOfCode2020Service;

class AdventOfCode2020Controller extends AbstractController
{
    private $AOC2020Service;

    public function __construct(AdventOfCode2020Service $AOC2020Service)
    {
        $this->AOC2020Service = $AOC2020Service;
    }

    /**
     * @Route("/adventofcode2020/", name="aoc2020_index")
     */
    public function index(RouterInterface $router, Environment $twig): Response
    {
        $aoc2020_routes = array_filter($router->getRouteCollection()->all(), function ($route_name) {
            return str_starts_with($route_name, 'aoc2020');
        }, ARRAY_FILTER_USE_KEY);

        return new Response($twig->render("aoc2020/index.html.twig", array("routes" => array_keys($aoc2020_routes))));
    }

    /**
     * @Route("/adventofcode2020/day1/part1", name="aoc2020_day1_part1")
     */
    public function day1Part1(): Response
    {
        $expense_report = $this->AOC2020Service->getAOCInput('day1.txt');
        $expected_result = 2020;

        foreach ($expense_report as $expense1) {
            foreach ($expense_report as $expense2) {
                if ($expense1 + $expense2 == $expected_result) {
                    $answer = sprintf('Found it !<br> one is %d and two is %d <br>', (int)$expense1, (int)$expense2);
                    $answer .= sprintf('Multiplying them gives %d', $expense1 * $expense2);
                    break 2;
                } else {
                    continue;
                }
            }
        }

        return new Response($answer);
    }

    /**
     * @Route("/adventofcode2020/day1/part2", name="aoc2020_day1_part2")
     */
    public function day1Part2(): Response
    {
        $expense_report = $this->AOC2020Service->getAOCInput('day1.txt');
        $expected_result = 2020;

        foreach ($expense_report as $expense1) {
            foreach ($expense_report as $expense2) {
                foreach ($expense_report as $expense3) {
                    if ($expense1 + $expense2 + $expense3 == $expected_result) {
                        $answer = sprintf('Found it !<br> one is %d , two is %d, three is %d <br>', (int)$expense1,
                            (int)$expense2, (int)$expense3);
                        $answer .= sprintf('Multiplying them gives %d', $expense1 * $expense2 * $expense3);
                        break 3;
                    } else {
                        continue;
                    }
                }
            }
        }

        return new Response($answer);
    }

    /**
     * @Route("/adventofcode2020/day2/part1", name="aoc2020_day2_part1")
     */
    public function day2Part1(): Response
    {
        $passwords = $this->AOC2020Service->getAOCInput("day2.txt");

        $good_passwords = array_filter($passwords, function ($password) {
            $password_data = explode(" ", $password);

            $expected_char = str_replace(":", "", $password_data[1]);
            $expected_char_range = explode("-", $password_data[0]);
            $password_to_check = $password_data[2];

            $expected_char_count = substr_count($password_to_check, $expected_char);
            return in_array($expected_char_count, range($expected_char_range[0], $expected_char_range[1]));
        });

        return new Response(count($good_passwords));
    }

    /**
     * @Route("/adventofcode2020/day2/part2", name="aoc2020_day2_part2")
     */
    public function day2Part2(): Response
    {
        $passwords = $this->AOC2020Service->getAOCInput("day2.txt");

        $good_passwords = array_filter($passwords, function ($password) {
            $password_data = explode(" ", $password);

            $expected_char = str_replace(":", "", $password_data[1]);
            $expected_char_pos = explode("-", $password_data[0]);
            $password_array_to_check = str_split($password_data[2]);

            return $expected_char === $password_array_to_check[$expected_char_pos[0] - 1] xor
                $expected_char === $password_array_to_check[$expected_char_pos[1] - 1];
        });

        return new Response(count($good_passwords));
    }

    /**
     * @Route("/adventofcode2020/day3/part1", name="aoc2020_day3_part1")
     */
    public function day3Part1(): Response
    {
        $slope = $this->AOC2020Service->getAOCInput("day3.txt");
        $number_of_trees = $this->AOC2020Service->SkiOnSlopeAndHitTrees($slope, 3, 1);

        return new Response($number_of_trees);
    }

    /**
     * @Route("/adventofcode2020/day3/part2", name="aoc2020_day3_part2")
     */
    public function day3Part2(): Response
    {
        $slope = $this->AOC2020Service->getAOCInput("day3.txt");
        $score = 1;

        $slope_styles = [ [1,1] , [3,1] , [5,1] , [7,1] , [1,2] ];

        foreach ($slope_styles as $style) {
            $score *= $this->AOC2020Service->SkiOnSlopeAndHitTrees($slope, $style[0], $style[1]);
        }

        return new Response($score);
    }

}
