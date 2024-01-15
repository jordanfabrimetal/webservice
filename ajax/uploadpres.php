<?php
/*
UploadiFive
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
*/

include '../public/build/lib/fabrimetal/functions.php';
require_once "../config/conexionSap.php";

if(isset($_GET['op'])){
	switch ($_GET['op']) {
		case 'interno':
			error_log("Entraste a interno");
			$countfiles = count($_FILES['file']['name']);
			$archivos = array();
			for($i=0;$i<$countfiles;$i++){
				$filename = $_FILES['file']['name'][$i];
				move_uploaded_file($_FILES['file']['tmp_name'][$i],'../files/img_presupuesto/'.$filename);
				$archivos['archivo'.$i] = $filename;
			}
			$archivos['count'] = $countfiles;
			    // Convertir el array en JSON
				$json = json_encode($archivos);

				// Imprimir el JSON en el error_log
				error_log("El json del interno: ".$json);
			echo json_encode($archivos);
		break;
		case 'internoandroid':
			error_log("Entraste a interno");
			$archivos = array();
			foreach ($_FILES as $file) {
				$filename = $file['name'];
				move_uploaded_file($file['tmp_name'], '../files/img_presupuesto/' . $filename);
				$archivos[] = $filename;
			}
			$countfiles = count($archivos);
			$archivos['count'] = $countfiles;
			    // Convertir el array en JSON
				$json = json_encode($archivos);

				// Imprimir el JSON en el error_log
				error_log("El json del interno: ".$json);
			echo json_encode($archivos);
		break;
	}
}else{
	if (!empty($_FILES)) {
		error_log("NO Entraste a interno");
		$files = rearrange_array_attachments($_FILES);
		$rspta = UploadFile($files, true);//true: renombrar archivo y agregar timestamp
		$rspta = json_decode($rspta);
		if (isset($rspta->AbsoluteEntry)){
			echo json_encode(array('id'=>$rspta->AbsoluteEntry));
		}else{
			echo json_encode(array('id'=>0));
		}
	}
}
?>