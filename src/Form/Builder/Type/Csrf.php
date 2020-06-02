<?php

namespace Hardkode\Form\Builder\Type;

use Assert\Assert;
use Hardkode\Form\Builder\AbstractType;
use Hardkode\Form\Builder\Validator\NotEmpty;
use Hardkode\Form\Builder\Validator\Csrf as CsrfValidator;

/**
 * Class Csrf
 * @package Hardkode\Form\Builder\Type
 */
class Csrf extends AbstractType
{

    /**
     * Csrf constructor.
     *
     * @param array $definition
     * @param array $validators
     */
    public function __construct(array $definition, array $validators = [])
    {
        parent::__construct($definition, $validators);

        Assert::that($definition)->notEmptyKey('value');

        $this->definition = $definition;
    }

    /**
     * @return string
     */
    public function render()
    {
        return sprintf('<input type="hidden" name="csrf" value="%s" />', $this->definition['value']);
    }

}