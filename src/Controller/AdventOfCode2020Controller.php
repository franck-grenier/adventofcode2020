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
    private $twig;

    public function __construct(AdventOfCode2020Service $AOC2020Service, Environment $twig)
    {
        $this->AOC2020Service = $AOC2020Service;
        $this->twig = $twig;
    }

    /**
     * @Route("/adventofcode2020/", name="aoc2020_index")
     */
    public function index(RouterInterface $router): Response
    {
        $aoc2020_routes = array_filter($router->getRouteCollection()->all(), function ($route_name) {
            return str_starts_with($route_name, 'aoc2020');
        }, ARRAY_FILTER_USE_KEY);

        return new Response($this->twig->render("aoc2020/index.html.twig", array("routes" => array_keys($aoc2020_routes))));
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

        return new Response($this->twig->render("base.html.twig", array("answer" => $answer)));
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

        return new Response($this->twig->render("base.html.twig", array("answer" => $answer)));
    }

    /**
     * @Route("/adventofcode2020/day2/part1", name="aoc2020_day2_part1")
     */
    public function day2Part1(): Response
    {
        $passwords = $this->AOC2020Service->getAOCInput("day2.txt");
        $good_passwords = $this->AOC2020Service->checkPasswordsCompliance($passwords, 1);

        return new Response($this->twig->render("base.html.twig", array("answer" => count($good_passwords))));
    }

    /**
     * @Route("/adventofcode2020/day2/part2", name="aoc2020_day2_part2")
     */
    public function day2Part2(): Response
    {
        $passwords = $this->AOC2020Service->getAOCInput("day2.txt");
        $good_passwords = $this->AOC2020Service->checkPasswordsCompliance($passwords, 2);

        return new Response($this->twig->render("base.html.twig", array("answer" => count($good_passwords))));
    }

    /**
     * @Route("/adventofcode2020/day3/part1", name="aoc2020_day3_part1")
     */
    public function day3Part1(): Response
    {
        $slope = $this->AOC2020Service->getAOCInput("day3.txt");
        $number_of_trees = $this->AOC2020Service->SkiOnSlopeAndHitTrees($slope, 3, 1);

        return new Response($this->twig->render("base.html.twig", array("answer" => $number_of_trees)));
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

        return new Response($this->twig->render("base.html.twig", array("answer" => $score)));
    }

    /**
     * @Route("/adventofcode2020/day4/part1", name="aoc2020_day4_part1")
     */
    public function day4Part1(): Response
    {
        $passports = $this->AOC2020Service->parsePassportsInput($this->AOC2020Service->getAOCInput("day4.txt", false));
        $good_passports = $this->AOC2020Service->checkPassportsCompleteness($passports);

        return new Response($this->twig->render("base.html.twig", array("answer" => count($good_passports))));
    }

    /**
     * @Route("/adventofcode2020/day4/part2", name="aoc2020_day4_part2")
     */
    public function day4Part2(): Response
    {
        $passports = $this->AOC2020Service->parsePassportsInput($this->AOC2020Service->getAOCInput("day4.txt", false));
        $valid_passports = $this->AOC2020Service->checkPassportsValidity($passports);

        return new Response($this->twig->render("base.html.twig", array("answer" => count($valid_passports))));
    }

}
