<?php

namespace Hardkode\View\Helper;

/**
 * Class RenderBlock
 * @package Hardkode\View\Helper
 */
class RenderBlock extends AbstractViewHelper
{

    /**
     * @param string $name
     * @param string $defaultValue
     * @return array|string
     */
    public function __invoke(string $name, $defaultValue = '')
    {
        $value = $this->getView()->getVariable($name);

        if (empty($value)) {
            $value = $defaultValue;
        }

        return $value;
    }

}