<?php 

require_once "../modelos/Tascensor.php";

$tascensor = new Tascensor();

switch ($_GET["op"]) {

		case 'selecttascensor':
			$rspta = $tascensor->selecttascensor();
			while($reg = $rspta->fetch_object()){
				echo '<option value='.$reg->idtascensor.'>'.$reg->nombre.'</option>';
			}
			break;
}

 ?>