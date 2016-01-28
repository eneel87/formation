<?php

namespace Entity;

use \OCFram\Entity;

class Alertd extends Entity
{
    protected $FAD_id;
    protected $FAD_fk_FAC;
    protected $FAD_fk_FHC;
    protected $FAD_fk_FVC;

    /**
     * @return mixed
     */
    public function FAD_id()
    {
        return $this->FAD_id;
    }

    /**
     * @param mixed $FAD_id
     */
    public function setFAD_id($FAD_id)
    {
        $this->FAD_id = (int) $FAD_id;
        $this->id = (int) $FAD_id;
    }

    /**
     * @return mixed
     */
    public function FAD_fk_FAC()
    {
        return $this->FAD_fk_FAC;
    }

    /**
     * @param mixed $FAD_fk_FAC
     */
    public function setFAD_fk_FAC($FAD_fk_FAC)
    {
        $this->FAD_fk_FAC = (int) $FAD_fk_FAC;
    }

    /**
     * @return mixed
     */
    public function FAD_fk_FHC()
    {
        return $this->FAD_fk_FHC;
    }

    /**
     * @param mixed $FAD_fk_FHC
     */
    public function setFAD_fk_FHC($FAD_fk_FHC)
    {
        $this->FAD_fk_FHC = (int) $FAD_fk_FHC;
    }

    /**
     * @return mixed
     */
    public function FAD_fk_FVC()
    {
        return $this->FAD_fk_FVC;
    }

    /**
     * @param mixed $FAD_fk_FVC
     */
    public function setFAD_fk_FVC($FAD_fk_FVC)
    {
        $this->FAD_fk_FVC = (int) $FAD_fk_FVC;
    }


}