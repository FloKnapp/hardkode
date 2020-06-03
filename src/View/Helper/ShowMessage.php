<?php

namespace Hardkode\View\Helper;

/**
 * Class ShowMessage
 * @package Hardkode\View\Helper
 */
class ShowMessage extends AbstractViewHelper
{
    /**
     * @param string $id
     *
     * @return string
     */
    public function __invoke(string $id)
    {
        $message = $this->getSession()->getFlashMessage($id);

        if (null === $message) {
            return null;
        }

        return $this->getTranslator()->translate($message);
    }

}