<?php

namespace Hardkode;

use Hardkode\Exception\MethodNotFoundException;
use Hardkode\Exception\NotFoundException;
use Hardkode\Service\EntityManager;
use Hardkode\Service\EntityManagerAwareInterface;
use Hardkode\Service\EntityManagerAwareTrait;
use Hardkode\Service\Session;
use Hardkode\Service\SessionAwareInterface;
use Hardkode\Service\SessionAwareTrait;
use Hardkode\Service\User;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * Class Dispatcher
 * @package Hardkode
 */
class Dispatcher implements EntityManagerAwareInterface, LoggerAwareInterface, SessionAwareInterface
{

    use EntityManagerAwareTrait;
    use SessionAwareTrait;
    use LoggerAwareTrait;

    /** @var Config */
    private $config;

    /** @var Session */
    private $session;

    /** @var EntityManager */
    private $em;

    /** @var User */
    private $user;

    /** @var Container */
    private $container;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     *
     * @throws MethodNotFoundException
     * @throws NotFoundException
     * @throws \ReflectionException
     */
    public function forward(RequestInterface $request)
    {
        $result = $this->getRouteItemByPath($request->getUri()->getPath());

        if (empty($result)) {
            throw new NotFoundException('No matching route for path "' . $request->getUri()->getPath() . '" found.');
        }

        $controller = Initializer::load($result['class'], [$request, $this->config, $this->logger, $this->getEntityManager(), $this->session]);

        if (!method_exists($controller, $result['action'])) {
            throw new MethodNotFoundException('Method "' . $result['action'] . '" not found in class "' . $result['class'] . '"');
        }

        return call_user_func_array([$controller, $result['action']], array_values($result['parameters']));
    }

    /**
     * @param string $path
     * @return array
     */
    private function getRouteItemByPath(string $path): array
    {
        foreach ($this->config->get('routes') as $name => $route) {

            if (false === $this->hasPathMatch($path, $route['path'])) {
                continue;
            }

            $this->logger->debug('Matched route "' . $name . '" for path "' . $path . '".');

            $segments = $this->getPathSegments($path, $route['path']);
            array_shift($segments);

            return $route + ['parameters' => $segments];

        }

        return [];
    }

    /**
     * @param string $path
     * @param string $configPath
     * @return bool
     */
    private function hasPathMatch(string $path, string $configPath)
    {
        $matches = $this->matchPath($path, $configPath);
        return !empty($matches[0]);
    }

    /**
     * @param $path
     * @param $configPath
     * @return array
     */
    private function getPathSegments($path, $configPath): array
    {
        $pathMatch   = $this->matchPath($path, $configPath);
        $matchedPath = array_shift($pathMatch);

        if (null === $matchedPath) {
            return [];
        }

        $result = [$matchedPath];

        foreach ($pathMatch as $key => $value) {
            if (!is_numeric($key)) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * @param string $configPath
     * @return string
     */
    private function convertPathToRegex(string $configPath): string
    {
        $pathRegex  = preg_replace_callback('/\/:\w+/u', function($item) {
            $str = substr($item[0], 2);
            return '/(?<' . $str . '>[a-zäöüA-ZÄÖÜ0-9~_\-\.\:\@]+)';
        }, $configPath);

        return '/^' . str_replace('/', '\/', $pathRegex) . '$/u';
    }

    /**
     * @param string $path
     * @param string $configPath
     *
     * @return array
     */
    private function matchPath(string $path, string $configPath): array
    {
        $matches   = [];
        $pathRegex = $this->convertPathToRegex($configPath);
        preg_match($pathRegex, urldecode($path), $matches);

        return $matches;
    }

}