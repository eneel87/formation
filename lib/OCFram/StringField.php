<?php
namespace OCFram;

class StringField extends Field
{
    protected $maxLength;
    protected $type = 'text';

    public function buildWidget()
    {
        $widget = '';

        $widget .= '<label>'.$this->label.'</label><input type="'.$this->type.'" name="'.$this->name.'"';

        if (!empty($this->value))
        {
            $widget .= ' value="'.htmlspecialchars($this->value).'"';
        }

        if (!empty($this->maxLength))
        {
            $widget .= ' maxlength="'.$this->maxLength.'"';
        }

        $widget .= ' />';

        if (!empty($this->errorMessage))
        {
            $widget .= '<p style="color:red">'.$this->errorMessage.'</p>';
        }

        return $widget;
    }

    public function setMaxLength($maxLength)
    {
        $maxLength = (int) $maxLength;

        if ($maxLength > 0)
        {
            $this->maxLength = $maxLength;
        }
        else
        {
            throw new \RuntimeException('La longueur maximale doit �tre un nombre sup�rieur � 0');
        }
    }

    public function setType($type)
    {
        if(!is_string($type))
        {
            throw new \RuntimeException('Le type du champ doit être une chaîne de caractères');
        }

        $this->type = $type;
    }
}