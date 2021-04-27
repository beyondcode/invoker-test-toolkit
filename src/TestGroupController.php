<?php

namespace Invoker\TestToolkit;


use Illuminate\Support\Collection;

class TestGroupController
{

    public function index(Collection $rawTests): Collection
    {

        $testGroups = new \Illuminate\Support\Collection();

        foreach ($rawTests as $test) {

            # split in class and method
            $testIdentifier = substr($test, 3, strlen($test) - 2);

            $fragments = explode("::", $testIdentifier);

            $testClass = $fragments[0];

            # split namespace from test - for display
            $classSplit = strrpos($testClass, "\\");

            $testGroup = new TestGroup();

            $namespace = substr($testClass, 0, $classSplit + 1);

            $class = substr($testClass, $classSplit + 1, strlen($testClass));

            $testGroup->setClass($class)
                ->setNamespace($namespace)
                ->setFullPath($testClass);

            if (!$testGroups->contains($testGroup))
                $testGroups->add($testGroup);
        }


        return $testGroups;
    }
}