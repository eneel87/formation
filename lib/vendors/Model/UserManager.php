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


    /**
     * Méthode permettant de récupérer un utilisateur via son login et son password
     * @param $login
     * @param $password
     * @return $user
     */
    abstract public function getUserUsingLoginAndPassword($login, $password);

    /**
     * Méthode retournant un utilisateur précis
     * @param $id int L'identifiant de l'utilisateur à récupérer
     * @return User L'utilisateur
     */
    abstract public function getUnique($id);

    /**
     * Méthode permettant d'ajouter un utiilsateur.
     * @param $user User L'utilisateur à ajouter
     * @return void
     */
    abstract protected function add(User $user);

    /**
     * Méthode permettant de modifier un utilisateur.
     * @param $user user l'utiliasteur à modifier
     * @return void
     */
    abstract protected function modify(User $user);

    /**
     * Méthode permettant d'enregistrer un utilisateur.
     * @param $user User l'utilisateur à enregistrer
     * @see self::add()
     * @see self::modify()
     * @return void
     */
    public function save(User $user)
    {
        var_dump($user);

        if($user->isValid())
        {
            $user->isNew() ? $this->add($user) : $this->modify($user);
        }
        else
        {
            throw new \RuntimeException('L\'utilisateur doit êter validé pour être enregistré');
        }
    }
}