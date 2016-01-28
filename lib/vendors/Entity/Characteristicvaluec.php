<?php
namespace Entity;

use \OCFram\Entity;

class Characteristicvaluec extends Entity
{
    protected $FVC_id;
    protected $FVC_fk_FHC;
    protected $FVC_value;

    const INVALID_FHC = 1;
    const INVALID_VALUE = 2;

    public function setFVC_id($id)
    {
        $this->FVC_id = (int) $id;
        $this->id = (int) $id;
        return $this;
    }

    public function setFVC_fk_FHC($id)
    {
        if(!is_int($id))
        {
            $this->erreurs[] = self::INVALID_FHC;
        }

        $this->FVC_fk_FHC = $id;
        return $this;
    }

    public function setFVC_value($value)
    {
        if(!is_string($value))
        {
            $this->erreurs[] = self::INVALID_VALUE;
        }

        $this->FVC_value = $value;
        return $this;
    }

    public function FVC_id()
    {
        return $this->FVC_id;
    }

    public function FVC_fk_FHC()
    {
        return $this->FVC_fk_FHC;
    }

    public function FVC_value()
    {
        return $this->FVC_value;
    }
}