<?php 

require_once "../modelos/Marca.php";

$marca = new Marca();

switch ($_GET["op"]) {

		case 'selectmarca':
			$rspta = $marca->selectmarca();
			while($reg = $rspta->fetch_object()){
				echo '<option value='.$reg->idmarca.'>'.$reg->nombre.'</option>';
			}
			break;
}

 ?>