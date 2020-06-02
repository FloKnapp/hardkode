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
        $this->add(
            new Input([
                'type' => 'text',
                'name' => 'username'
                ], [
                    NotEmpty::class
                ])
        );

        $this->add(
            new Input([
                'type' => 'password',
                'name' => 'password'
            ], [
                NotEmpty::class
            ])
        );

        $this->add(new Button([
            'name' => 'button',
            'text' => 'Hallo'
        ]));
    }

}