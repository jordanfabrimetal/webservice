<?php 

require_once "../modelos/Provincia.php";

$provincia = new Provincia();


switch ($_GET["op"]) {

		case 'selectProvincia':
			$idregiones=$_GET["id"];
			$rspta = $provincia->selectProvincia($idregiones);
			while($reg = $rspta->fetch_object()){
				echo '<option value='.$reg->provincia_id.'>'.$reg->provincia_nombre.'</option>';
			}
		break;
}

 ?>