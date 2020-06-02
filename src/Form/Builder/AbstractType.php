<?php

namespace Hardkode\Form\Builder;

use Assert\Assert;
use Hardkode\Service\Session;
use Hardkode\Service\Translator;

/**
 * Class AbstractType
 * @package Hardkode\Form\Builder
 */
abstract class AbstractType
{

    /** @var AbstractBuilder */
    protected $form;

    /** @var Session */
    protected $session;

    /** @var string */
    protected $name;

    /** @var string */
    protected $value;

    /** @var array */
    protected $definition;

    /** @var array */
    protected $validators;

    /** @var array */
    protected $errorMessages;

    /**
     * AbstractType constructor.
     *
     * @param array           $definition
     * @param array           $validators
     */
    public function __construct(array $definition, array $validators = [])
    {
        Assert::that($definition)->notEmptyKey('name');

        $this->name       = $definition['name'];
        $this->definition = $definition;
        $this->validators = $validators;
    }

    /**
     * @param Session $session
     */
    public function setSession(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @param AbstractBuilder $form
     */
    public function setForm(AbstractBuilder $form)
    {
        $this->form = $form;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getValue():? string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return AbstractBuilder
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        $translator = new Translator('de');

        foreach ($this->validators as $validator) {

            if (!class_exists($validator)) {
                continue;
            }

            /** @var AbstractValidator $validatorObj */
            $validatorObj = new $validator($this, $this->session);

            if (false === $validatorObj->exec($this->getValue())) {
                $this->errorMessages[] = $translator->translate($validatorObj->getErrorMessage());
            }

        }

        return empty($this->errorMessages);
    }

    /**
     * @return string
     */
    public function getErrorMessages(): string
    {
        $output = '';

        if (empty($this->errorMessages)) {
            return $output;
        }

        $pattern = '<div class="error-message" data-type="%s" data-name="%s">%s</div>';

        foreach ($this->errorMessages as $message) {
            $output .= sprintf($pattern, $this->definition['type'] ?? 'unknown', $this->getName(), $message);
        }

        return $output;
    }

    /**
     * @return string
     */
    abstract public function render();

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

}