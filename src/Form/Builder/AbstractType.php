<?php

namespace Hardkode\Form\Builder;

use Assert\Assert;
use Hardkode\Service\SessionAwareInterface;
use Hardkode\Service\SessionAwareTrait;
use Hardkode\Service\Translator;
use Hardkode\Service\TranslatorAwareInterface;
use Hardkode\Service\TranslatorAwareTrait;

/**
 * Class AbstractType
 * @package Hardkode\Form\Builder
 */
abstract class AbstractType implements TranslatorAwareInterface, SessionAwareInterface
{

    use TranslatorAwareTrait;
    use SessionAwareTrait;

    /** @var AbstractBuilder */
    protected $form;

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
     * @param array $definition
     * @param array $validators
     */
    public function __construct(array $definition, array $validators = [])
    {
        Assert::that($definition)->notEmptyKey('name');

        $this->name       = $definition['name'];
        $this->definition = $definition;
        $this->validators = $validators;
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
                $this->definition['class'] = $this->definition['class'] . ' error';
                $this->definition['data-error'] = $translator->translate($validatorObj->getErrorMessage());
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