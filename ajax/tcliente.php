<?php 

require_once "../modelos/Tcliente.php";

$tcliente = new Tcliente();


switch ($_GET["op"]) {

		case 'selecttcliente':
			$rspta = $tcliente->selecttcliente();
			while($reg = $rspta->fetch_object()){
				echo '<option value='.$reg->idtcliente.'>'.$reg->nombre.'</option>';
			}
			break;
}

 ?>