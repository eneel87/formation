<?php
namespace Model;

use Entity\Alertc;
use Entity\Alertd;
use Entity\Characteristicc;

class FORManagerPDO extends FORManager
{
    public function getCharacteristiccUsingName($name)
    {
        $sql = 'SELECT *
                FROM T_FOR_characteristicc
                WHERE FHC_name = :name';

        $r = $this->dao->prepare($sql);
        $r->bindValue(':name', $name, \PDO::PARAM_STR);
        $r->execute();

        $r->setFetchMode(\PDO::FETCH_ASSOC);

        return new Characteristicc($r->fetch());
    }

    public function addCharacteristicvaluec($characteristic_id, $value)
    {
        $sql = 'INSERT INTO T_FOR_characteristicvaluec SET FVC_fk_FHC = :id, FVC_value = :value';

        $r = $this->dao->prepare($sql);
        $r->bindValue(':id', (int) $characteristic_id, \PDO::PARAM_INT);
        $r->bindValue(':value', $value, \PDO::PARAM_STR);
        $r->execute();

        return $this->dao->lastInsertId();
    }

    public function addAlertc(Alertc $Alertc)
    {
        $sql = 'INSERT INTO T_FOR_alertc SET FAC_fk_FAY = :FAY_id, FAC_fk_FAE = :FAE_id, FAC_dateregistration = NOW()';

        $r = $this->dao->prepare($sql);
        $r->bindValue('FAY_id', $Alertc->FAC_fk_FAY(), \PDO::PARAM_INT);
        $r->bindValue('FAE_id', $Alertc->FAC_fk_FAE(), \PDO::PARAM_INT);
        $r->execute();

        return $this->dao->lastInsertId();
    }

    public function addAlertd(Alertd $Alertd)
    {
        $sql = 'INSERT INTO T_FOR_alertd SET FAD_fk_FAC = :FAC_id, FAD_fk_FHC = :FHC_id, FAD_fk_FVC = :FVC_id';

        $r = $this->dao->prepare($sql);
        $r->bindValue(':FAC_id', $Alertd->FAD_fk_FAC(), \PDO::PARAM_INT);
        $r->bindValue(':FHC_id', $Alertd->FAD_fk_FHC(), \PDO::PARAM_INT);
        $r->bindValue(':FVC_id', $Alertd->FAD_fk_FVC(), \PDO::PARAM_INT);
        $r->execute();

        return $this->dao->lastInsertId();
    }

}