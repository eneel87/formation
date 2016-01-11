<?php
namespace Entity;

use \OCFram\Entity;

class Member extends Entity
{
    protected $login,
              $password,
              $level,
              $dateAjout,
              $dateModif;


    const LOGIN_INVALIDE = 1;
    const PASSWORD_INVALIDE = 2;
    const LEVEL_INVALIDE = 3;

    public function isValid()
    {
        return !(empty($this->login) || empty($this->password) || empty($this->level));
    }

    public function setLogin($login)
    {
        if(!is_string($login) || empty($login))
        {
            $this->erreurs[] = self::LOGIN_INVALIDE;
        }

        $this->login = $login;
    }

    public function setPassword($password)
    {
        if(!is_string($password) || empty($password))
        {
            $this->erreurs[] = self::PASSWORD_INVALIDE;
        }

        $this->password = $password;
    }

    public function setLevel($level)
    {
        $lev = (int) $level;

        if(!is_int($lev) || empty($lev))
        {
            $this->erreurs[] = self::LEVEL_INVALIDE;
        }

        $this->level = $lev;
    }

    public function setDateAjout(\DateTime $dateAjout)
    {
        $this->dateAjout = $dateAjout;
    }

    public function setDateModif(\DateTime $dateModif)
    {
        $this->dateModif = $dateModif;
    }

    public function login()
    {
        return $this->login;
    }

    public function password()
    {
        return $this->password;
    }

    public function level()
    {
        return $this->level;
    }

    public function dateAjout()
    {
        return $this->dateAjout;
    }

    public function dateModif()
    {
        return $this->dateModif;
    }
}