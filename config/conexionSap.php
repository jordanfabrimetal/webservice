<?php 

	require_once "global.php";

	// Credenciales.
	$params = [
		"UserName" => DB_USERNAME_SAP,
		"Password" => DB_PASSWORD_SAP,
		"CompanyDB" => DB_NAME_SAP,
	];

	/*   Función para Login en SAP B1 SL   */
	if(!function_exists('LoginSAP')){
		function LoginSAP(){
			// if (!isset($_COOKIE[B1SESSION]) && !isset($_COOKIE[ROUTEID])){
				global $params;
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, LOGIN_SAP);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl, CURLOPT_VERBOSE, 1);
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
				curl_setopt($curl, CURLOPT_HEADERFUNCTION, function($curl, $string) use (&$routeId){
					$len = strlen($string);
					if(substr($string, 0, 10) == "Set-Cookie"){
						preg_match("/ROUTEID=(.+);/", $string, $match);
						if(count($match) == 2){
							$routeId = $match[1];
						}
					}
					return $len;
				});
				$response = curl_exec($curl);

				$expire = time() + 30*60; //30 minutos
				setcookie(ROUTEID, $routeId, $expire, '/');
				// setcookie(ROUTEID, $routeId);
				$_COOKIE[ROUTEID] = $routeId; //script para poder tener la variable cookie de inmediato
				foreach(json_decode($response) as $data => $value){
					if($data == 'SessionId'){
						setcookie(B1SESSION, $value, $expire, '/');
						// setcookie(B1SESSION,$value);
						$_COOKIE[B1SESSION] = $value; //script para poder tener la variable cookie de inmediato
					}
				}

				curl_close($curl);
				return $response;
			// }
		}

		/*   Función para LogOut en SAP B1 SL   */
		function LogoutSAP(){
				//al logout debe agregarse el mismo header que se genero en el Login, sino falla el Logout
				$headers = array( 'Prefer: odata.maxpagesize=0', 'Cookie: B1SESSION='.@$_COOKIE[B1SESSION].'; ROUTEID='.@$_COOKIE[ROUTEID]);
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($curl, CURLOPT_URL, LOGOUT_SAP);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
				$response = curl_exec($curl);

				curl_close($curl);

				unset($_COOKIE[B1SESSION]);
				setcookie(B1SESSION, '', time() - 3600, '/');

				unset($_COOKIE[ROUTEID]);
				setcookie(ROUTEID, '', time() - 3600, '');

				return $response;
		}


		/*   Función para Consultar Entity con opción de seleccionar campos en SAP B1 SL   */
		function ConsultaEntity($entity,$select = null, $filter = null){
			logincaducado();
			$url = SERVER_SAP.$entity;
			if(!empty($select)){
				$url =$url.'?$select='.$select;
			}
			if(!empty($filter)){
				$url =$url.'&$filter='.str_replace(" ", "%20", $filter);
			}
			$url=$url.'&$inlinecount=allpages';
			$url = str_replace(' ', '%20', $url);
			$curl = curl_init();
			$headers = array( 'Prefer: odata.maxpagesize=0', 'Cookie: B1SESSION='.$_COOKIE[B1SESSION].'; ROUTEID='.$_COOKIE[ROUTEID]);
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_VERBOSE, 1);
			curl_setopt($curl, CURLOPT_HTTPGET , true);
			$response = curl_exec($curl);
			curl_close($curl);

			return $response;
		}

		/*   Función para Consultar un elemento en especifico con opción de seleccionar campos en SAP B1 SL   */
		function ConsultaIDNum($entity,$id,$select = null, $filter = null){
			logincaducado();
			$url = SERVER_SAP.$entity."(".str_replace(" ", "%20", $id).")";
			if(!empty($select)){
				$url =$url.'?$select='.$select;
			}
			if(!empty($filter)){
				$url =$url.'&$filter='.str_replace(" ", "%20", $filter);
			}
			$url = str_replace(' ', '%20', $url);
			$curl = curl_init();
			$headers = array( 'Prefer: odata.maxpagesize=0', 'Cookie: B1SESSION='.$_COOKIE[B1SESSION].'; ROUTEID='.$_COOKIE[ROUTEID]);
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_VERBOSE, 1);
			curl_setopt($curl, CURLOPT_HTTPGET , true);
			$response = curl_exec($curl);
			curl_close($curl);

			return $response;
		}

		/*   Función para Consultar un elemento en especifico con opción de seleccionar campos en SAP B1 SL   */
		function ConsultaIDLet($entity,$id,$select = null){
			logincaducado();
			$url = SERVER_SAP.$entity."('".str_replace(" ", "%20", $id)."')";
			if(!empty($select)){
				$url =$url.'?$select='.$select;
			}
			$url = str_replace(' ', '%20', $url);
			$curl = curl_init();
			$headers = array( 'Prefer: odata.maxpagesize=0', 'Cookie: B1SESSION='.$_COOKIE[B1SESSION].'; ROUTEID='.$_COOKIE[ROUTEID]);
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_VERBOSE, 1);
			curl_setopt($curl, CURLOPT_HTTPGET , true);
			$response = curl_exec($curl);
			curl_close($curl);

			return $response;
		}

		/*   Función para Insertar datos en un entity en SAP B1 SL   */
		function InsertarDatos($entity,$data){
			logincaducado();
			$url = SERVER_SAP.$entity;
			$url = str_replace(' ', '%20', $url);
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			$headers = array( 'Cookie: B1SESSION='.$_COOKIE[B1SESSION].'; ROUTEID='.$_COOKIE[ROUTEID]);
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_VERBOSE, 1);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			$response = curl_exec($curl);
			curl_close($curl);

			return $response;
		}

		/*   Función para Editar un elemento en espeifico en SAP B1 SL   */
		function EditardatosNum($entity,$id,$data){
			logincaducado();
			$url = SERVER_SAP.$entity."(".str_replace(' ','',$id).")";
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			$headers = array( 'Cookie: B1SESSION='.$_COOKIE[B1SESSION].'; ROUTEID='.$_COOKIE[ROUTEID]);
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_VERBOSE, 1);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			$response = curl_exec($curl);
			curl_close($curl);

			return $response;
		}

		function Editardatos($entity,$id,$data,$estexto = true){
			logincaducado();
			if ($estexto)
				$url = SERVER_SAP.$entity."('".str_replace(' ','%20',$id)."')";
			else
				$url = SERVER_SAP.$entity."(".str_replace(' ','%20',$id).")";

			$url = str_replace(' ', '%20', $url);
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			$headers = array( 'Cookie: B1SESSION='.$_COOKIE[B1SESSION].'; ROUTEID='.$_COOKIE[ROUTEID]);
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_VERBOSE, 1);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			$response = curl_exec($curl);
			curl_close($curl);

			return $response;
		}

		/*   Función para Eliminar un elemento en espeifico en SAP B1 SL   */
		function Eliminardatos($entity,$id){
			logincaducado();
			$url = SERVER_SAP.$entity."('".str_replace(" ", "%20", $id)."')";
			$url = str_replace(' ', '%20', $url);
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			$headers = array( 'Cookie: B1SESSION='.$_COOKIE[B1SESSION].'; ROUTEID='.$_COOKIE[ROUTEID]);
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_VERBOSE, 1);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
			$response = curl_exec($curl);
			curl_close($curl);

			return $response;
		}

		/*   Función para realizar una query cualquiera y retorne los datos en SAP B1 SL   */
		function Query($query){
			logincaducado();
			$url = SERVER_SAP.$query;
			$url = str_replace(' ', '%20', $url);
			$curl = curl_init();
			$headers = array( 'Prefer: odata.maxpagesize=0', 'Cookie: B1SESSION='.@$_COOKIE[B1SESSION].'; ROUTEID='.@$_COOKIE[ROUTEID]);
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_VERBOSE, 1);
			curl_setopt($curl, CURLOPT_HTTPGET , true);
			$response = curl_exec($curl);
			curl_close($curl);

			return $response;
		}

		function logincaducado() {
			$url = SERVER_SAP.'Warehouses/$count'; //consulta sencilla para ver si retorna error de sesion

			//se agrego esta linea para reemplazar los espacios
			$url = str_replace(' ', '%20', $url);

			$curl = curl_init();
			$headers = array( 'Prefer: odata.maxpagesize=0', 'Cookie: B1SESSION='.@$_COOKIE[B1SESSION].'; ROUTEID='.@$_COOKIE[ROUTEID]);
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_VERBOSE, 1);
			curl_setopt($curl, CURLOPT_HTTPGET , true);
			$response = curl_exec($curl);
			curl_close($curl);

			//$response es la respuesta del resultado que devolvio service layer
			$json_output = json_decode($response);
			$saperror = (isset($json_output->error) ? $json_output->error : '');
			$coderror = (isset($saperror->code) ? $saperror->code : '');

			if ($coderror == '301') { //Invalid session or session already timeout.
				LogoutSAP();
				LoginSAP();
				return true;
			}else{
				return false;
			}
		}

		/*   Función para realizar una QueryService_PostQuery cualquiera y retorne los datos en SAP B1 SL   */
		function postQuery($QueryPath, $QueryOption){
			logincaducado();

		    $data = array(
		                    'QueryPath' => $QueryPath . '',
		                    'QueryOption' => $QueryOption . ''
		                 );
		    $dataJson = json_encode($data, JSON_PRETTY_PRINT);

			$url = SERVER_SAP.'QueryService_PostQuery';
			$url = str_replace(' ', '%20', $url);
			$curl = curl_init();
			$headers = array( 'Prefer: odata.maxpagesize=0', 'Cookie: B1SESSION='.$_COOKIE[B1SESSION].'; ROUTEID='.$_COOKIE[ROUTEID]);
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_VERBOSE, 1);
			curl_setopt($curl, CURLOPT_POST, true);
		    curl_setopt($curl, CURLOPT_POSTFIELDS, $dataJson);
			$response = curl_exec($curl);
			curl_close($curl);

			return $response;
		}

		/*   Función para Insertar datos adjuntos en la entidad Attachments2 en SAP B1 SL   */

		//$timestamp => agregar fechahora al archivo para evitar duplicados

		function UploadFile($binaryData, $timestamp = false, $id = ''){
            $boundary = uniqid();

			$post_data = build_data_files($boundary, $binaryData, $timestamp);
			logincaducado();
			if ($id)
				$url = SERVER_SAP.'Attachments2(' . $id . ')';
			else
				$url = SERVER_SAP.'Attachments2';
			$url = str_replace(' ', '%20', $url);
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			$headers = array(
				'Cookie: B1SESSION='.$_COOKIE[B1SESSION].'; ROUTEID='.$_COOKIE[ROUTEID],
				'Content-Type: multipart/form-data;boundary=' . $boundary, //requerido
				'Expect: '
			);
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_VERBOSE, 1);
			if ($id)
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
			else
				curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);




			$response = curl_exec($curl);
			curl_close($curl);

			return $response;
		}

		//funcion que construye el post data que se envia a SAP para el upload de archivo(s)
		//segun el estricto formato indicado en el manual, pagina 114 (3.17.2.2)
		function build_data_files($boundary, $files, $timestamp){
		    $data = '';
		    $eol = "\r\n";

		    $YmdHis = '';
		    if ($timestamp)
		    	$YmdHis = '_' . date('YmdHis');

		    $delimiter = '--' . $boundary;
		    for ($i=0; $i<count($files); $i++) {
		    	$content = file_get_contents($files[$i]['tmp_name']);
		    	$fileparts = pathinfo($files[$i]['name']);
		        $data .= $delimiter . $eol
		            . 'Content-Disposition: form-data; name="files"; filename="' . $fileparts['filename'] . $YmdHis . '.' . $fileparts['extension'] . '"' . $eol
		            . 'Content-Type: ' . $files[$i]['type'] . $eol
		            ;

		        $data .= $eol;
		        $data .= $content . $eol;
		    }
		    $data .= $delimiter . "--".$eol;
		    return $data;
		}
	}
?>