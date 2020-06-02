<?php

namespace Hardkode\View\Helper;

/**
 * Class Path
 * @package Hardkode\View\Helper
 */
class Path extends AbstractViewHelper
{

    /**
     * @param string $routeName
     * @return string|null
     */
    public function __invoke(string $routeName)
    {
        $routes = $this->getConfig()->get('routes');
        $path   = $routes[$routeName]['path'] ?? null;

        if (null === $path) {
            $this->getLogger()->error('Route "' . $routeName . '" not found.', ['class' => get_class($this)]);
            return null;
        }

        return $path;
    }

}