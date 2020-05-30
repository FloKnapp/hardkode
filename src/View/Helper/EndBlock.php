<?php

namespace Hardkode\View\Helper;

/**
 * Class Block
 * @package Hardkode\View\Helper
 */
class EndBlock extends AbstractViewHelper
{

    /**
     * @param string $name
     */
    public function __invoke(string $name)
    {
        if (!$this->getView()->getParentView()->hasVariable($name) || $this->getView()->getParentView()->getVariable($name) !== 'init') {
            $this->getLogger()->debug('Sealing block view helper failed. Expected block variable is missing.');
            return;
        }

        $content = ob_get_contents();
        ob_end_clean();
        $this->getView()->getParentView()->setVariable($name, $content);
    }

}