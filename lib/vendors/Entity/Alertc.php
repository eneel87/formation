<?php

namespace Entity;

use \OCFram\Entity;

class Alertc extends Entity
{
    protected $FAC_id;
    protected $FAC_fk_FAY;
    protected $FAC_fk_FAE;
    protected $FAC_dateregistration;
    protected $FAC_dateprocess;

    const INVALID_FAY = 1;
    const INVALID_FAE = 2;

    public function setFAC_id($id)
    {
        $this->FAC_id = (int) $id;
        $this->id = (int) $id;
        return $this;
    }

    public function setFAC_fk_FAY($id)
    {
        if(!is_int($id))
        {
            $this->erreurs[] = self::INVALID_FAY;
        }

        $this->FAC_fk_FAY = $id;
        return $this;
    }

    public function setFAC_fk_FAE($id)
    {
        if(!is_int($id))
        {
            $this->erreurs[] = self::INVALID_FAE;
        }

        $this->FAC_fk_FAE = $id;
        return $this;
    }

    public function setFAC_dateregistration(\DateTime $date)
    {
        $this->FAC_dateregistration = $date;
    }

    public function setFAC_dateprocess(\DateTime $date)
    {
        $this->FAC_dateprocess = $date;
    }

    /**
     * @return mixed
     */
    public function FAC_id()
    {
        return $this->FAC_id;
    }

    /**
     * @return mixed
     */
    public function FAC_fk_FAY()
    {
        return $this->FAC_fk_FAY;
    }

    /**
     * @return mixed
     */
    public function FAC_fk_FAE()
    {
        return $this->FAC_fk_FAE;
    }

    /**
     * @return mixed
     */
    public function FAC_dateregistration()
    {
        return $this->FAC_dateregistration;
    }

    /**
     * @return mixed
     */
    public function FAC_dateprocess()
    {
        return $this->FAC_dateprocess;
    }
}