<?php

namespace Invoker\TestToolkit;

class PluginController
{

    public static function checkRequirements($projectPath, $executable): bool
    {
        // check if PHPUnit dependency is installed
        if (!file_exists($projectPath . $executable)) {
            throw PluginException::instance(
                "PHPUnit executable not found. Please check your devDependencies.",
                compact("projectPath", "executable")
            );
        }

        return true;
    }
}