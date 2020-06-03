<?php

namespace Hardkode\Service;

/**
 * Class UserAwareTrait
 * @package Hardkode\Service
 */
trait UserAwareTrait
{

    /** @var User */
    private $user;

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

}