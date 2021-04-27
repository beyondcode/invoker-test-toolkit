<?php

require_once __DIR__ . '/vendor/autoload.php';

use Silly\Application;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

$app = new Application();

$testController = new \Invoker\TestToolkit\SimpleTestController();
$groupsController = new \Invoker\TestToolkit\TestGroupController();


function bootstrap($path)
{
    require $path . '/vendor/autoload.php';
    $app = require_once $path . '/bootstrap/app.php';

    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    return $app;
}

$app->command('list-all-tests path binary executable', function ($path, $binary, $executable) use ($testController, $groupsController) {
    try {
        \Invoker\TestToolkit\PluginController::checkRequirements($path, $executable);
    } catch (\Invoker\TestToolkit\PluginException $pluginException) {
        echo $pluginException
            ->toJson();
        return;
    } catch (Exception $exception) {
        echo \Invoker\TestToolkit\PluginException::instance("An internal error occured.", [$exception->getMessage()])
            ->toJson();
        return;
    }

    $app = bootstrap($path);

    # read tests from phpunit
    $rawTests = $testController->readRawTests($binary, $path, $executable);

    # return sorted, split & nice
    echo Collection::make(
        [
            "tests" => $testController->index($rawTests)->toJson(),
            "groups" => $groupsController->index($rawTests)->toJson()
        ]
    )->toJson();
});

$app->command('run-single-test path binary executable testClass', function ($path, $binary, $executable, $testClass) use ($testController) {
    try {
        \Invoker\TestToolkit\PluginController::checkRequirements($path, $executable);
    } catch (\Invoker\TestToolkit\PluginException $pluginException) {
        echo $pluginException
            ->toJson();
        return;
    } catch (Exception $exception) {
        echo \Invoker\TestToolkit\PluginException::instance("An internal error occured.", [$exception->getMessage()])
            ->toJson();
        return;
    }

    $app = bootstrap($path);

    echo json_encode($testController->runSingleTest($binary, $path, $executable, $testClass));
});


$app->run();