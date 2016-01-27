<?php
namespace Entity;

use \OCFram\Entity;

class Member extends Entity implements \JsonSerializable
{
    protected $login,
              $password,
              $password_confirmation,
              $level,
              $dateAjout,
              $dateModif,
              $levelNom,
              $email;


    const LOGIN_INVALIDE = 1;
    const PASSWORD_INVALIDE = 2;
    const LEVEL_INVALIDE = 3;
    const LEVEL_NOM_INVALIDE = 4;
    const EMAIL_INVALIDE = 5;
    const PASSWORD_CONFIRMATION_INVALIDE = 6;

    public function isValid()
    {
        return !(empty($this->login) || empty($this->password) || empty($this->level) || empty($this->email));
    }

    public function jsonSerialize()
    {
        $Membre_a = array(
            'membre_id' => $this->id(),
            'membre_login' => htmlspecialchars($this->login()),
            'membre_levelNom' => $this->levelNom()
        );

        return $Membre_a;
    }

    public function setLogin($login)
    {
        if(!is_string($login) || empty($login))
        {
            $this->erreurs[] = self::LOGIN_INVALIDE;
        }

        $this->login = $login;
        return $this;
    }

    public function setPassword($password)
    {
        if(!is_string($password) || empty($password))
        {
            $this->erreurs[] = self::PASSWORD_INVALIDE;
        }

        $this->password = $password;
        return $this;
    }

    public function setPassword_confirmation($password_confirmation)
    {
        if(!is_string($password_confirmation) || empty($password_confirmation))
        {
            $this->erreurs[] = self::PASSWORD_CONFIRMATION_INVALIDE;
        }

        $this->password_confirmation = $password_confirmation;
        return $this;
    }

    public function setEmail($email)
    {
        if(!is_string($email) || empty($email))
        {
            $this->erreurs[] = self::EMAIL_INVALIDE;
        }

        $this->email = $email;
        return $this;
    }

    public function setLevel($level)
    {
        $lev = (int) $level;

        if(!is_int($lev) || empty($lev))
        {
            $this->erreurs[] = self::LEVEL_INVALIDE;
        }

        $this->level = $lev;
        return $this;
    }

    public function setDateAjout(\DateTime $dateAjout)
    {
        $this->dateAjout = $dateAjout;
        return $this;
    }

    public function setDateModif(\DateTime $dateModif)
    {
        $this->dateModif = $dateModif;
        return $this;
    }

    public function setLevelNom($levelNom)
    {
        if(!is_string($levelNom) || empty($levelNom))
        {
            $this->erreurs[] = self::LEVEL_NOM_INVALIDE;
        }

        $this->levelNom = $levelNom;
        return $this;
    }

    public function login()
    {
        return $this->login;
    }

    public function password()
    {
        return $this->password;
    }

    public function password_confirmation()
    {
        return $this->password_confirmation;
    }

    public function email()
    {
        return $this->email;
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

    public function levelNom()
    {
        return $this->levelNom;
    }
}