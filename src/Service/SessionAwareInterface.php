<?php


namespace Hardkode\Service;


interface SessionAwareInterface
{

    /**
     * @return Session
     */
    public function getSession(): Session;

    /**
     * @param Session $session
     * @return void
     */
    public function setSession(Session $session): void;

}