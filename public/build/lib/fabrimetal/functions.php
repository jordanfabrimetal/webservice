<?php
	//functions: rearrange_array_attachments
	//funcion que reorganiza o reordena el array $_FILES
	//dejando TODOS los adjuntos de un formulario de esta forma:
	/*
	Array(
		[0] => Array(
			[name] => SERV Personalizado oct.xls
			[type] => application/vnd.ms-excel
			[tmp_name] => C:\wamp64\tmp\phpF53A.tmp
			[error] => 0
			[size] => 204800
		)
		[1] => Array(
			...
		)
		[2] => Array(
			...
		)
		etc
	)
	*/
	function rearrange_array_attachments($arr) {
		$indice = 0;
		$files = array();
		foreach($arr as $fieldname => $keys){
			if(is_array($keys['name'])){
				foreach($keys as $key => $list){
					foreach($list as $no => $value) {
						if(!$keys['error'][$no])
							$files[$indice + $no][$key] = $value;
					}
				}
			}else{
				if(!$keys['error'])
				$files[] = $keys;
			}
			if(count($files))
				$indice = max(array_keys($files)) + 1;
		}

		//si hay saltos de indices, reordena partiendo del indice 0
		$files = array_values(array_filter($files));
		return $files;
	}

	function comprimeImagen($origen, $destino, $nombre, $extension, $calidad){
		$imgInfo = getimagesize($origen); 
		$mime = $imgInfo['mime']; 
		
		switch($mime){ 
			case 'image/jpeg': 
				$image = imagecreatefromjpeg($origen); 
			break; 
			case 'image/png': 
				$image = imagecreatefrompng($origen); 
			break; 
			case 'image/gif': 
				$image = imagecreatefromgif($origen); 
			break; 
			default: 
				$image = imagecreatefromjpeg($origen); 
		}

        $nombreArchivo = $nombre.time().'.'.$extension;
		imagejpeg($image, $destino.$nombreArchivo, $calidad); 
		return $nombreArchivo; 
		
		/*$exif = exif_read_data($origen);
		if (!empty($exif['Orientation'])) {
			$exif['Orientation'];
			$imageToRotate = imagecreatefromjpeg($origen);
			switch ($exif['Orientation']) {
				case 3:
					$imageToRotate = imagerotate($imageToRotate, 180, 0);
				break;
				case 6:
					$imageToRotate = imagerotate($imageToRotate, -90, 0);
				break;
				case 8:
					$imageToRotate = imagerotate($imageToRotate, 90, 0);
				break;
			}
			//imagejpeg($imageToRotate, $destino, $calidad);
		}
		*/
		
		
		
	}

?>