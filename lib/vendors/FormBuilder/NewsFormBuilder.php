<?php
namespace FormBuilder;

use \OCFram\FormBuilder;
use \OCFram\StringField;
use \OCFram\TextField;
use \OCFram\MaxLengthValidator;
use \OCFram\NotNullValidator;

class NewsFormBuilder extends FormBuilder
{
    public function build()
    {
        $this->form->add(new StringField([
                'label' => 'Titre',
                'name' => 'titre',
                'maxLength' => 100,
                'validators' => [
                    new MaxLengthValidator('Le titre sp�cifi� est trop long (100 caract�res maximum)', 100),
                    new NotNullValidator('Merci de sp�cifier le titre de la news'),
                ],
            ]))
            ->add(new TextField([
                'label' => 'Contenu',
                'name' => 'contenu',
                'rows' => 8,
                'cols' => 60,
                'validators' => [
                    new NotNullValidator('Merci de sp�cifier le contenu de la news'),
                ],
            ]));
    }
}