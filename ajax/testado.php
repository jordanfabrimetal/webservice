<?php 

require_once "../modelos/Testado.php";

$testado = new Testado();


switch ($_GET["op"]) {

		case 'selecttestado':
			$rspta = $testado->selecttestado();
                        echo '<option value="" selected disabled>SELECCIONE ESTADO DEL EQUIPO</option>';
			while($reg = $rspta->fetch_object()){
				echo '<option value='.$reg->id.'>'.$reg->nombre.'</option>';
			}
		break;
                
                case 'selecttestadofi':
			$rspta = $testado->selecttestadofi();
                        echo '<option value="" selected disabled>SELECCIONE ESTADO DEL EQUIPO</option>';
			while($reg = $rspta->fetch_object()){
				echo '<option value='.$reg->id.'>'.$reg->nombre.'</option>';
			}
		break;
}

 ?>