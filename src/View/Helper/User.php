<?php

namespace Hardkode\View\Helper;

/**
 * Class User
 * @package Hardkode\View\Helper
 */
class User extends AbstractViewHelper
{

    /**
     *
     */
    public function __invoke()
    {
        return $this;
    }

    /**
     * @return mixed|null
     */
    public function getUsername()
    {
        return $this->getUser()->getCurrentUser()->name ?? null;
    }

}