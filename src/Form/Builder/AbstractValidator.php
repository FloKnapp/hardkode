<?php

namespace Hardkode\Form\Builder;

use Hardkode\Service\Session;

/**
 * Class AbstractValidator
 * @package Hardkode\Form\Builder
 */
abstract class AbstractValidator
{

    /** @var AbstractType */
    protected $field;

    /** @var Session */
    protected $session;

    /**
     * AbstractValidator constructor.
     * @param AbstractType $field
     * @param Session      $session
     */
    public function __construct(AbstractType $field, Session $session)
    {
        $this->field = $field;
        $this->session = $session;
    }

    /**
     * @return string
     */
    abstract public function getErrorMessage(): string;

    /**
     * @param $value
     * @return bool
     */
    abstract public function exec($value): bool;

}