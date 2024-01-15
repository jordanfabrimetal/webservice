<?php 
session_name('SESS_GSAP');
session_start();
require_once "../modelos/Usuario.php";
require_once "../modelos/Role.php";


$user = new Usuario();

$iduser=isset($_POST["iduser"])?limpiarCadena($_POST["iduser"]):"";
$idrole=isset($_POST["idrole"])?limpiarCadena($_POST["idrole"]):"";
$username=isset($_POST["username"])?limpiarCadena($_POST["username"]):"";
$password=isset($_POST["password"])?limpiarCadena($_POST["password"]):"";
$nombre=isset($_POST["nombre"])?limpiarCadena($_POST["nombre"]):"";
$apellido=isset($_POST["apellido"])?limpiarCadena($_POST["apellido"]):"";
$tipo_documento=isset($_POST["tipo_documento"])?limpiarCadena($_POST["tipo_documento"]):"";
$num_documento=isset($_POST["num_documento"])?limpiarCadena($_POST["num_documento"]):"";
$fecha_nac=isset($_POST["fecha_nac"])?limpiarCadena($_POST["fecha_nac"]):"";
$direccion=isset($_POST["direccion"])?limpiarCadena($_POST["direccion"]):"";
$telefono=isset($_POST["telefono"])?limpiarCadena($_POST["telefono"]):"";
$email=isset($_POST["email"])?limpiarCadena($_POST["email"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
		if(!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name'])){
			$imagen=$_POST["imagenactual"];	
		}else{
			$ext = explode(".",$_FILES['imagen']['name']);
			if($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png" ){
				$imagen = round(microtime(true)).".".end($ext);
				move_uploaded_file($_FILES['imagen']['tmp_name'], "../files/usuarios/".$imagen);
			}
		}

		$clavehash = hash("SHA256", $password);

		if(empty($iduser)){
			$rspta=$user->insertar($idrole,$username,$clavehash,$nombre,$apellido,$tipo_documento,$num_documento,$fecha_nac,$direccion,$telefono,$email,$imagen);
			echo $rspta ? "Usuario registrado" : $username."Usuario no pudo ser registrado";
		}
		else{
			$rspta=$user->editar($iduser,$idrole,$username,$clavehash,$nombre,$apellido,$tipo_documento,$num_documento,$fecha_nac,$direccion,$telefono,$email,$imagen);
			echo $rspta ? "Usuario editado" : "Usuario no pudo ser editado";
		}
		break;

	case 'desactivar':
		$rspta=$user->desactivar($iduser);
			echo $rspta ? "Usuario inhabilitado" : "Usuario no se pudo inhabilitar";
		break;

	case 'activar':
		$rspta=$user->activar($iduser);
			echo $rspta ? "Usuario habilitado" : "Usuario no se pudo habilitar";
		break;

	case 'mostar':
		if($_POST["iduser"]){
			$iduser=$_POST["iduser"];
			error_log("tramos en mostrar");	
		}
		error_log("no tramos en mostrar");
		$rspta=$user->mostrar($iduser);
			echo json_encode($rspta);
		break;
			
	case 'listar':
		$rspta=$user->listar();
		$data = Array();
		while ($reg = $rspta->fetch_object()){
			$data[] = array(
					"0"=>($reg->condicion)?
					'<button class="btn btn-warning btn-xs" onclick="mostar('.$reg->iduser.')"><i class="fa fa-pencil"></i></button>'.
					' <button class="btn btn-danger btn-xs" onclick="desactivar('.$reg->iduser.')"><i class="fa fa-close"></i></button>':
					'<button class="btn btn-warning btn-xs" onclick="mostar('.$reg->iduser.')"><i class="fa fa-pencil"></i></button>'.
					' <button class="btn btn-primary btn-xs" onclick="activar('.$reg->iduser.')"><i class="fa fa-check"></i></button>',
					"1"=>$reg->nombre.''.$reg->apellido,
					"2"=>$reg->username,
					"3"=>$reg->email,
					"4"=>$reg->role,
					"5"=>($reg->condicion)?'<span class="label bg-green">Habilitado</span>':'<span class="label bg-red">Inhabilitado</span>'
				);
		}
		$results = array(
				"sEcho"=>1,
				"iTotalRecords"=>count($data),
				"iTotalDisplayRecords"=>count($data), 
				"aaData"=>$data
			);

		echo json_encode($results);
		break;

		case 'selectRole':
			require_once "../modelos/Role.php";
			$role = new Role();
			$rspta = $role->select();
			while($reg = $rspta->fetch_object()){
				echo '<option value='.$reg->idrole.'>'.$reg->nombre.'</option>';
			}
			break;

		case 'verificar':
			//require_once "../modelos/Usuario.php";
			$usuario= new Usuario(); 

			$username_form = $_POST['username_form'];
			$password_form = $_POST['password_form'];

			$password_hash = hash("SHA256", $password_form);
			
			$rspta=$usuario->verificar($username_form, $password_hash);
           
			$fecth = $rspta->fetch_object();

			if(isset($fecth)){
				$id_sesion_antigua = session_id();
				session_regenerate_id();
				$id_sesion_nueva = session_id();
				
				$_SESSION['iduser']=$fecth->iduser;
				$_SESSION['nombre']=$fecth->nombre;
				$_SESSION['apellido']=$fecth->apellido;
				$_SESSION['imagen']=$fecth->imagen;
				$_SESSION['username']=$fecth->username;
				$_SESSION['idrole']=$fecth->idrole;

				$iduser = $_SESSION['iduser'];
				$nombre = $_SESSION['nombre'];
				$apellido = $_SESSION['apellido'];
				$imagen = $_SESSION['imagen'];
				$username = $_SESSION['username'];
				$idrole = $_SESSION['idrole'];
				

				$role= new Role();
				$permisos = $role->listarmarcados($fecth->idrole);

				$valores=array();

				while ($per = $permisos->fetch_object()){
					array_push($valores, $per->idpermiso);
				}
				
				if(empty($fecth->firma) || empty($fecth->filefir)){
					$fecth->status = 'sinfirma';
				}else{
					$fecth->status = 'confirma';
				}

				in_array(1, $valores)? $_SESSION['administrador']=1:$_SESSION['administrador']=0;
				in_array(2, $valores)? $_SESSION['mantencion']=1:$_SESSION['mantencion']=0;
                in_array(3, $valores)? $_SESSION['Icontratos']=1:$_SESSION['Icontratos']=0;
                in_array(4, $valores)? $_SESSION['Mcontratos']=1:$_SESSION['Mcontratos']=0;
                in_array(5, $valores)? $_SESSION['Vcontratos']=1:$_SESSION['Vcontratos']=0;
                in_array(6, $valores)? $_SESSION['Lcontratos']=1:$_SESSION['Lcontratos']=0;
                in_array(7, $valores)? $_SESSION['Contratos']=1:$_SESSION['Contratos']=0;
                in_array(8, $valores)? $_SESSION['Guia']=1:$_SESSION['Guia']=0;
                in_array(9, $valores)? $_SESSION['FC3']=1:$_SESSION['FC3']=0;
                                
				
                /* inicio obtener userID SAP */
				$entity = 'EmployeesInfo';
				$select = 'EmployeeID,eMail,Position';
				$filter = "IdNumber eq '".strtoupper(str_replace('.', '', $fecth->num_documento))."'";
				$rspta = json_decode(ConsultaEntity($entity,$select,$filter), true);
				foreach ($rspta['value'] as $val) {
					$_SESSION['idSAP']=$val['EmployeeID'];
					$idSAP =$val['EmployeeID'];
					error_log("El ID DE SAP ES: ".$_SESSION['idSAP']);
					$_SESSION['email']=$val['eMail'];
					$email = $_SESSION['email'];
					$_SESSION['subcontrato']=(($val['Position'] == 20) ? 1 : 0);
				}
				/* fin obtener userID SAP */
				error_log("El ID del usuario: ".$iduser);
				error_log("El ID del rol es: ".$idrole);

				$response = array(
					'success' => true,
					'idSAP' => $idSAP,
					'nombre' => $nombre,
					'apellido' => $apellido,
					'email' => $email, 
					'iduser' => $iduser,
					'idrole' => $idrole,
					'message' => 'Inicio de sesión exitoso',
					'user_data' => $fecth // o cualquier otro dato que desees enviar
				);
			}else {
				$response = array(
					'success' => false,
					'message' => 'Usuario o contraseña incorrecta'
				);
			}
			echo json_encode($response); 			
			break;

			case 'salir':
			LogoutSAP();
			session_unset();
			session_destroy();
			header("Location: ../index.php");
			break;
                    
                        case 'salirguia':
			session_unset();
			session_destroy();
			header("Location: ../production/guia/index.php");
			break;
			
			case 'verificasesion':
    			if(!isset($_SESSION["idSAP"])  || empty($_SESSION["idSAP"])){
    				echo 'login';
    			}else{
    				echo 'continue';
    			}
    		break;
}

 ?>