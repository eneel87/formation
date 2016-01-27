<?php
namespace OCFram;

class PasswordConfirmationValidator extends Validator
{
    protected $password;

    public function __construct($errorMessage, $password)
    {
        parent::__construct($errorMessage);

        $this->setPassword($password);
    }

    public function isValid($value)
    {
        return $value == $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }
}