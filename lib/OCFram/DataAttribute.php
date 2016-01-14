<?php
namespace OCFram;

class DataAttribute
{
    protected $name;
    protected $value;
    protected $erreurs = [];

    const INVALID_NAME = 1;

    public function __construct($name, $value)
    {
        $this->setName($name);
        $this->setValue($value);
    }

    public function name()
    {
        return $this->name;
    }

    public function value()
    {
        return $this->value;
    }

    public function setName($name)
    {
        if (!is_string($name))
        {
            $this->erreurs[] = self::INVALID_NAME;
        }

        $this->name = $name;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }
}