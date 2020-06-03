<?php

namespace Hardkode\View\Helper;

use Hardkode\Config;
use Hardkode\Service\LoggerAwareInterface;
use Hardkode\Service\LoggerAwareTrait;
use Hardkode\Service\SessionAwareInterface;
use Hardkode\Service\SessionAwareTrait;
use Hardkode\Service\TranslatorAwareInterface;
use Hardkode\Service\TranslatorAwareTrait;
use Hardkode\Service\UserAwareInterface;
use Hardkode\Service\UserAwareTrait;
use Hardkode\View\Renderer;

/**
 * Class AbstractViewHelper
 * @package Hardkode\View\Helper
 */
abstract class AbstractViewHelper implements LoggerAwareInterface, SessionAwareInterface, UserAwareInterface, TranslatorAwareInterface
{
    use UserAwareTrait;
    use LoggerAwareTrait;
    use SessionAwareTrait;
    use TranslatorAwareTrait;

    /** @var Renderer */
    private $view;

    /** @var Config */
    private $config;

    /**
     * AbstractViewHelper constructor.
     *
     * @param Renderer   $view
     * @param Config     $config
     */
    public function __construct(Renderer $view, Config $config) {
        $this->view   = $view;
        $this->config = $config;
    }

    /**
     * @return Renderer
     */
    public function getView(): Renderer
    {
        return $this->view;
    }

    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

}