<?php 

require_once "../config/conexion.php";
require_once "../config/conexionSap.php";

	Class Usuario{
		//Constructor para instancias
		public function __construct(){

		}

		public function insertar($idrole,$username,$password,$nombre,$apellido,$tipo_documento,$num_documento,$fecha_nac,$direccion,$telefono,$email,$imagen){
			$sql="INSERT INTO user (idrole, username, password, nombre, apellido, tipo_documento, num_documento, fecha_nac, direccion, telefono, email, imagen, publico, condicion) VALUES ('$idrole','$username','$password','$nombre', '$apellido', '$tipo_documento', '$num_documento', '$fecha_nac','$direccion', '$telefono','$email','$imagen',1,1)";
			return ejecutarConsulta($sql);
		}

		public function editar($iduser,$idrole,$username,$password,$nombre,$apellido,$tipo_documento,$num_documento,$fecha_nac,$direccion,$telefono,$email,$imagen){
			$sql="UPDATE user SET idrole= '$idrole', username='$username', password='$password',nombre='$nombre', apellido='$apellido', tipo_documento='$tipo_documento', num_documento='$num_documento', direccion='$direccion', telefono='$telefono', email='$email', fecha_nac='$fecha_nac', imagen='$imagen' , updated_time=CURRENT_TIMESTAMP WHERE iduser='$iduser'";
			return ejecutarConsulta($sql);
		}

		public function desactivar($iduser){
			$sql="UPDATE user SET condicion='0' WHERE iduser='$iduser'";
			return ejecutarConsulta($sql);
		}

		public function activar($iduser){
			$sql="UPDATE user SET condicion='1' WHERE iduser='$iduser'";
			return ejecutarConsulta($sql);
		}

		public function mostrar($iduser){
			$sql="SELECT * FROM user WHERE iduser='$iduser'";
			return ejecutarConsultaSimpleFila($sql);
		}

		public function listar(){
			$sql="SELECT u.iduser, u.idrole, r.nombre as role, u.username, u.nombre, u.apellido, u.email, u.imagen, u.condicion FROM user u INNER JOIN role r ON u.idrole = r.idrole";
			return ejecutarConsulta($sql);
		}

		public function verificar($username, $password){
			LoginSAP();
			$sql="SELECT iduser, idrole, nombre, apellido, tipo_documento, num_documento, telefono, email, imagen, username, num_documento, firma, filefir FROM user WHERE username='$username' AND password='$password' AND condicion='1'";
			return ejecutarConsulta($sql);
		}
                
                public function Rut($iduser){
			$sql="SELECT num_documento FROM user WHERE iduser='$iduser'";
			return ejecutarConsultaSimpleFila($sql);
		}

	}
?>