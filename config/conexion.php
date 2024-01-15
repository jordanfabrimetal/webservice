<?php 

require_once "global.php";


if(mysqli_connect_errno()){
	printf("Fallo la conexion con la BD: %s \n", mysqli_connect_error());
	exit();
}

if(!function_exists('ejecutarConsulta')){
	function ejecutarConsulta($sql){
		
		$conexion = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        mysqli_query($conexion, 'SET NAMES "'.DB_ENCODE.'"');
        mysqli_query($conexion, 'SET lc_time_names="'.DB_NAMES.'"');
		$query = $conexion->query($sql);

		if (!$query) {
            die("Error en la consulta: " . $conexion->error);
        }

		mysqli_close($conexion);
		return $query;
	}


	function ejecutarConsultaSimpleFila($sql){
		
		$conexion = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        mysqli_query($conexion, 'SET NAMES "'.DB_ENCODE.'"');
        mysqli_query($conexion, 'SET lc_time_names="'.DB_NAMES.'"');
		$query = $conexion->query($sql);
		$row = $query->fetch_assoc();
		mysqli_close($conexion);
		return $row;
	}

	function ejecutarConsulta_retornarID($sql){
		
		$conexion = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        mysqli_query($conexion, 'SET NAMES "'.DB_ENCODE.'"');
        mysqli_query($conexion, 'SET lc_time_names="'.DB_NAMES.'"');
		$query = $conexion->query($sql);
		$id = $conexion->insert_id;
		mysqli_close($conexion);
		return $id;
	}

	function ejecutarConsu_retornarID($sql){
		
		$conexion = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        mysqli_query($conexion, 'SET NAMES "'.DB_ENCODE.'"');
        mysqli_query($conexion, 'SET lc_time_names="'.DB_NAMES.'"');
		$query = $conexion->query($sql);
		if($query){
		    $id = $conexion->insert_id;
		    mysqli_close($conexion);
			return $id;
		}else{
		    mysqli_close($conexion);
			return false;
		}
	}
        
        function NumeroFilas($sql){
		
		$conexion = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        mysqli_query($conexion, 'SET NAMES "'.DB_ENCODE.'"');
        mysqli_query($conexion, 'SET lc_time_names="'.DB_NAMES.'"');
		$query = $conexion->query($sql);
		$rows = $conexion->affected_rows; 
		mysqli_close($conexion);
        return $rows ;
	}
        
        

	function limpiarCadena($str){
		
		$conexion = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        mysqli_query($conexion, 'SET NAMES "'.DB_ENCODE.'"');
        mysqli_query($conexion, 'SET lc_time_names="'.DB_NAMES.'"');
		$str = mysqli_real_escape_string($conexion, trim($str));
		mysqli_close($conexion);
		return htmlspecialchars($str);
	}       

}


?>