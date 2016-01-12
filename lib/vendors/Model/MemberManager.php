<?php
namespace Model;

use Entity\Member;
use \OCFram\Manager;

abstract class MemberManager extends Manager
{

    const ADMINISTRATOR=1;

    /**
     * Méthode permettant de vérifier si un utilisateur est en base de données
     * @param Member $Member
     * @return boolean True or False si l'utilisateur est trouvé en base de données
     */
    abstract public function matchMember(Member $member);


    /**
     * Méthode permettant de récupérer un utilisateur via son login et son password
     * @param $login
     * @param $password
     * @return $Member Member
     */
    abstract public function getMemberUsingLoginAndPassword($login, $password);

    /**
     * Méthode retournant un utilisateur précis
     * @param $id int L'identifiant de l'utilisateur à récupérer
     * @return Member L'utilisateur
     */
    abstract public function getUnique($id);

    /**
     * Méthode retournant une liste d'utilisateurs demandés
     * @param $debut int Le premier utilisateur à sélectionner
     * @param $limite int Le nombre d'utilisateurs à sélectionner
     * @return array La liste des utilisateurs. Chaque entrée est une instance de Member
     */
    abstract public function getList($debut = -1, $limite = -1);

    /**
     * Méthode permettant d'ajouter un utiilsateur.
     * @param $Member Member L'utilisateur à ajouter
     * @return void
     */
    abstract protected function add(Member $Member);

    /**
     * Méthode permettant de modifier un utilisateur.
     * @param $Member Member l'utiliasteur à modifier
     * @return void
     */
    abstract protected function modify(Member $Member);

    /**
     * Méthode permettant d'enregistrer un utilisateur.
     * @param $Member Member l'utilisateur à enregistrer
     * @see self::add()
     * @see self::modify()
     * @return void
     */
    public function save(Member $Member)
    {
        var_dump($Member);

        if($Member->isValid())
        {

            $Member->isNew() ? $this->add($Member) : $this->modify($Member);
        }
        else
        {
            throw new \RuntimeException('L\'utilisateur doit êter validé pour être enregistré');
        }
    }

    /**
     * Méthode renvoyant le nombre d'utilisateurs total.
     * @return int
     */
    abstract public function count();
}