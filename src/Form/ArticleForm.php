<?php

namespace Hardkode\Form;

use Hardkode\Form\Builder\AbstractBuilder;
use Hardkode\Form\Builder\Type\Button;
use Hardkode\Form\Builder\Type\Input;
use Hardkode\Form\Builder\Type\Textarea;
use Hardkode\Form\Builder\Validator\NotEmpty;
use Psr\Http\Message\RequestInterface;

/**
 * Class ArticleForm
 * @package Hardkode\Form
 */
class ArticleForm extends AbstractBuilder
{

    /**
     * @param RequestInterface $request
     * @throws \ReflectionException
     */
    public function create(RequestInterface $request)
    {
        $this->add(Input::class, [
            'type' => 'text',
            'name' => 'title',
        ], [
            NotEmpty::class
        ]);

        $this->add(Textarea::class, [
            'name' => 'text',
        ], [
            NotEmpty::class
        ]);

        $this->add(Button::class, [
            'text' => 'Speichern',
            'name' => 'button'
        ], [
            NotEmpty::class
        ]);
    }

}