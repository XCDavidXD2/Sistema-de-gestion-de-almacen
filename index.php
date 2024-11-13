<?php

//Carlos Ayuso & David Correa
include_once 'InterficieNaming.php';
include_once 'ClasseAbstractaItem.php';
include_once 'noExpendable.php';
include_once 'expendable.php';
include_once 'food.php';
include_once 'drink.php';
include_once 'warehouse.php';
$bebida1 = new Drink("Coca-Cola", 0.5, 1.50, true, "2025-12-31", 10, true, 330);
$bebida2 = new Drink("Agua", 0.5, 1.00, true, "2024-06-30", 0, false, 500);
$comida1 = new Food("Hamburguesa", 0.4, 8.00, true, "2024-08-11", 10, ["Comida Rápida", "Salado"]);
$comida2 = new Food("Chocolate", 0.2, 2.00, true, "2025-02-21", 10, ["Dulce"]);
$noExpendable1 = new NoExpendable("Ordenador", 1.5, 1200.00, true, "2024-10-01");
$noExpendable2 = new NoExpendable("PS5", 5.0, 600.00, true, "2023-02-11");
$warehouse = new Warehouse("Almacen", "Calle Falsa 123", "Ciudad", 3, 4);
$warehouse->add($bebida1);
$warehouse->add($comida1);
$warehouse->add($comida2);
$warehouse->add($bebida2);
$warehouse->add($noExpendable1);
$warehouse->add($noExpendable2);
echo "Inventario desordenado: ";
echo "<br>";
echo "<br>";
$warehouse->printInventory();
echo "<br>";
echo "Inventario ordenado: ";
echo "<br>";
$warehouse->order();
echo "<br>";
$warehouse->printInventory();
echo "<br>";
$searchResult = $warehouse->search("Coca-Cola");
echo "<br>Resultado de búsqueda para 'Coca-Cola':<br>";
echo "Coincidencias: " . $searchResult["Coincidencias: "] . "<br>";
foreach ($searchResult["Items coincidentes: "] as $item) {
    echo $item->__toString();
}

echo "<br>";
$foodTypeSearch = $warehouse->searchByType("Dulce");
echo "<br>Búsqueda de alimentos por tipo 'Dulce':<br>";
echo "Nº Coincidencias: " . $foodTypeSearch["Nº Coincidencias"] . "<br>";
//Recorremos el array de Foods devuelto por la funcion searchByType para imprimir todos sus toString
foreach ($foodTypeSearch["Foods"] as $food) {
    echo $food->__toString();
}

echo "<br>";
echo "<br>";
echo "Precio promedio de los items: " . $warehouse->avgPriceItems() . " €";
echo "<br>";
echo "<br>";
$warehouse->totalBegudes();
echo "<br>";
echo "<br>";
echo "Se ha borrado un item ";
echo "<br>";
$warehouse->remove(0, 2);
echo "<br>";
$warehouse->add($comida1);
//$warehouse->removeBlanks();
$warehouse->printInventory();
echo "<br>";
echo "<br>";
echo "Se han eliminado los items caducados y que caducaran en los proximos 200 dias";
echo "<br>";
echo "<br>";
$warehouse->searchExpired(200);
$warehouse->printInventory();
echo "<br>";
echo "<br>";
echo "Precio total de los items: " . $warehouse->sumPriceItems() . "€";
echo "<br>";
echo "<br>";
echo "AHORA SE VA A VACIAR TODO EL ALMACEN";
echo "<br>";
echo "<br>";
$warehouse->removeBlanks();
$warehouse->cleanWarehouse();
echo "<br>";
echo "<br>";
$warehouse->printInventory();
echo "<br>";
echo "<br>";
echo "AHORA SE HAN VUELTO A AÑADIR";
$warehouse->add($bebida1);
$warehouse->add($comida1);
$warehouse->add($bebida2);
$warehouse->add($comida2);
$warehouse->add($noExpendable1);
$warehouse->add($noExpendable2);
echo "<br>";
echo "<br>";
$warehouse->printInventory();
echo "<br>";
echo "<br>";
echo "EXPENDABLES QUE CADUCAN ENTRE 2024-09-12 Y 2026-01-01";
echo "<br>";
echo "<br>";
$warehouse->fechasDadas('2024-09-12', '2026-01-01');
echo "<br>";
echo "<br>";
