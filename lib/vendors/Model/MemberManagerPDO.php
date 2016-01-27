<?php
namespace Model;


use Entity\Member;

class MemberManagerPDO extends MemberManager
{
    public function getMemberUsingLogin($login)
    {
        $sql = 'SELECT *
                FROM T_FOR_memberc
                WHERE FMC_login = :login';

        $requete = $this->dao->prepare($sql);
        $requete->bindValue(':login', $login, \PDO::PARAM_STR);
        $requete->execute();

        $data = $requete->fetch();

        $Member = new Member();
        $Member->setId($data['FMC_id'])
            ->setLogin($data['FMC_login'])
            ->setPassword($data['FMC_password'])
            ->setEmail($data['FMC_email'])
            ->setDateAjout(new \DateTime($data['FMC_dateadd']))
            ->setDateModif(new \DateTime($data['FMC_dateupdate']));

        $requete->closeCursor();

        return $Member;
    }

    public function getMemberUsingEmail($email)
    {
        $sql = 'SELECT *
                FROM T_FOR_memberc
                WHERE FMC_login = :email';

        $requete = $this->dao->prepare($sql);
        $requete->bindValue(':email', $email, \PDO::PARAM_STR);
        $requete->execute();

        $data = $requete->fetch();

        $Member = new Member();
        $Member->setId($data['FMC_id'])
            ->setLogin($data['FMC_login'])
            ->setPassword($data['FMC_password'])
            ->setEmail($data['FMC_email'])
            ->setDateAjout(new \DateTime($data['FMC_dateadd']))
            ->setDateModif(new \DateTime($data['FMC_dateupdate']));

        $requete->closeCursor();

        return $Member;
    }

    public function matchMember(Member $Member)
    {
        $sql = 'SELECT COUNT(*)
                FROM T_FOR_memberc
                WHERE FMC_login = :login AND FMC_password = :password';

        $requete = $this->dao->prepare($sql);
        $requete->bindValue(':login', $Member->login(), \PDO::PARAM_STR);
        $requete->bindValue(':password', $Member->password(), \PDO::PARAM_STR);
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
        $requete->bindValue(':login', $login, \PDO::PARAM_STR);
        $requete->bindValue(':password', $password, \PDO::PARAM_STR);
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
        $sql = 'SELECT *
                FROM T_FOR_memberc
                INNER JOIN T_FOR_membery ON FMC_fk_FMY = FMY_id
                ORDER BY FMC_id DESC';

        if ($debut != -1 || $limite != -1)
        {
            $sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
        }

        $requete = $this->dao->query($sql);

        $listeMember = array();

        while($data = $requete->fetch())
        {
            $Member = new Member();
            $Member->setId($data['FMC_id'])
                   ->setLogin($data['FMC_login'])
                   ->setPassword($data['FMC_password'])
                   ->setDateAjout(new \DateTime($data['FMC_dateadd']))
                   ->setDateModif(new \DateTime($data['FMC_dateupdate']))
                   ->setLevelNom($data['FMY_type']);

            $listeMember[] = $Member;
        }

        $requete->closeCursor();

        return $listeMember;
    }

    public function add(Member $Member)
    {
        $requete = $this->dao->prepare('INSERT INTO T_FOR_memberc SET FMC_login = :login, FMC_password = :password, FMC_email = :email, FMC_fk_FMY= :level, FMC_dateadd = NOW(), FMC_dateupdate = NOW()');

        $requete->bindValue(':login', $Member->login(), \PDO::PARAM_STR);
        $requete->bindValue(':password', $Member->password(), \PDO::PARAM_STR);
        $requete->bindValue(':email', $Member->email(), \PDO::PARAM_STR);
        $requete->bindValue(':level', $Member->level(), \PDO::PARAM_INT);

        $requete->execute();
    }

    public function modify(Member $Member)
    {
        $requete = $this->dao->prepare('UPDATE T_FOR_memberc SET FMC_login = :login, FMC_password = :password, FMC_fk_FMY = :level, FMC_dateupdate = NOW() WHERE FMC_id = :id');

        $requete->bindValue(':login', $Member->login(), \PDO::PARAM_STR);
        $requete->bindValue(':password', $Member->password(), \PDO::PARAM_STR);
        $requete->bindValue(':level', $Member->level(), \PDO::PARAM_INT);
        $requete->bindValue(':id', (int) $Member->id(), \PDO::PARAM_INT);

        $requete->execute();
    }

    public function count()
    {
        return $this->dao->query('SELECT COUNT(*) FROM T_FOR_memberc')->fetchColumn();
    }

    public function delete($id)
    {
        $sql = 'DELETE FROM t_for_memberc WHERE FMC_id = :id';

        $requete = $this->dao->prepare($sql);
        $requete->bindValue('id', $id, \PDO::PARAM_INT);
        $requete->execute();
    }
}