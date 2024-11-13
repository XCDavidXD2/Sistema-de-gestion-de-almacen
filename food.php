<?php

include_once 'expendable.php';
include_once 'interficieNaming.php';

class Food extends Expendable implements Naming
{
    private array $type;

    public function __toString()
    {
        $typeString = implode(", ", $this->type);
        return parent::__toString() . "\n" .
                "Tipus: " . $typeString;
    }

    public function __construct($name, $weight, $price, $isNew, $expireDate, $tax, $type)
    {
        parent::__construct($name, $weight, $price, $isNew, $expireDate, $tax);
        $this-> type = $type;
    }

    public function getType()
    {
        return $this -> type;
    }

    public function setType($a)
    {
        $this -> type = $a;
    }
}
