<?php

namespace Hardkode\View\Helper;

use Apix\Log\Logger;
use Hardkode\Config;
use Hardkode\View\Renderer;

/**
 * Class AbstractViewHelper
 * @package Hardkode\View\Helper
 */
abstract class AbstractViewHelper
{

    /** @var Renderer */
    private $view;

    /** @var Logger */
    private $logger;

    /** @var Config */
    private $config;

    /**
     * AbstractViewHelper constructor.
     * @param Renderer $view
     * @param Logger   $logger
     * @param Config   $config
     */
    public function __construct(Renderer $view, Logger $logger, Config $config)
    {
        $this->view = $view;
        $this->logger = $logger;
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
     * @return Logger
     */
    public function getLogger(): Logger
    {
        return $this->logger;
    }

    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

}