<?php

namespace Hardkode\View\Helper;

use Hardkode\Exception\FileNotFoundException;

/**
 * Class Layout
 * @package Hardkode\View\Helper
 */
class Layout extends AbstractViewHelper
{

    /**
     * @param string $template
     *
     * @throws FileNotFoundException
     */
    public function __invoke(string $template)
    {
        $parentRenderer = clone $this->getView();
        $parentRenderer->reset();
        $parentRenderer->setTemplate($template);

        $this->getView()->setParentView($parentRenderer);
    }

}