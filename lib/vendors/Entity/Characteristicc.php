<?php
namespace Entity;

use \OCFram\Entity;

class Characteristicc extends Entity
{
    protected $FHC_id;
    protected $FHC_name;

    const INVALID_NAME = 1;

    public function FHC_id()
    {
        return $this->FHC_id;
    }

    public function FHC_name()
    {
        return $this->FHC_name;
    }

    public function setFHC_id($id)
    {
        $this->FHC_id = (int) $id;
        $this->id = (int) $id;
        return $this;
    }

    public function setFHC_name($name)
    {
        if(!is_string($name))
        {
            $this->erreurs[] = self::INVALID_NAME;
        }

        $this->FHC_name = $name;
        return $this;
    }
}