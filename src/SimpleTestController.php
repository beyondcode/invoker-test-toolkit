<?php

namespace Invoker\TestToolkit;


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class SimpleTestController
{

    public function readRawTests(string $binary, string $path, string $executable): Collection
    {
        $output = array();

        $command = $binary . " " . $path . $executable . " --list-tests";
        $result = exec($command, $output);

        $tests = collect($output);

        $tests = $tests->filter(function ($row) {
            return (substr($row, 0, 8) == " - Tests");
        });

        return $tests;
    }

    public function index(Collection $rawTests)
    {

        $sortedTests = new \Illuminate\Support\Collection();

        # sort tests by test class
        foreach ($rawTests as $test) {

            $singleTest = new SingleTestModel();

            # split in class and method
            $testIdentifier = substr($test, 3, strlen($test) - 2);

            $fragments = explode("::", $testIdentifier);

            $testClass = $fragments[0];
            $testMethod = $fragments[1];

            $singleTest
                ->setClass($testClass)
                ->setMethod($testMethod)
                ->setFullPath($testIdentifier);

            $sortedTests->add($singleTest);

        }

        return $sortedTests;
    }

    public function runSingleTest(string $binary, string $path, string $executable, string $testClass): array
    {
        $result = exec($binary . " " . $path . $executable . " --filter '" . addslashes($testClass) . "'", $output);

        $output = implode(" ", $output);

        // toDo Create TestResult Class for this information + consts
        // detect test status
        if (str_contains($output, "FAILURES")) {
            $status = "FAIL";
        } else if (str_contains($output, "incomplete") || str_contains($output, "risky") || str_contains($output, "skipped")) {
            $status = "SKIP";
        } elseif (str_contains($output, "OK")) {
            $status = "OK";
        } else $status = "FAIL";

        return [
            "status" => $status,
            "output" => $output,
        ];

    }


}