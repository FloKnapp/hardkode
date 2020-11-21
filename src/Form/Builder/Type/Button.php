<?php

namespace Hardkode\Form\Builder\Type;

use Assert\Assert;
use Hardkode\Form\Builder\AbstractType;

/**
 * Class Button
 * @package Hardkode\Form\Builder\Type
 */
class Button extends AbstractType
{

    public function __construct(array $definition)
    {
        parent::__construct($definition);
        Assert::that($definition)->notEmptyKey('text', 'You have to define a button text.');
        $this->definition = $definition;
    }

    public function render()
    {
        return '<button>' . $this->getTranslator()->translate($this->definition['text']) . '</button>';
    }

}