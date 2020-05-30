<?php

namespace Hardkode\Service;

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
        return $_SESSION[$name];
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

}