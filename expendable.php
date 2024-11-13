<?php

include_once 'InterficieNaming.php';
include_once 'ClasseAbstractaItem.php';

class Expendable extends Item implements Naming
{
    protected $expireDate;
    protected $tax = 10;


    public function __construct($name, $weight, $price, $isNew, $expireDate, $tax)
    {
        parent::__construct($name, $weight, $price, $isNew);
        $this->expireDate = $expireDate;
        $this->tax = $tax;
    }
    public function __toString()
    {
        return parent::__toString() . "\n" .
           "Fecha de expiración: " . $this->expireDate . "\n" .
           "Tasa: " . $this->tax . "%\n";
    }
    public function getExpireDate()
    {
        return $this -> expireDate;
    }
    public function calcPriceWithTax()
    {
        $cambio = ($this->price * ($this->tax / 100));
        $precioFinal = $this->price + $cambio;
        return $precioFinal;
    }
    public function isExpired()
    {

        $fechaActual = date('Y-m-d'); //Devuelve la hora actual

        if ($fechaActual > $this->expireDate) {
            $expirado = true;
        } else {
            $expirado = false;
        }
        return $expirado;
    }
}
