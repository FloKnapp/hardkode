<?php

namespace Hardkode\View;

use Hardkode\Config;
use Hardkode\Initializer;
use Hardkode\Service\LoggerAwareTrait;
use Hardkode\Exception\TemplateException;
use Hardkode\Service\LoggerAwareInterface;
use Hardkode\Exception\ViewHelperException;
use Hardkode\View\Helper\AbstractViewHelper;
use Hardkode\Exception\FileNotFoundException;

/**
 * Class View
 * @package Hardkode
 */
class Renderer implements LoggerAwareInterface
{

    use LoggerAwareTrait;

    /** @var Config */
    private $config;

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
     * @param Config     $config
     */
    public function __construct(Config $config)
    {
        $this->config  = $config;
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

            $content = '';

            extract($this->variables, EXTR_OVERWRITE);

            ob_start();

            $this->logger->debug('Opening output buffering for template "' . $this->template . '"...');

            include $this->getTemplate();

            $content = ob_get_contents();

            ob_end_clean();

            $this->logger->debug('Cleared output buffer for template "' . $this->template . '"...');

        } catch (\Throwable $t) {
            $this->logger->error($t->getMessage(), ['exception' => $t]);
            throw new TemplateException($t->getMessage(), 0, $t);
        } finally {
            $this->clearOutputBuffer();
        }

        if ($this->getParentView() instanceof Renderer) {
            return $this->normalizeOutput($this->getParentView()->setVariables($this->getVariables())->render());
        }

        return $this->normalizeOutput($content);

    }

    /**
     * @return void
     */
    private function clearOutputBuffer()
    {
        while (ob_get_level() > 0) {
            $this->logger->debug('Detected still open output buffering, closing.');
            ob_end_clean();
        }
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
     * @throws \ReflectionException
     */
    public function __call($name, $arguments)
    {
        $viewHelper = __NAMESPACE__ . '\Helper\\' . ucfirst($name);

        if (class_exists($viewHelper)) {

            /** @var AbstractViewHelper $class */
            $class = Initializer::load($viewHelper, [$this, $this->config]);

            try {
                return $this->_callUserFuncArray($class, $arguments);
            } catch (\Throwable $t) {
                $this->clearOutputBuffer();
                $this->getLogger()->error('ViewHelperException: ' . $t->getMessage(), ['exception' => $t]);
            }

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
     * @return void
     */
    public function reset()
    {
        unset($this->variables, $this->template);
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->reset();
    }

}