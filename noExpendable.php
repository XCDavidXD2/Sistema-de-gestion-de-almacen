<?php

include_once 'InterficieNaming.php';
include_once 'ClasseAbstractaItem.php';

class NoExpendable extends Item implements Naming
{
    private $warrantyDueDate;
    private $purchaseDate;
    private const TAX = 21;



    public function __toString()
    {

        return parent::__toString() . "\n" .
            "Garantía: " . ($this->warrantyDueDate ? $this->warrantyDueDate->format('Y-m-d') : 'No disponible') . "\n" .
            "TAX: " . self::TAX . "\n" .
            "Fecha de compra: " . $this->purchaseDate->format('Y-m-d') . "\n"; // Formateamos la fecha aquí
    }


    public function __construct($name, $weight, $price, $isNew, $purchaseDate)
    {
        parent::__construct($name, $weight, $price, $isNew);
        $this -> warrantyDueDate = null;
        $this -> purchaseDate = date_create($purchaseDate);
    }


    public function fullfillWarranty()
    {
        /*
        date_add suma a una fecha un intervalo de tiempo, esta funcion cambia el valor de su primer parametro directamente,
        para evitarlo usamos clone para hacer una copia del valor de purchaseDate.
        Como intervalo de tiempo usamos la funcion date_interval_create_from_date_string la qual recibe un string
        cuyo valor sera un numero y la forma de tiempo (en este caso 2 y años)
        */
        $this->warrantyDueDate = date_add(clone $this->purchaseDate, date_interval_create_from_date_string("2 years"));
    }

    public function calcPriceWithTax()
    {
        $preuAmbTaxes = $this->price * (1 + (self::TAX / 100));
        return $preuAmbTaxes;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($n)
    {
        $this->name = $n;
    }
}
