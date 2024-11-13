<?php

include_once 'InterficieNaming.php';

class Warehouse implements Naming
{
    // Creamos atributos para warehouse
    private $name;
    private $address;
    private $city;
    private array $slots;
    private $maxX;
    private $maxY;

    public function getName()
    {
        return $this->name;
    }
    public function setName($n)
    {
        $this->name = $n;
    }
    public function __toString()
    {
        // Convertir slots en cadena
        $slotsString = implode(", ", $this->slots);

        return "Nombre: $this->name\n" .
               "Dirección: $this->address\n" .
               "Ciudad: $this->city\n" .
               "Slots: $slotsString\n" .
               "MaxX: $this->maxX\n" .
               "MaxY: $this->maxY\n";
    }

    public function __construct($name, $address, $city, $maxX, $maxY)
    {
        $this->name = $name;
        $this->address = $address;
        $this->city = $city;
        $this->maxX = $maxX;
        $this->maxY = $maxY;
        /* Inicializamos slots como un array 2D,
         maxX será el numero de filas del array y maxY será el numero de columnas.
        Todas las posiciones serán null.*/
        $this->slots = array_fill(0, $maxX, array_fill(0, $maxY, null));
    }

    public function add($item)
    {
        // Doble bucle para recorrerse todo el array 2d
        for ($x = 0; $x < $this->maxX; $x++) {
            for ($y = 0; $y < $this->maxY; $y++) {
                if ($this->slots[$x][$y] === null) { // Encuentra el primer slot vacío (null) y agrega un item
                    $this->slots[$x][$y] = $item;
                    return;
                }
            }
        }
        echo "No hay espacio en el almacén para agregar el item.";
    }



    public function remove($x, $y)
    {
        if (isset($this->slots[$x][$y])) { // Verifica si existe el elemento
            $this->slots[$x][$y] = null; // Si existe lo pone en null
        } else {
            echo "No hay elementos en esta posición.";
        }
    }


    public function order()
    {
        for ($x = 0; $x < $this->maxX; $x++) { //Recorre el array en filas hasta maxX
            if (!empty($this->slots[$x])) { //Verificamos que hay elementos para ordenar
                /*
                Definimos una funcion lambda dentro de array_filter que devolverá un true
                (no se elimina) o un false (se elimina) si es null.
                */
                $this->slots[$x] = array_filter($this->slots[$x], function ($elemento) {
                    return $elemento !== null;
                });

                /*El segundo parametro de usort es una funcion lambda
                 que nos ayudará a ordenar los items por classe.
                Usamos "get_class" para obtener el nombre de la classe de cada item y las comparamos
                alfabeticamente usando "strcmp", lo que devolverá un numero < 0, > 0 o 0
                 y segun ese numero "usort" ordenará los items*/
                usort($this->slots[$x], function ($a, $b) {
                    return strcmp(get_class($a), get_class($b));
                });
            }
        }
    }


    public function removeBlanks()
    {
   //Recorremos las filas del array
        foreach ($this->slots as $x => $fila) {
            /* Definimos una funcion lambda dentro de array_filter que devolverá un true (no se elimina)
             o un false (se elimina) si es null.
            Usaremos array_values para reindexar los items
            */
            $this->slots[$x] = array_values(array_filter($fila, function ($elemento) {
                return $elemento !== null;
            }));
        }
    }


    public function search($nomItem)
    {
        $count = 0;
        $array = [];
        //Recorremos el array por filas y columnas con maxX y maxY como maximo
        for ($x = 0; $x < $this->maxX; $x++) {
            for ($y = 0; $y < $this->maxY; $y++) {
                /*
                Si los elementos existen y el nombre coincide con el nombre del parametro
                se suma un elemento al contador y al array
                */
                if (isset($this->slots[$x][$y]) && $this->slots[$x][$y]->getName() == $nomItem) {
                    $count++;
                    $array[] = $this->slots[$x][$y];
                }
            }
        }
        return ["Coincidencias: " => $count, "Items coincidentes: " => $array];
    }

    public function searchByType(...$foodType)//Usamos rest porque puede ser un valor o varios como parametro
    {

        $matchingFoods = [];
        $count = 0;

        for ($x = 0; $x < $this->maxX; $x++) {
            for ($y = 0; $y < $this->maxY; $y++) {
                /*
                Si el elemento existe y es de la classe Food, array_intersect compara un array
                con una cantidad indeterminada de valores

                */
                if (isset($this->slots[$x][$y]) && $this->slots[$x][$y] instanceof Food) {
                    if (array_intersect($this->slots[$x][$y]->getType(), $foodType)) {
                        $matchingFoods[] = $this->slots[$x][$y]; // Agregamos este slot al array de coincidencias
                        $count++; // Aumentamos el contador
                    }
                }
            }
        }
        return ["Nº Coincidencias" => $count,"Foods" => $matchingFoods];
    }

    public function cleanWarehouse()
    {
        $contador = 0; //Declaramos contador para tener controlado el numero de items eliminados
        foreach ($this->slots as $x => $fila) {
            foreach ($fila as $y => $columna) {
                //Si el valor no es nulo, es decir, hay algun item, pasará el valor a null usando remove
                if ($this->slots[$x][$y] !== null) {
                    $this->remove($x, $y);
                    $contador++;
                }
            }
        }

        $this->slots = array_fill(0, $this->maxX, array_fill(0, $this->maxY, null));
        /*
        Con esta linea este metodo no solo pone todos los slots a null si no que los vuelve a indexar con el
        tamaño definido en el constructor para evitar WARNINGs
        */

        echo "$contador objetos eliminados.";
    }


    public function avgPriceItems()
    {
        $precioTotal = 0;
        $contador = 0;

        for ($x = 0; $x < $this->maxX; $x++) {
            for ($y = 0; $y < $this->maxY; $y++) {
                if (isset($this->slots[$x][$y])) { // Verificamos si hay un elemento en esta posición
                    //Agregamos el valor del item con la taxa incluida a la variable $precioTotal
                    $precioTotal += $this->slots[$x][$y]->calcPriceWithTax();
                    $contador++;
                }
            }
        }
        // Para evitar dividir entre cero
        if ($contador == 0) {
            return 0;
        }

        $avgPreu = $precioTotal / $contador;

        return $avgPreu;
    }


    public function searchExpired($numDias = null)//El parametro se declara como null a no ser que se introduzca uno
    {
        $fechaActual = date('Y-m-d');//Declaramos una variable con la fecha actual como referencia

        if ($numDias !== null) { //Si se ha introducido un parametro...
            //Declaramos una variable con el valor de la fecha actual + los dias agregados
            $fechaLimite = date('Y-m-d', strtotime($fechaActual . " + $numDias days"));
            for ($x = 0; $x < $this->maxX; $x++) {
                for ($y = 0; $y < $this->maxY; $y++) {
                    /*
                    Creamos la variable columna asignandole el item de la posicion.
                    Si esa posicion no está definida, se rellenará como un null para evitar errores

                    */
                    $columna = $this->slots[$x][$y] ?? null;
                    //Comprobamos que el item sea de la classe Expendable
                    if ($columna instanceof Expendable) {
                        //Comprobamos que expire antes o igual que la fecha limite
                        if ($columna->getExpireDate() <= $fechaLimite) {
                            $this->remove($x, $y); //Se marcará como null
                        }
                    }
                }
            }
        } else { //Hacemos lo mismo pero sin el parametro dado
            for ($x = 0; $x < $this->maxX; $x++) {
                for ($y = 0; $y < $this->maxY; $y++) {
                    $columna = $this->slots[$x][$y] ?? null;

                    if ($columna instanceof Expendable) {
                        $this->remove($x, $y);
                    }
                }
            }
        }
    }

    public function totalBegudes()
    {
        $totalAlc = 0;
        $totalSinAlc = 0;
        $totalLitros = 0;

        foreach ($this->slots as $fila) {
            foreach ($fila as $slot) {
                //Si el elemento existe y es de la classe Drink...
                if (isset($slot) && $slot instanceof Drink) {
                    $totalLitros += $slot->toLiters();

                    if ($slot->getisAlcoholic()) {
                        $totalAlc += $slot->toLiters();
                    } else {
                        $totalSinAlc += $slot->toLiters();
                    }
                }
            }
        }

        if ($totalLitros > 0) {
            $percsinAlc = round(($totalSinAlc / $totalLitros) * 100);
            $percAlc = round(($totalAlc / $totalLitros) * 100);
            echo "Total Litros: $totalLitros L";
            echo "<br>";
            echo "$percsinAlc%  sin alcohol";
            echo "<br>";
            echo "$percAlc% con alcohol";
        } else {
            echo "No hay bebidas para calcular los porcentajes.";
        }
    }
    public function sumPriceItems()
    {
        $total = 0;

        foreach ($this->slots as $fila) {
            foreach ($fila as $item) {
                if ($item !== null) {
                    $total += $item->calcPriceWithTax();
                }
            }
        }

        return $total;
    }
    public function printInventory()
    {
        echo "INVENTARIO";
        echo "<br>";

        $hayItems = false;

        for ($x = 0; $x < $this->maxX; $x++) {
            for ($y = 0; $y < $this->maxY; $y++) {
                //Si el elemento existe...
                if (isset($this->slots[$x][$y])) {
                    echo "Posición ($x, $y): " . $this->slots[$x][$y]->__toString();
                    echo "<br>";
                    $hayItems = true;
                }
            }
        }

        if (!$hayItems) {
            echo "No hay Items en el almacén";
        }
    }

    public function fechasDadas($fechaInicio, $fechaFinal)
    {
        //Declaramos variables strings que son fechas como valor numerico para poder compararlas sin problemas
        $inicio = strtotime($fechaInicio);
        $final = strtotime($fechaFinal);

        if (empty($this->slots)) {
            echo "No hay items en el almacen";
        }

        for ($x = 0; $x < $this->maxX; $x++) {
            for ($y = 0; $y < $this->maxY; $y++) {
                /*
                Creamos la variable item asignandole el item de la posicion.
                Si esa posicion no está definida, se rellenará como un null para evitar errores
                */
                $item = $this->slots[$x][$y] ?? null;
                //Si el item es de la classe Expensable
                if ($item instanceof Expendable) {
                    /*
                    Comparamos las fechas del item con las fechas marcadas
                    (todo en valor numerico para una mejor comparacion)
                    */
                    if (strtotime($item->getExpireDate()) >= $inicio && strtotime($item->getExpireDate()) <= $final) {
                        echo "Posicion: ($x,$y)";
                        echo " Nombre: " . $item->getName() . "<br>";
                    }
                }
            }
        }
    }
}
