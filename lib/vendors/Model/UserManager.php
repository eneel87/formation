<?php
namespace Model;

use Entity\User;
use \OCFram\Manager;

abstract class UserManager extends Manager
{
    /**
     * Méthode permettant de vérifier si un utilisateur est en base de données
     * @param User $user
     * @return boolean True or False si l'utilisateur est trouvé en base de données
     */
    abstract public function matchUser(User $user);
}