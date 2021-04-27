<?php

namespace Invoker\TestToolkit;

use Throwable;

class PluginException extends \Exception
{
    protected $userMessage;
    protected $additionalInformation = [];

    public static function instance($userMessage, $additionalInformation = [])
    {
        $exception = new PluginException();

        $exception->userMessage = $userMessage;
        $exception->additionalInformation = $additionalInformation;

        return $exception;
    }

    public
    function toJson()
    {
        return json_encode(
            [
                "error" => true,
                "userMessage" => $this->userMessage,
                "additionalInformation" => $this->additionalInformation
            ]
        );
    }
}
