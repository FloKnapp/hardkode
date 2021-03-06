<?php

namespace Hardkode\View\Helper;

/**
 * Class Link
 * @package Hardkode\View\Helper
 */
class Link extends AbstractViewHelper
{
    /**
     * @param string $routeName
     * @param array  $attributes
     * @param string $linkTextAdditional
     *
     * @return string
     */
    public function __invoke(string $routeName, array $attributes = [], $linkTextAdditional = '')
    {
        $routes   = $this->getConfig()->get('routes');
        $path     = $routes[$routeName]['path'] ?? null;
        $linkText = $routes[$routeName]['link'] ?? null;

        if (null === $routes[$routeName]) {
            $this->getLogger()->error('Route "' . $routeName . '" not found.', ['class' => get_class($this)]);
            return '';
        }

        if ($_SERVER['REQUEST_URI'] === $routes[$routeName]['path']) {
            $attributes['class'] = $attributes['class'] ?? '' . ' active';
        }

        $attributeString  = '';
        $attributePattern = ' %s="%s" ';

        foreach ($attributes as $attr => $value) {
            $attributeString .= sprintf($attributePattern, $attr, $value);
        }

        $attributeString = substr($attributeString, 1, strlen($attributeString) - 1);

        return '<a href="' . $path . '" ' . $attributeString . '>' . sprintf($linkText, $linkTextAdditional) . '</a>';

    }
}