<?php
namespace Model;

use Entity\Member;
use \OCFram\Manager;

abstract class MemberManager extends Manager
{

    const ADMINISTRATOR=1;
    const WRITER=2;

    /**
     * M�thode permettant de v�rifier si un utilisateur est en base de donn�es
     * @param Member $Member
     * @return boolean True or False si l'utilisateur est trouv� en base de donn�es
     */
    abstract public function matchMember(Member $member);


    /**
     * M�thode permettant de r�cup�rer un utilisateur via son login et son password
     * @param $login
     * @param $password
     * @return $Member Member
     */
    abstract public function getMemberUsingLoginAndPassword($login, $password);

    /**
     * M�thode retournant un utilisateur pr�cis
     * @param $id int L'identifiant de l'utilisateur � r�cup�rer
     * @return Member L'utilisateur
     */
    abstract public function getUnique($id);

    /**
     * M�thode retournant une liste d'utilisateurs demand�s
     * @param $debut int Le premier utilisateur � s�lectionner
     * @param $limite int Le nombre d'utilisateurs � s�lectionner
     * @return array La liste des utilisateurs. Chaque entr�e est une instance de Member
     */
    abstract public function getList($debut = -1, $limite = -1);

    /**
     * M�thode permettant d'ajouter un utiilsateur.
     * @param $Member Member L'utilisateur � ajouter
     * @return void
     */
    abstract protected function add(Member $Member);

    /**
     * M�thode permettant de modifier un utilisateur.
     * @param $Member Member l'utiliasteur � modifier
     * @return void
     */
    abstract protected function modify(Member $Member);

    /**
     * M�thode permettant d'enregistrer un utilisateur.
     * @param $Member Member l'utilisateur � enregistrer
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
            throw new \RuntimeException('L\'utilisateur doit �ter valid� pour �tre enregistr�');
        }
    }

    /**
     * M�thode renvoyant le nombre d'utilisateurs total.
     * @return int
     */
    abstract public function count();
}