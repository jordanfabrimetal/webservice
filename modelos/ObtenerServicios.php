<?php

require "Estado.php";

$estado = new Estado();

$resultado = $estado->TraerDatos();

echo $resultado;

?>