<?php

namespace Hardkode\Controller;

use Apix\Log\Logger;
use Assert\Assert;
use Assert\Assertion;
use Hardkode\Config;
use Hardkode\Exception\PermissionException;
use Hardkode\Form\Builder\AbstractBuilder;
use Hardkode\Model\Role;
use Hardkode\Service\EntityManager;
use Hardkode\Service\RequestAwareInterface;
use Hardkode\Service\RequestAwareTrait;
use Hardkode\Service\Session;
use Hardkode\Service\User;
use Hardkode\View\Renderer;
use Nyholm\Psr7\Factory\Psr17Factory;
use ORM\Exception\IncompletePrimaryKey;
use ORM\Exception\NoEntity;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class AbstractController
 * @package Hardkode\Controller
 */
abstract class AbstractController implements RequestAwareInterface
{

    use RequestAwareTrait;

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

    /** @var User */
    private $user;

    /** @var array */
    private $renderer = [];

    public function __construct()
    {
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
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * @param string $className
     * @return AbstractBuilder
     */
    public function createForm(string $className)
    {
        Assert::that($className)->classExists();
        return new $className($this->getRequest(), $this->getLogger(), $this->getSession());
    }

    /**
     * @return Renderer
     */
    public function getRenderer(): Renderer
    {
        $identifier = get_called_class();

        if (empty($this->renderer[$identifier])) {
            $renderer = new Renderer($this->config, $this->logger, $this->session, $this->user);
            $this->renderer[$identifier] = $renderer;
        }

        return $this->renderer[$identifier];
    }

    /**
     * @param string $role
     *
     * @throws PermissionException
     */
    protected function requiresPermission(string $role)
    {
        try {
            $roles = $this->getEntityManager()->fetch(Role::class)->all();
        } catch (IncompletePrimaryKey | NoEntity $e) {
            $this->getLogger()->error($e->getMessage(), ['exception' => $e]);
            throw new PermissionException($e->getMessage());
        }

        $roleNames = array_map(function(Role $role) {
            return $role->name;
        }, $roles);

        Assert::that($role)->inArray($roleNames);

        $userRole = $this->getSession()->get('userRole');

        Assert::that($userRole)->notNull(function() {
            $this->getSession()->set('referer', $this->getRequest()->getUri()->getPath());
            $this->getLogger()->info('Role entry not found in Users session. Redirecting to login...');
            $this->redirect('login');
        });

        if ($userRole === 'admin') {
            return;
        }

        if ($role !== $userRole) {
            throw new PermissionException('Invalid permissions for user "' . $this->getSession()->get('userName') . '" for route "' . $this->getRequest()->getUri()->getPath(). '".');
        }

        return;

    }

    /**
     * @param string $routeName
     *
     * @return bool
     */
    protected function redirect(string $routeName)
    {
        $path = $this->getConfig()->get('routes')[$routeName]['path'] ?? null;

        if (null === $path) {
            $path = $routeName;
        }

        header('HTTP/2 307 Temporary redirect');
        header('Location: ' . $path);

        return true;
    }

    /**
     * @return void
     */
    protected function addDefaultAssets()
    {
        // Must be implemented by children
    }

}