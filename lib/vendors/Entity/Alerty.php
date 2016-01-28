<?php

namespace Entity;

use \OCFram\Entity;

class Alerty extends Entity
{
    protected $FAY_id;
    protected $FAY_type;

    /**
     * @return mixed
     */
    public function FAY_id()
    {
        return $this->FAY_id;
    }

    /**
     * @param mixed $FAY_id
     */
    public function setFAY_id($FAY_id)
    {
        $this->FAY_id = (int) $FAY_id;
    }

    /**
     * @return mixed
     */
    public function FAY_type()
    {
        return $this->FAY_type;
    }

    /**
     * @param mixed $FAY_type
     */
    public function setFAY_type($FAY_type)
    {
        $this->FAY_type = $FAY_type;
    }


}