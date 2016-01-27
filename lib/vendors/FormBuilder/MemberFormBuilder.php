<?php
namespace FormBuilder;

use \OCFram\FormBuilder;
use OCFram\LoginValidator;
use OCFram\MaxLengthValidator;
use \OCFram\EmailValidator;
use OCFram\NotNullValidator;
use \OCFram\StringField;
use \OCFram\PasswordConfirmationValidator;

class MemberFormBuilder extends FormBuilder
{
    public function build()
    {
        $this->form->add(new StringField([
            'label' => 'Login',
            'name' => 'login',
            'maxLength' => 30,
            'validators' => [
                new LoginValidator('Ce login est déjà utilisé, veuillez en choisir un autre', $this->Manager()),
                new NotNullValidator('Votre login ne doit pas être vide, un peu d\'inspiration'),
                new MaxLengthValidator('Votre login ne doit pas dépasser 30 caractères', 30)
            ]
        ]))
            ->add(new StringField([
                'label' => 'Password',
                'type' => 'password',
                'name' => 'password',
                'validators' => [
                    new NotNullValidator('Votre password ne doit pas être vide')
                ]
            ]))
            ->add(new StringField([
                'label' => 'Password Confirmation',
                'type' => 'password',
                'name' => 'password_confirmation',
                'validators' => [
                    new PasswordConfirmationValidator('Vos deux password doivent être identiques', $this->form->entity()->password())
                ]
            ]))
            ->add(new StringField([
                'label' => 'Email',
                'type' => 'email',
                'name' => 'email',
                'validators' => [
                    new EmailValidator('Votre email n\'est pas au bon format', $this->Manager())
                ]
            ]));
    }
}