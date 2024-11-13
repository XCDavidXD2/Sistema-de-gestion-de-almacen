<?php

include_once 'InterficieNaming.php';

abstract class Item implements Naming
{
    protected $name;
    protected $weight;
    protected $price;
    protected $isNew;

    public function __construct($name, $weight, $price, $isNew)
    {
        $this->name = $name;
        $this->weight = $weight;
        $this->price = $price;
        $this->isNew = $isNew;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getWeight()
    {
        return $this->weight;
    }
    public function getPrice()
    {
        return $this->price;
    }
    public function getIsNew()
    {
        return $this->isNew;
    }
    public function setName($n)
    {
        $this->name = $n;
    }
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }
    public function setPrice($price)
    {
        $this->price = $price;
    }
    public function setIsNew($isNew)
    {
        $this->isNew = $isNew;
    }
    public function __toString()
    {
        return "Nombre: " . $this->name . "\n" .
           "Peso: " . $this->weight . " kg\n" .
           "Precio: " . $this->price . " €\n" .
           "Es nuevo: " . ($this->isNew ? "Sí" : "No") . "\n";
    }

    abstract public function calcPriceWithTax();
}
