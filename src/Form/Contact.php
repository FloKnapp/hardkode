<?php


namespace Hardkode\Form;


use Hardkode\Form\Builder\AbstractBuilder;
use Hardkode\Form\Builder\Type\Input;
use Hardkode\Form\Builder\Type\Textarea;
use Hardkode\Form\Builder\Validator\NotEmpty;
use Psr\Http\Message\RequestInterface;

class Contact extends AbstractBuilder
{

    public function create(RequestInterface $request)
    {
        $this->add(Input::class, [
            'name' => 'firstname',
            'type' => 'text'
        ], [
            NotEmpty::class
        ]);

        $this->add(Input::class, [
            'name' => 'lastname',
            'type' => 'text'
        ], [
            NotEmpty::class
        ]);

        $this->add(Input::class, [
            'name' => 'email',
            'type' => 'email'
        ], [
            NotEmpty::class
        ]);

        $this->add(Textarea::class, [
            'name' => 'message',
            'cols' => '20',
            'rows' => '6'
        ], [
            NotEmpty::class
        ]);
    }

}