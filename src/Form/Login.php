<?php

namespace Hardkode\Form;

use Hardkode\Form\Builder\AbstractBuilder;
use Hardkode\Form\Builder\Type\Button;
use Hardkode\Form\Builder\Type\Input;
use Hardkode\Form\Builder\Validator\NotEmpty;
use Psr\Http\Message\RequestInterface;

/**
 * Class Login
 * @package Hardkode\Form
 */
class Login extends AbstractBuilder
{

    /**
     * @param RequestInterface $request
     *
     * @return void
     */
    public function create(RequestInterface $request)
    {
        $this->add(Input::class,
            [
                'type'          => 'text',
                'name'          => 'username',
                'placeholder'   => 'form.field.placeholder.username',
                'data-lpignore' => 'true'
            ], [
                NotEmpty::class
            ]
        );

        $this->add(Input::class, [
                'type'          => 'password',
                'name'          => 'password',
                'placeholder'   => 'form.field.placeholder.password',
                'data-lpignore' => 'true'
            ], [
                NotEmpty::class
            ]
        );

        $this->add(Button::class, [
            'name' => 'button',
            'text' => 'form.button.login.text'
        ]);
    }

}