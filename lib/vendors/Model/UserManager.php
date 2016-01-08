<?php
namespace Model;

use Entity\User;
use \OCFram\Manager;

abstract class UserManager extends Manager
{
    /**
     * M�thode permettant de v�rifier si un utilisateur est en base de donn�es
     * @param User $user
     * @return boolean True or False si l'utilisateur est trouv� en base de donn�es
     */
    abstract public function matchUser(User $user);
}