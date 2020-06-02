<?php

namespace Hardkode\View\Helper;

/**
 * Class Block
 * @package Hardkode\View\Helper
 */
class Block extends AbstractViewHelper
{

    /**
     * @param string $name
     */
    public function __invoke(string $name)
    {
        if ($this->getView()->getParentView()->hasVariable($name)) {
            $this->getLogger()->debug('Opening block view helper failed. Expected block variable is already present.');
            return;
        }

        $this->getView()->getParentView()->setVariable($name, 'init');
        $this->getLogger()->debug('Opening output buffer for block "' . $name . '".');
        ob_start();
    }

}