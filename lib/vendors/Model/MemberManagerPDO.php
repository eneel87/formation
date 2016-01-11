<?php
namespace Model;


use Entity\Member;

class MemberManagerPDO extends MemberManager
{
    public function matchMember(Member $Member)
    {
        $sql = 'SELECT COUNT(*)
                FROM T_FOR_memberc
                WHERE FMC_login = :login AND FMC_password = :password';

        $requete = $this->dao->prepare($sql);
        $requete->bindValue(':login', $Member->login());
        $requete->bindValue(':password', $Member->password());
        $requete->execute();

        $result = $requete->fetchColumn();

        if($result)
        {
            return true;
        }

        return false;
    }

    public function getMemberUsingLoginAndPassword($login, $password)
    {
        $sql = 'SELECT FMC_id as id, FMC_login as login, FMC_password as password, FMC_fk_FMY as level
                FROM T_FOR_memberc
                WHERE FMC_login = :login AND FMC_password = :password';

        $requete = $this->dao->prepare($sql);
        $requete->bindValue(':login', $login);
        $requete->bindValue(':password', $password);
        $requete->execute();

        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Member');

        return $Member = $requete->fetch();
    }

    public function getUnique($id)
    {
        $sql = 'SELECT FMC_id as id, FMC_login as login, FMC_password as password, FMC_fk_FMY as level
                FROM T_FOR_memberc
                WHERE FMC_id = :id';

        $requete = $this->dao->prepare($sql);
        $requete->bindvalue(':id', (int) $id, \PDO::PARAM_INT);
        $requete->execute();

        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Member');

        return $Member = $requete->fetch();
    }

    public function getList($debut = -1, $limite = -1)
    {
        $sql = 'SELECT FMC_id as id, FMC_login as login, FMC_password as password, FMC_fk_FMY as level, FMC_dateadd as dateAjout, FMC_dateupdate as dateModif FROM T_FOR_memberc ORDER BY FMC_id DESC';

        if ($debut != -1 || $limite != -1)
        {
            $sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
        }

        $requete = $this->dao->query($sql);
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Member');

        $listeMember = $requete->fetchAll();

        foreach ($listeMember as $Member)
        {
            $Member->setDateAjout(new \DateTime($Member->dateAjout()));
            $Member->setDateModif(new \DateTime($Member->dateModif()));
        }

        $requete->closeCursor();

        return $listeMember;
    }

    public function add(Member $Member)
    {
        $requete = $this->dao->prepare('INSERT INTO T_FOR_memberc SET FMC_login = :login, FMC_password = :password, FMC_fk_FMY= :level');

        $requete->bindValue(':login', $Member->login());
        $requete->bindValue(':password', $Member->password());
        $requete->bindValue(':level', $Member->level());

        $requete->execute();
    }

    public function modify(Member $Member)
    {
        $requete = $this->dao->prepare('UPDATE T_FOR_memberc SET FMC_login = :login, FMC_password = :password, FMC_fk_FMY = :level, FMC_dateupdate = NOW() WHERE FMC_id = :id');

        $requete->bindValue(':login', $Member->login());
        $requete->bindValue(':password', $Member->password());
        $requete->bindValue(':level', $Member->level());
        $requete->bindValue(':id', (int) $Member->id(), \PDO::PARAM_INT);

        $requete->execute();
    }

    public function count()
    {
        return $this->dao->query('SELECT COUNT(*) FROM T_FOR_memberc')->fetchColumn();
    }

    public function delete($id)
    {
        $this->dao->exec('DELETE FROM t_for_memberc WHERE FMC_id = '.(int) $id);
    }
}