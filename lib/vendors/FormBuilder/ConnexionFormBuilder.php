<?php
namespace FormBuilder;

use \OCFram\FormBuilder;
use OCFram\NotNullValidator;
use \OCFram\StringField;

class ConnexionFormBuilder extends FormBuilder
{
    public function build()
    {
        $this->form->add(new StringField([
            'label' => 'Login',
            'name' => 'login',
            'validators' => [
                new NotNullValidator('Votre login ne doit pas être vide')
            ]
        ]))
            ->add(new StringField([
                'label' => 'Password',
                'type' => 'password',
                'name' => 'password',
                'validators' => [
                    new NotNullValidator('Votre password ne doit pas être vide')
                ]
            ]));
    }
}