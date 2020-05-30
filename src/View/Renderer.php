<?php

namespace Hardkode\View;

use Apix\Log\Logger;
use Hardkode\Config;
use Hardkode\Exception\FileNotFoundException;
use Hardkode\Exception\TemplateException;
use Hardkode\Exception\ViewHelperException;
use Hardkode\View\Helper\AbstractViewHelper;

/**
 * Class View
 * @package Hardkode
 */
class Renderer
{

    /** @var Config */
    private $config;

    /** @var Logger */
    private $logger;

    /**
     * Holds the view template
     * @var string
     */
    private $template = '';

    /**
     * Holds the view variables
     * @var array
     */
    private $variables = [];

    /**
     * Holds the parent template
     * @var Renderer|null
     */
    private $parentView = null;

    /**
     * Renderer constructor.
     * @param Config $config
     * @param Logger $logger
     */
    public function __construct(Config $config, Logger $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * Set template for this view
     *
     * @param string $template
     * @return self
     *
     * @throws FileNotFoundException
     */
    public function setTemplate(string $template = ''): self
    {
        $templatePath = __DIR__ . '/../../template';

        $template = $templatePath . $template;

        if (empty($template) || !file_exists($template) || is_dir($template)) {
            throw new FileNotFoundException('Template "' . $template . '" not found');
        }

        $this->logger->debug('Template "' . $template . '" found.');

        $this->template = $template;

        return $this;
    }

    /**
     * Add javascript from outside
     *
     * @param string $file
     * @return self
     */
    public function addScript(string $file): self
    {
        $this->variables['assetsJs'][] = $file;
        return $this;
    }

    /**
     * Add stylesheet from outside
     *
     * @param string $file
     * @return self
     */
    public function addStylesheet(string $file): self
    {
        $this->variables['assetsCss'][] = $file;
        return $this;
    }

    /**
     * Return current template
     *
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * Set a single variable
     *
     * @param string $key
     * @param string|array $value
     */
    public function setVariable(string $key = '', $value = ''): void
    {
        $this->variables[$key] = $value;
    }

    /**
     * Get a single variable
     *
     * @param string $key
     * @return string|array
     */
    public function getVariable(string $key)
    {
        return $this->variables[$key] ?? '';
    }

    /**
     * Check if variable exists
     *
     * @param string $key
     * @return bool
     */
    public function hasVariable(string $key): bool
    {
        if(isset($this->variables[$key])) {
            return true;
        }

        return false;
    }

    /**
     * Set many variables at once
     *
     * @param array $variables
     * @return self
     */
    public function setVariables(array $variables = []): self
    {
        foreach($variables AS $key=>$value) {
            $this->setVariable($key, $value);
        }

        return $this;
    }

    /**
     * Get all variables
     *
     * @return array
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    /**
     * Define parent template
     *
     * @param Renderer $view
     */
    public function setParentView(Renderer $view): void
    {
        $this->logger->debug('Add layout for template "' . $this->getTemplate() . '".');
        $this->parentView = $view;
    }

    /**
     * Get parent template
     *
     * @return Renderer
     */
    public function getParentView():? Renderer
    {
        return $this->parentView;
    }

    /**
     * Strip spaces and tabs from output
     *
     * @param $output
     * @return string
     */
    private function normalizeOutput($output): string
    {
        $output = str_replace('> <', '><', trim($output));

        if (getenv('APPLICATION_ENV') === 'production') {
            $this->logger->debug('Compressing output for production environment.');
            return preg_replace('/(\s{2,}|\t|\r|\n)/', ' ', $output);
        }

        // Dev environment
        $this->logger->debug('Remove unnecessary spaces and tabs from output.');
        return str_replace(["\t", "\r", "\n\n\n"], ' ', $output);
    }

    /**
     * Render the current view
     *
     * @return string
     *
     * @throws TemplateException
     * @throws FileNotFoundException
     * @throws ViewHelperException
     */
    public function render(): string
    {
        try {

            extract($this->variables, EXTR_OVERWRITE);

            ob_start();

            include $this->getTemplate();

            $content = ob_get_contents();

            ob_end_clean();

        } catch (\Exception $e) {
            ob_end_clean();
            $this->logger->error($e->getMessage(), ['exception' => $e]);
            throw new TemplateException($e->getMessage(), 0, $e);
        }

        if (ob_get_length() > 0) {
            $this->logger->debug('Detected still open output buffering, closing.');
            ob_end_clean();
        }

        if ($this->getParentView() instanceof Renderer) {
            return $this->normalizeOutput($this->getParentView()->setVariables($this->getVariables())->render());
        }

        return $this->normalizeOutput($content);

    }

    /**
     * Magic method for providing a view helpers
     *
     * @param  string $name      The class name
     * @param  array  $arguments Arguments if given
     *
     * @return AbstractViewHelper
     *
     * @throws ViewHelperException
     */
    public function __call($name, $arguments)
    {
        $viewHelper = __NAMESPACE__ . '\Helper\\' . ucfirst($name);

        if (class_exists($viewHelper)) {

            /** @var AbstractViewHelper $class */
            $class = new $viewHelper($this, $this->logger, $this->config);

            return $this->_callUserFuncArray($class, $arguments);

        }

        throw new ViewHelperException('No view helper for "' . $name . '" found.');
    }

    /**
     * Abstraction of call_user_func_array
     *
     * @param $class
     * @param $arguments
     *
     * @return mixed
     */
    private function _callUserFuncArray($class, $arguments)
    {
        return call_user_func_array($class, $arguments);
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        unset($this->variables, $this->template);
    }

}