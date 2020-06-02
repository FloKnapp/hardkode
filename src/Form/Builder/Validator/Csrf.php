<?php

namespace Hardkode\Form\Builder\Validator;

use Hardkode\Form\Builder\AbstractValidator;

/**
 * Class Csrf
 * @package Hardkode\Form\Builder\Validator
 */
class Csrf extends AbstractValidator
{

    /**
     * @param $value
     * @return bool
     */
    public function exec($value): bool
    {
        return $value === $this->session->get('csrf_' . $this->field->getForm()->getId());
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return 'form.csrf.invalid';
    }

}