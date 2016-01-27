<?php
namespace OCFram;

use Model\MemberManagerPDO;

class LoginValidator extends Validator
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
        $Member = $MemberManager->getMemberUsingLogin($value);

        if($Member->id() == 0)
        {
            return true;
        }

        return false;
    }

    public function setManager(MemberManagerPDO $Manager)
    {
        $this->Manager = $Manager;
    }
}