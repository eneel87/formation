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
}