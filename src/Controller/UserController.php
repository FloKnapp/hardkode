<?php

namespace Hardkode\Controller;

use Psr\Http\Message\ResponseInterface;

/**
 * Class UserController
 * @package Hardkode\Controller
 */
class UserController extends PageController
{

    /**
     * @return ResponseInterface
     */
    public function login()
    {
        return $this->render('/user/login.phtml');
    }

    /**
     * @return ResponseInterface
     */
    public function logout()
    {

    }

    public function register()
    {

    }

}