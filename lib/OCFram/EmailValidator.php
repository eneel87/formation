<?php
namespace OCFram;

use Model\MemberManagerPDO;

class EmailValidator extends Validator
{
    protected $Manager;

    public function __construct($errorMessage, $Manager)
    {
        parent::__construct($errorMessage);

        $this->setManager($Manager);
    }

    public function isValid($value)
    {
        $MemberManager = $this->Manager;
        $Member = $MemberManager->getMemberUsingEmail($value);

        if($Member->id()==0)
        {
            $this->setErrorMessage('Cette adresse email est déjà utilisée');

            return preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $value);
        }

        return false;
    }

    public function setManager(MemberManagerPDO $Manager)
    {
        $this->Manager = $Manager;
    }
}