<?php

namespace Hardkode\Service;

/**
 * Class UserAwareInterface
 * @package Hardkode\Service
 */
interface UserAwareInterface
{

    /**
     * @return User
     */
    public function getUser(): User;

    /**
     * @param User $user
     * @return void
     */
    public function setUser(User $user): void;

}