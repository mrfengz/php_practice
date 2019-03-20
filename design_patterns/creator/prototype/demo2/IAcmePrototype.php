<?php
abstract class IAcmePrototype
{
    protected $name;
    protected $id;
    protected $employeePic;
    protected $dept;

    //dept
    abstract function setDept($orgCode);

    abstract function getDept();

    //Name
    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    //ID
    public function setId($emId)
    {
        $this->id = $emId;
    }

    public function getId()
    {
        return $this->id;
    }

    //empolyee picture
    public function setPic($pic)
    {
        $this->pic = $pic;
    }

    public function getPic()
    {
        return $this->pic;
    }

    abstract function __clone();
}