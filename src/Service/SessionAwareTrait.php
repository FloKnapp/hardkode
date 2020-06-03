<?php

namespace Hardkode\Service;

/**
 * Trait SessionAwareTrait
 * @package Hardkode\Service
 */
trait SessionAwareTrait
{

    /** @var Session */
    private $session;

    /**
     * @return Session
     */
    public function getSession(): Session
    {
        return $this->session;
    }

    /**
     * @param Session $session
     */
    public function setSession(Session $session): void
    {
        $this->session = $session;
    }

}