<?php
namespace Model;

use \Entity\User;

class UserManagerPDO extends UserManager
{
    public function matchUser(User $user)
    {
        $sql = 'SELECT COUNT(*)
                FROM T_FOR_userc
                WHERE FUC_login = :login AND FUC_password = :password';

        $requete = $this->dao->prepare($sql);
        $requete->bindValue(':login', $user->login());
        $requete->bindValue(':password', $user->password());
        $requete->execute();

        $result = $requete->fetchColumn();

        if($result)
        {
            return true;
        }

        return false;
    }

    public function getUserUsingLoginAndPassword($login, $password)
    {
        $sql = 'SELECT FUC_login as login, FUC_password as password, FUC_fk_FUY as level
                FROM T_FOR_userc
                WHERE FUC_login = :login AND FUC_password = :password';

        $requete = $this->dao->prepare($sql);
        $requete->bindValue(':login', $login);
        $requete->bindValue(':password', $password);
        $requete->execute();

        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\User');

        return $user = $requete->fetch();
    }

    public function getUnique($id)
    {
        $sql = 'SELECT FUC_id as id, FUC_login as login, FUC_password as password, FUC_fk_FUY as level
                FROM T_FOR_userc
                WHERE FUC_id = :id';

        $requete = $this->dao->prepare($sql);
        $requete->bindvalue(':id', (int) $id, \PDO::PARAM_INT);

        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\User');

        return $user = $requete->fetch();
    }

    public function add(User $user)
    {
        $requete = $this->dao->prepare('INSERT INTO T_FOR_userc SET FUC_login = :login, FUC_password = :password, FUC_fk_FUY= :level');

        $requete->bindValue(':login', $user->login());
        $requete->bindValue(':password', $user->password());
        $requete->bindValue(':level', $user->level());

        $requete->execute();
    }

    public function modify(User $user)
    {
        $requete = $this->dao->prepare('UPDATE T_FOR_userc SET login = :login, password = :password, level = :level WHERE id = :id');

        $requete->bindValue(':login', $user->login());
        $requete->bindValue(':password', $user->password());
        $requete->bindValue(':level', $user->level());
        $requete->bindValue(':id', $user->id(), \PDO::PARAM_INT);

        $requete->execute();
    }
}