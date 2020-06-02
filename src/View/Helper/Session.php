<?php

namespace Hardkode\View\Helper;

/**
 * Class Session
 * @package Hardkode\View\Helper
 */
class Session extends AbstractViewHelper
{

    /**
     * @return \Hardkode\Service\Session
     */
    public function __invoke()
    {
        return $this->getSession();
    }

}