<?php

namespace Hardkode\Controller;

use Hardkode\Exception\NotFoundException;
use Hardkode\Exception\PermissionException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ErrorController
 * @package Hardkode\Controller
 */
class ErrorController extends PageController
{

    /**
     * @param int        $code
     * @param string     $message
     * @param string     $file
     * @param int        $line
     * @param array|null $context
     */
    public function onError($code, $message, $file, $line, $context)
    {
        echo <<<EXCEPTION
<h2>Error</h2>
<h3>{$message}</h3>
<h5>{$file}:{$line}</h5>
EXCEPTION;
    }

    /**
     * @param \Throwable $t
     */
    public function onException(\Throwable $t)
    {

        if ($t instanceof PermissionException) {
            echo (string)$this->render('/error/403.phtml')->getBody();
            $this->getLogger()->error($t->getMessage());
            exit(0);
        }

        if ($t instanceof NotFoundException) {
            echo (string)$this->render('/error/404.phtml')->getBody();
            $this->getLogger()->error($t->getMessage());
            exit(0);
        }

        echo <<<EXCEPTION
<h2>Exception</h2>
<h3>{$t->getMessage()}</h3>
<h5>{$t->getFile()}:{$t->getLine()}</h5>
EXCEPTION;

    }

}