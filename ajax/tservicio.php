<?php 

	require_once "../modelos/Tservicio.php";

	$tservicio = new Tservicio();


	switch ($_GET["op"]) {

		case 'selecttservicio':
			$rspta = $tservicio->selecttservicio();
			echo '<option value="" selected disabled>SELECCIONE EL SERVICIO</option>';
			while($reg = $rspta->fetch_object()){
				echo '<option value='.$reg->idtservicio.'>'.$reg->nombre.'</option>';
			}
		break;

		case 'selecttserviciosap':
			$rspta=$tservicio->selecttserviciosap();
	        $rspta = json_decode($rspta, true);
	        echo '<option value="" selected disabled>SELECCIONE SERVICIO</option>';
	        foreach ($rspta as $val=>$value) {
	            switch ($val) {
	                case 'value':
	                    foreach ($value as $data) {
	                        echo '<option value='.$data['CallTypeID'].'>'.$data['Name'].'</option>';
	                        //$data['InternalSerialNum']
	                    }
	                break;
	            }
	        }
			/*$rspta = $tservicio->selecttservicio();
			echo '<option value="" selected disabled>SELECCIONE EL SERVICIO</option>';
			while($reg = $rspta->fetch_object()){
				echo '<option value='.$reg->idtservicio.'>'.$reg->nombre.'</option>';
			}*/
		break;
}

?>