<?php 

require_once "../modelos/Modelo.php";

$modelo = new Modelo();


switch ($_GET["op"]) {

		case 'selectmodelo':
			$idmarca=$_GET["id"];
			$rspta = $modelo->selectmodelo($idmarca);
			while($reg = $rspta->fetch_object()){
				echo '<option value='.$reg->idmodelo.'>'.$reg->nombre.'</option>';
			}
		break;
}

 ?>