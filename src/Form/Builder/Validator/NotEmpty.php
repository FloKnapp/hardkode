<?php

namespace Hardkode\Form\Builder\Validator;

use Hardkode\Form\Builder\AbstractValidator;

/**
 * Class NotEmpty
 * @package Hardkode\Form\Builder\Validator
 */
class NotEmpty extends AbstractValidator
{

    /**
     * @param $data
     * @return bool
     */
    public function exec($data): bool
    {
        return !empty($data);
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return 'form.field.empty';
    }

}