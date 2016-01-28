<?php

namespace Entity;

use \OCFram\Entity;

class Alerte extends Entity
{
    protected $FAE_id;
    protected $FAE_description;

    /**
     * @return mixed
     */
    public function FAE_id()
    {
        return $this->FAE_id;
    }

    /**
     * @param mixed $FAE_id
     */
    public function setFAE_id($FAE_id)
    {
        $this->FAE_id = (int) $FAE_id;
    }

    /**
     * @return mixed
     */
    public function FAE_description()
    {
        return $this->FAE_description;
    }

    /**
     * @param mixed $FAE_description
     */
    public function setFAE_description($FAE_description)
    {
        $this->FAE_description = $FAE_description;
    }

}