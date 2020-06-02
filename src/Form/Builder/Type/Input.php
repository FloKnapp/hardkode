<?php

namespace Hardkode\Form\Builder\Type;

use Assert\Assert;
use Hardkode\Form\Builder\AbstractType;

/**
 * Class Input
 * @package Hardkode\Form\Builder\Type
 */
class Input extends AbstractType
{

    /**
     * Input constructor.
     * @param array $definition
     * @param array $validators
     */
    public function __construct(array $definition, array $validators = [])
    {
        parent::__construct($definition, $validators);

        Assert::that($definition)->notEmptyKey('type');

        $this->definition = $definition;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $result = '<input';

        if (!empty($this->getValue())) {
            $this->definition['value'] = $this->getValue();
        }

        foreach ($this->definition as $attribute => $value) {
            $result .= sprintf(' %s="%s"', $attribute, $value);
        }

        $result .= ' />';

        return $result;
    }

}