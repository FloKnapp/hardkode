<?php

namespace Hardkode\View\Helper;

use Apix\Log\Logger;
use Hardkode\Config;
use Hardkode\Service\Session;
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

    /** @var Session */
    private $session;

    /** @var \Hardkode\Service\User */
    private $user;

    /**
     * AbstractViewHelper constructor.
     * @param Renderer $view
     * @param Logger   $logger
     * @param Config   $config
     * @param Session  $session
     * @param \Hardkode\Service\User $user
     */
    public function __construct(Renderer $view, Logger $logger, Config $config, Session $session, \Hardkode\Service\User $user)
    {
        $this->view    = $view;
        $this->logger  = $logger;
        $this->config  = $config;
        $this->session = $session;
        $this->user    = $user;
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

    /**
     * @return Session
     */
    public function getSession(): Session
    {
        return $this->session;
    }

    /**
     * @return \Hardkode\Service\User
     */
    public function getUser(): \Hardkode\Service\User
    {
        return $this->user;
    }

}