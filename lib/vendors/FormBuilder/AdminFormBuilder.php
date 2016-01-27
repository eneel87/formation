<?php
namespace FormBuilder;

use \OCFram\FormBuilder;
use \OCFram\StringField;

class AdminFormBuilder extends FormBuilder
{
    public function build()
    {
        $this->form->add(new StringField([
            'label' => 'Login',
            'name' => 'login'
        ]))
            ->add(new StringField([
                'label' => 'Password',
                'name' => 'password'
            ]))
                ->add(new StringField([
                    'label' => 'Level',
                    'name' => 'level'
                ]));
    }
}