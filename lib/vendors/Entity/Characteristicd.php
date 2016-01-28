<?php

namespace Entity;

use \OCFram\Entity;

class Characteristicd extends Entity
{
    protected $FHD_id;
    protected $FHD_fk_FAY;
    protected $FHD_fk_FHC;

    /**
     * @return mixed
     */
    public function FHD_id()
    {
        return $this->FHD_id;
    }

    /**
     * @param mixed $FHD_id
     */
    public function setFHD_id($FHD_id)
    {
        $this->FHD_id = (int) $FHD_id;
    }

    /**
     * @return mixed
     */
    public function FHD_fk_FAY()
    {
        return $this->FHD_fk_FAY;
    }

    /**
     * @param mixed $FHD_fk_FAY
     */
    public function setFHD_fk_FAY($FHD_fk_FAY)
    {
        $this->FHD_fk_FAY = (int) $FHD_fk_FAY;
    }

    /**
     * @return mixed
     */
    public function FHD_fk_FHC()
    {
        return $this->FHD_fk_FHC;
    }

    /**
     * @param mixed $FHD_fk_FHC
     */
    public function setFHD_fk_FHC($FHD_fk_FHC)
    {
        $this->FHD_fk_FHC = (int) $FHD_fk_FHC;
    }

}