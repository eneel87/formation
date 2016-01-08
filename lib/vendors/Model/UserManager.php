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


    /**
     * M�thode permettant de r�cup�rer un utilisateur via son login et son password
     * @param $login
     * @param $password
     * @return $user
     */
    abstract public function getUserUsingLoginAndPassword($login, $password);

    /**
     * M�thode retournant un utilisateur pr�cis
     * @param $id int L'identifiant de l'utilisateur � r�cup�rer
     * @return User L'utilisateur
     */
    abstract public function getUnique($id);

    /**
     * M�thode permettant d'ajouter un utiilsateur.
     * @param $user User L'utilisateur � ajouter
     * @return void
     */
    abstract protected function add(User $user);

    /**
     * M�thode permettant de modifier un utilisateur.
     * @param $user user l'utiliasteur � modifier
     * @return void
     */
    abstract protected function modify(User $user);

    /**
     * M�thode permettant d'enregistrer un utilisateur.
     * @param $user User l'utilisateur � enregistrer
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
            throw new \RuntimeException('L\'utilisateur doit �ter valid� pour �tre enregistr�');
        }
    }
}