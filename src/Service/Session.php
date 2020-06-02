<?php

namespace Hardkode\Service;

use Assert\Assert;

/**
 * Class Session
 * @package Hardkode\Service
 */
class Session
{

    /** @var string */
    private $sessionId;

    /**
     * Session constructor.
     */
    public function __construct()
    {
        if (empty(session_id()) && session_start()) {
            $this->sessionId = session_id();
        }
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function get(string $name)
    {
        return $_SESSION[$name] ?? null;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function set(string $name, $value)
    {
        $_SESSION[$name] = $value;
    }

    /**
     * @param string $name
     */
    public function delete(string $name)
    {
        unset($_SESSION[$name]);
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function getFlashMessage(string $name)
    {
        $value = $_SESSION['flashbag'][$name] ?? null;

        if (null !== $value) {
            unset($_SESSION['flashbag'][$name]);
        }

        return $value;
    }

    /**
     * @param string $name
     * @param $value
     */
    public function setFlashMessage(string $name, $value)
    {
        $_SESSION['flashbag'][$name] = $value;
    }

}