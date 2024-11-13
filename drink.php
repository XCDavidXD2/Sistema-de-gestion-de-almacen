<?php

include_once 'expendable.php';
include_once 'InterficieNaming.php';

class Drink extends Expendable implements Naming
{
    private $isAlcoholic;
    private $volume; //volumen de la beguda en ml

    public function __toString()
    {
        return parent::__toString() . "\n" .
        "¿Es alcohólica? " . ($this->isAlcoholic ? "Sí" : "No") . "\n" .
        "Volumen: " . $this->volume . " ml\n";
    }
    public function __construct($name, $weight, $price, $isNew, $expireDate, $tax, $isAlcoholic, $volume)
    {
        parent::__construct($name, $weight, $price, $isNew, $expireDate, $tax);
        $this -> isAlcoholic = $isAlcoholic;
        $this -> volume = $volume;
    }

    public function getisAlcoholic()
    {
        return $this -> isAlcoholic;
    }

    public function getVolume()
    {
        return $this -> volume;
    }

    public function setisAlcoholic($a)
    {
        $this -> isAlcoholic = $a;
    }

    public function setVolume($a)
    {
        $this -> volume = $a;
    }

    public function toGallons()
    {
        $galones = $this->volume / 3785.41;
        return $galones;
    }
    public function toLiters()
    {
        $litros = $this->volume / 1000;
        return $litros;
    }
}
