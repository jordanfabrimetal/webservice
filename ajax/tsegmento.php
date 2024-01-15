<?php 

require_once "../modelos/Tsegmento.php";

$tsegmento = new Tsegmento();


switch ($_GET["op"]) {

		case 'selecttsegmento':
			$rspta = $tsegmento->selecttsegmento();
			while($reg = $rspta->fetch_object()){
				echo '<option value='.$reg->idtsegmento.'>'.$reg->nombre.'</option>';
			}
			break;
}

 ?>