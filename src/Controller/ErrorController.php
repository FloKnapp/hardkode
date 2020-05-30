<?php

namespace Hardkode\Controller;

/**
 * Class ErrorController
 * @package Hardkode\Controller
 */
class ErrorController extends PageController
{

    public function onError($code, $message, $file, $line, $context)
    {
        echo "Error:" . PHP_EOL;
    }

    public function onException(\Throwable $exception)
    {
        echo "Exception:" . PHP_EOL;

        echo $exception->getMessage();
    }

}