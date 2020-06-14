<?php


namespace Hardkode\Form;


use Hardkode\Form\Builder\AbstractBuilder;
use Hardkode\Form\Builder\Type\Button;
use Hardkode\Form\Builder\Type\Input;
use Hardkode\Form\Builder\Type\Textarea;
use Hardkode\Form\Builder\Validator\NotEmpty;
use Psr\Http\Message\RequestInterface;

class ContactForm extends AbstractBuilder
{

    public function create(RequestInterface $request)
    {
        $this->add(Input::class, [
            'name' => 'firstname',
            'type' => 'text',
            'placeholder' => 'form.field.placeholder.firstname'
        ], [
            NotEmpty::class
        ]);

        $this->add(Input::class, [
            'name' => 'lastname',
            'type' => 'text',
            'placeholder' => 'form.field.placeholder.lastname'
        ], [
            NotEmpty::class
        ]);

        $this->add(Input::class, [
            'name' => 'email',
            'type' => 'email',
            'placeholder' => 'form.field.placeholder.email'
        ], [
            NotEmpty::class
        ]);

        $this->add(Textarea::class, [
            'name' => 'message',
            'cols' => '20',
            'rows' => '6',
            'placeholder' => 'form.field.placeholder.message'
        ], [
            NotEmpty::class
        ]);

        $this->add(Button::class, [
            'name' => 'submit',
            'text' => 'form.button.send.text'
        ]);
    }

}