<?php
namespace OCFram;

abstract class FormBuilder
{
    protected $form;
    protected $Manager;

    public function __construct(Entity $entity, Manager $Manager=null)
    {
        $this->setForm(new Form($entity));

        $this->Manager = $Manager;
    }

    abstract public function build();

    public function setForm(Form $form)
    {
        $this->form = $form;
    }

    public function form()
    {
        return $this->form;
    }

    public function Manager()
    {
        return $this->Manager;
    }
}