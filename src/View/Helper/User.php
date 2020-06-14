<?php

namespace Hardkode\View\Helper;

/**
 * Class User
 * @package Hardkode\View\Helper
 */
class User extends AbstractViewHelper
{

    /**
     * @return self
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

    /**
     * @param string $name
     * @return bool
     */
    public function hasRole(string $name)
    {
        return in_array($name, $this->getUserRoles(), true);
    }

    /**
     * @return array
     */
    private function getUserRoles(): array
    {
        return array_map(function(\Hardkode\Model\Role $role) {
            return $role->name;
        }, $this->getUser()->getCurrentUser()->roles ?? []);
    }

}