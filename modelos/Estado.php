<?php 

	require "../config/conexion.php";
	require "../config/conexionSap.php";


	Class Estado{
		//Constructor para instancias
		public function __construct(){
		}

		/*public function serviciosa($iduser){
			$sql="SELECT COUNT(idservicio) AS servicios FROM servicio WHERE YEAR(created_time)=YEAR(NOW()) AND iduser='$iduser' AND estadofin IS NOT NULL";
			return ejecutarConsultaSimpleFila($sql);
		}

		public function serviciosmes($iduser){
			$sql="SELECT COUNT(idservicio) AS servicios FROM servicio WHERE YEAR(created_time)=YEAR(NOW()) AND MONTH(created_time) = MONTH(NOW()) AND iduser='$iduser' AND estadofin IS NOT NULL";
			return ejecutarConsultaSimpleFila($sql);
		}

		public function serviciosdia($iduser){
			$sql="SELECT COUNT(idservicio) AS servicios FROM servicio WHERE DATE(created_time)=DATE(NOW()) AND iduser='$iduser' AND  estadofin IS NOT NULL";
			return ejecutarConsultaSimpleFila($sql);
		}

		public function contarservicios($iduser){
			$sql="SELECT MONTH(created_time) AS mes, COUNT(idservicio) AS servicios FROM servicio WHERE YEAR(created_time)=YEAR(NOW()) AND iduser='$iduser' AND estadofin IS NOT NULL GROUP BY MONTH(created_time)";
			return ejecutarConsulta($sql);
		}*/
		public function TraerDatos(){
			$meses = array(
				"01"=>0,
				"02"=>0,
				"03"=>0,
				"04"=>0,
				"05"=>0,
				"06"=>0,
				"07"=>0,
				"08"=>0,
				"09"=>0,
				"10"=>0,
				"11"=>0,
				"12"=>0,
			);

			foreach ($meses as $key => $value) {
				$query = "Activities/\$count?\$filter=HandledByEmployee eq ".$_SESSION['idSAP']." and Closed eq 'Y' and U_PorFirmar eq 'N' and CloseDate ge '".date('Y')."-".$key."-01' and CloseDate le '".date('Y')."-".$key."-31'";
				$meses[$key] = Query($query);
				if(date('m') == $key){
					$mes = $meses[$key];
				}
			}
			$data['anio'] = array_sum($meses);
			$data['mes'] = $mes;
			$query = "Activities/\$count?\$filter=HandledByEmployee eq ".$_SESSION['idSAP']." and Closed eq 'Y' and U_PorFirmar eq 'N' and CloseDate ge '".date('Y')."-".str_pad(date('m'), 2, '0', STR_PAD_LEFT)."-".date('d')."' and CloseDate le '".date('Y')."-".str_pad(date('m'), 2, '0', STR_PAD_LEFT)."-".date('d')."'";
			$data['dia'] = Query($query);

			return json_encode(array("datos"=>$data,"grafico"=>$meses));
		}

		public function TraerDatosAndroid($idSAP){
			$meses = array(
				"01"=>0,
				"02"=>0,
				"03"=>0,
				"04"=>0,
				"05"=>0,
				"06"=>0,
				"07"=>0,
				"08"=>0,
				"09"=>0,
				"10"=>0,
				"11"=>0,
				"12"=>0,
			);

			foreach ($meses as $key => $value) {
				$query = "Activities/\$count?\$filter=HandledByEmployee eq ".$idSAP." and Closed eq 'Y' and U_PorFirmar eq 'N' and CloseDate ge '".date('Y')."-".$key."-01' and CloseDate le '".date('Y')."-".$key."-31'";
				$meses[$key] = Query($query);
				if(date('m') == $key){
					$mes = $meses[$key];
				}
			}
			$data['anio'] = array_sum($meses);
			$data['mes'] = $mes;
			$query = "Activities/\$count?\$filter=HandledByEmployee eq ".$idSAP." and Closed eq 'Y' and U_PorFirmar eq 'N' and CloseDate ge '".date('Y')."-".str_pad(date('m'), 2, '0', STR_PAD_LEFT)."-".date('d')."' and CloseDate le '".date('Y')."-".str_pad(date('m'), 2, '0', STR_PAD_LEFT)."-".date('d')."'";
			$data['dia'] = Query($query);

			return json_encode(array("datos"=>$data,"grafico"=>$meses));
		}


	
	}
?>