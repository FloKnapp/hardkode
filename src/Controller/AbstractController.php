<?php

namespace Hardkode\Controller;

use Apix\Log\Logger;
use Hardkode\Config;
use Hardkode\Service\EntityManager;
use Hardkode\Service\Session;
use Hardkode\View\Renderer;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class AbstractController
 * @package Hardkode\Controller
 */
abstract class AbstractController
{

    /** @var RequestInterface */
    private $request;

    /** @var Logger */
    private $logger;

    /** @var Config */
    private $config;

    /** @var Session */
    private $session;

    /** @var EntityManager */
    private $em;

    /** @var array */
    private $renderer = [];

    /**
     * AbstractController constructor.
     *
     * @param RequestInterface $request
     * @param Logger           $logger
     * @param Config           $config
     * @param Session          $session
     * @param EntityManager    $em
     */
    public function __construct(RequestInterface $request, Logger $logger, Config $config, Session $session, EntityManager $em)
    {
        $this->request = $request;
        $this->logger  = $logger;
        $this->config  = $config;
        $this->session = $session;
        $this->em      = $em;
    }

    /**
     * @param string $template
     * @param array $variables
     *
     * @return ResponseInterface
     */
    public function render(string $template, array $variables = []): ResponseInterface
    {
        $result = '';

        $this->logger->debug('Start rendering of template "' . $template . '".');

        try {

            $this->getRenderer()->setTemplate($template);
            $this->getRenderer()->setVariables($variables);

            $this->addDefaultAssets();

            $result = $this->getRenderer()->render();

            $this->logger->debug('Successfully rendered template "' . $template . '".');

        } catch (\Throwable $t) {
            $this->logger->error($t->getMessage(), ['exception' => $t]);
        }

        $psr17Factory = new Psr17Factory();
        $response     = $psr17Factory->createResponse();
        $body         = $psr17Factory->createStream($result);
        return $response->withBody($body);
    }

    /**
     * @return Logger
     */
    public function getLogger(): Logger
    {
        return $this->logger;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * @return Session
     */
    public function getSession(): Session
    {
        return $this->session;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager(): EntityManager
    {
        return $this->em;
    }

    /**
     * @return Renderer
     */
    public function getRenderer(): Renderer
    {
        $identifier = get_called_class();

        if (empty($this->renderer[$identifier])) {
            $renderer = new Renderer($this->config, $this->logger);
            $this->renderer[$identifier] = $renderer;
        }

        return $this->renderer[$identifier];
    }

    /**
     * @return void
     */
    protected function addDefaultAssets()
    {
        // Must be implemented by children
    }

}