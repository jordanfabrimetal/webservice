<?php
//ini_set("pcre.backtrack_limit", "50000000");
function newPdf($opcion, $data = '', $output = 'browser', $params = array()) {
	//$save = true  : guarda la data en la ruta dada, y retorna un json con status y ruta del archivo
	//$save = false : NO guarda la data, genera el pdf y retorna un json con status y el string binario
	
	require_once '../modelos/Encuesta.php';

	$encuesta = new Encuesta();


	switch ($opcion) {
        case 'informemantencion': //gservicio
        	$idservicio = $params['idservicio'];
        	$idascensor = $params['idascensor'];
        	$idencuesta = $params['idencuesta'];
        	$firmabase64 = $params['firmabase64'];

        	$rsptaservicio = $encuesta->ultimoInforme($idencuesta, $idascensor, $idservicio);
        	$periodo = $rsptaservicio['infv_periodo'] . '';
        	// $periodo = '202112';
        	$idvisita = $rsptaservicio['infv_id'];
        	// $idvisita = 120;

        	//Se ocupara sistema de plantillas TemplatePower
        	require_once("../public/build/lib/TemplatePower/class.TemplatePower.php7.inc.php");

        	switch ($idencuesta) {
        	    case 3:
        	        $t = new TemplatePower("../production/pla_print_informe_mantenimiento.html");
        	        $headerPdf = '
        	        <table>
        	            <tr>
        	                <td class="col-30 sinborde"><img src="../public/build/images/fm-logo-negro.png" height="25" alt=""></td>
        	                <td class="verticalcentertext sinborde"><center>Nº VISITA: {idvisita}</center></td>
        	                <td class="col-25 right sinborde"><img src="../public/build/images/kone-logo.png" height="25" alt="" style="border:1px solid grey"></td>
        	            </tr>
        	        </table>
        	        ';
        	        // $headerPdf = '<img src="../public/build/images/fm-logo-negro.png" height="40" alt="" style="float: left; display: inline-block;"> <div style="text-align: right"><img src="../public/build/images/kone-logo.png" height="40" alt="" style="float: left; display: inline-block; border:1px solid grey"><div>';
        	        $footerPdf = '';
        	        $opcionesMPDF = [
        	            'mode' => 'c',
        	            'margin_left' => 15,
        	            'margin_right' => 15,
        	            'margin_top' => 25,
        	            'margin_bottom' => 20,
        	            'margin_header' => 16,
        	            'margin_footer' => 13
        	        ];
        	        break;
        	    
        	    // default:
        	        // code...
        	        // break;
        	}

        	$t->prepare();

        	// $path_reportes = '../files/visitas/reportes/';

        	$rspequipo = $encuesta->infoVisita($idvisita);
        	$rows = $rspequipo->fetch_all(MYSQLI_ASSOC);
        	$modelo = '';
        	$tipoascensor = '';
        	$idascensor = '';
        	$fechavisita = '';

        	setlocale(LC_ALL, 'spanish');
        	$t->assign('periodotxt', '<strong>Período: ' . strtoupper(strftime('%B-%Y', strtotime($periodo . '01'))) . '</strong>');

        	foreach($rows as $i => $item)
        	{
        	    $t->assign('obra', $item['nomedificio'] . '');
        	    $t->assign('ascensor', $item['codigo'] . '');
        	    $modelo = $item['modelo'] . '';
        	    $tipoascensor = $item['tipoascensor'] . '';
        	    $idascensor = $item['infv_ascensor'] . '';
        	    $fechavisita = $item['infv_fecha'] . '';
        	    $fechamod = $item['infv_fechamod'] . '';
        	    $t->assign('modelo', $modelo . '');
        	    $t->assign('tipoascensor', $tipoascensor . '');
        	    $t->assign('observaciones', $item['infv_observaciones'] . '');
        	    $t->assign('supervisor', $item['supervisor'] . '');
        	    $t->assign('rutcli', mb_strtoupper($item['infv_rutcli']) . '');
        	    $t->assign('celularcli', mb_strtoupper($item['infv_celularcli']) . '');
        	    $t->assign('emailcli', mb_strtoupper($item['infv_emailcli']) . '');
        	    $t->assign('fechavisita', $fechavisita . '');
        	    $t->assign('empresa', (!$item['razon_social'] ? $item['infv_empresa'] . '' : $item['razon_social'] . '') . '');
        	    $t->assign('direccion', (!$item['calle'] ? $item['infv_direccion'] . '' : $item['calle'] . ' ' . $item['numero']) . '');
        	    if ($item['infv_estado'] == 'porfirmar') {
        	    	$t->assign('textoporfirmar', '<table style="margin-top: 30px;"><tr><td style="padding: 20px">PENDIENTE FIRMA DEL CLIENTE (Técnico: ' . $item['nomusuario'] . ')</td></tr></table>');
        	    }
        	    else {
        	    	$t->newBlock('firmacliente');
        	    	$t->assign('nomuser', $item['nomusuario'] . '');
        	    	$t->assign('nomcli', mb_strtoupper($item['infv_nomcli']) . '');
        	    	$t->assign('rutuser', $item['num_documento'] . '');
        	    	if ($firmabase64)
        	    		$t->assign('firmacliente', '<img src="' . $firmabase64 . '" alt="firmacliente" height="70">' . '');
        	    	else {
		        	    $path_firmacliente = '../files/servicioequipo/firmas/';
		        	    $firmacliente = $path_firmacliente . $item['infv_firmacliente'];
		        	    if (file_exists($firmacliente)) {
		        	        if (is_file($firmacliente))
		        	            $t->assign('firmacliente', '<img src="' . $firmacliente . '" alt="firmacliente" height="70">' . '');
		        	    }
		        	}

	        	    $path_firmausuario = '../files/usuarios/firmas/';
	        	    $firmausuario = $path_firmausuario . $item['filefir'];
	        	    if (file_exists($firmausuario)) {
	        	        if (is_file($firmausuario))
	        	            $t->assign('firmausuario', '<img src="' . $firmausuario . '" alt="firmausuario" height="70">' . '');
	        	    }
        	    	$t->assign('fechafirmacliente', $fechamod . '');
        	    }
        	}


        	$rspta = $encuesta->infoEquipo($idservicio);
        	$rsptaJson = json_decode($rspta, true); //true para que sea array, no objeto
        	$data = Array();

        	if ($rspta != 'NODATASAP') {
        	    foreach($rsptaJson as $row){
        	        $t->assign('_ROOT.obra', $row['equEdificio'] . '');
        	        $t->assign('_ROOT.ascensor', $row['equSnInterno'] . '');
        	        $t->assign('_ROOT.modelo', $row['artModelo'] . '');
        	        $t->assign('tipoascensor', $row['artTipoEquipo'] . '');
        	        $t->assign('supervisor', $row['tecNombre'] . ' ' . $row['tecApellido'] . '');
        	        $t->assign('empresa', trim($row['cliNombre']) . '');
        	        $t->assign('direccion', trim($row['equCalle'] . ' ' . $row['equCalleNro']) . '');
        	        $t->assign('idcliente', trim($row['cliCodigo']) . '');
        	    }
        	}

        	$result = $encuesta->numeroDeVisita($idencuesta, $idascensor, $idvisita);
        	$t->assign('nvisita', $result['nvisita'] . '');
        	$t->assign('idvisita', sprintf("%05d", $idvisita) . '');
        	// $t->assign('idvisita', date('Y', strtotime($fechavisita)) . sprintf("%05d", $idvisita) . '');

        	$headerPdf = str_replace('{nvisita}', $result['nvisita'] . '', $headerPdf);
        	$headerPdf = str_replace('{idvisita}', sprintf("%05d", $idvisita) . '', $headerPdf);

        	$arrMeses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        	// $mesVisita = $arrMeses[date('n', strtotime($fechavisita)) - 1];

        	$mesVisita = substr($periodo, -2); //captura el mes actual o el anterior, segun seleccione
        	$mesVisita = $mesVisita * 1;

        	$mesVisita = $arrMeses[$mesVisita - 1];

        	$t->assign('_ROOT.sel' . $mesVisita, 'selcolumn');

        	// echo $mesVisita;

        	$arrMantenimiento['Ene'] = ['B-ST', 'C', 'M', 'SM', 'L'];
        	$arrMantenimiento['Feb'] = ['B-ST', 'S', 'D', 'L', 'Z'];
        	$arrMantenimiento['Mar'] = ['B-ST', 'C', 'SM', 'L'];
        	$arrMantenimiento['Abr'] = ['B-ST', 'S', 'D', 'L', 'Z'];
        	$arrMantenimiento['May'] = ['B-ST', 'C', 'M', 'SM', 'L'];
        	$arrMantenimiento['Jun'] = ['B-ST', 'MX', 'S', 'D', 'L', 'Z'];
        	$arrMantenimiento['Jul'] = ['B-ST', 'C', 'SM', 'L'];
        	$arrMantenimiento['Ago'] = ['B-ST', 'S', 'D', 'L', 'Z'];
        	$arrMantenimiento['Sep'] = ['B-ST', 'C', 'M', 'SM', 'L'];
        	$arrMantenimiento['Oct'] = ['B-ST', 'S', 'D', 'L', 'Z'];
        	$arrMantenimiento['Nov'] = ['B-ST', 'C', 'SM', 'L'];
        	$arrMantenimiento['Dic'] = ['B-ST', 'MX', 'S', 'D', 'L', 'Z'];

        	//BLOQUES DE LA ENCUESTA
        	$rspbloques = $encuesta->bloques($idencuesta);

        	//RESPUESTAS DE LA VISITA
        	$rspvisita = $encuesta->respPreguntas($idvisita);
        	$respuestas = $rspvisita->fetch_all(MYSQLI_ASSOC);
        	foreach ( $respuestas as $key => $respuesta )
        	{
        	    $resp[$respuesta['resp_tipo']][$respuesta['resp_idtipo']] = $respuesta['resp_data'] . '';
        	}

        	$rows = $rspbloques->fetch_all(MYSQLI_ASSOC);
        	$jk = 0;
        	foreach($rows as $i => $item)
        	{
        	    //verificacion de que modelo del equipo contenga el tipo de equipo del bloque
        	    if (strtolower($tipoascensor) == 'escalera')
        	        $modelo = $tipoascensor;
        	    else
        	    {
        	        // if(strpos(strtolower($item['blq_tipoequipo']), strtolower($modelo) ) !== 0)
        	        if(substr(strtolower($item['blq_tipoequipo']), 0, strlen($modelo)) !== strtolower($modelo))
        	            $modelo = 'OTROS';
        	    }
        	    
        	    // if(strpos(strtolower($item['blq_tipoequipo']), strtolower($modelo) ) === 0 || $item['blq_tipoequipo'] == 'TODOS')
        	    // 
        	    // 
        	    $arrTipoMantencion = explode("/", $item['blq_tipoequipo']);

        	    // if(count(array_unique($array, SORT_REGULAR)) < count($array)) {
        	    // if(1==1 || substr(strtolower($item['blq_tipoequipo']), 0, strlen($modelo)) === strtolower($modelo) || $item['blq_tipoequipo'] == 'TODOS')
        	    if (!empty(array_intersect($arrTipoMantencion, $arrMantenimiento[$mesVisita])))
        	    {
        	        $t->newBlock('bloques');
        	        $t->assign('nombloque', $item['blq_nombre'] . '');

        	        $idbloque = $item['blq_id'] . '';

        	        //PREGUNTAS DEL BLOQUE
        	        $rsppreguntas = $encuesta->preguntas($idbloque);
        	        $rows2 = $rsppreguntas->fetch_all(MYSQLI_ASSOC);
        	        $jk = 0;
        	        foreach($rows2 as $j => $item2)
        	        {
        	            $idpregunta = $item2['preg_id'];

        	            if (($jk%2) == 0) {

        	                if (isset($resp['pregunta'][$idpregunta]))
        	                {
        	                	$t->newBlock('preguntas');
        	                	$jk++;
        	                	$t->assign('pregunta1', $item2['preg_nombre'] . '');
        	                    if ($resp['pregunta'][$idpregunta] == 'SI')
        	                        $t->assign('si1', '<img src="../public/build/images/circle_2.png" height="8" style="display: block; margin-left: auto; margin-right: auto">');
        	                }
        	            }
        	            else {

        	                if (isset($resp['pregunta'][$idpregunta]))
        	                {
        	                	$jk++;
        	                	$t->assign('pregunta2', $item2['preg_nombre'] . '');
        	                    if ($resp['pregunta'][$idpregunta] == 'SI')
        	                        $t->assign('si2', '<img src="../public/build/images/circle_2.png" height="8" style="display: block; margin-left: auto; margin-right: auto">');
        	                }
        	            }
        	            // $t->assign('num', $jk);

        	            if ($item2['preg_comentario'])
        	            {
        	                if (isset($resp['comentario'][$idpregunta]))
        	                    $t->assign('comentario', $resp['comentario'][$idpregunta] . '');
        	            }
        	        }
        	    }
        	}

        	//print the result
        	 $t->printToScreen();
        	 exit();

        	$style02 = '../public/build/css/form_informe_mantenimiento.css';

        	require_once("../public/build/lib/mPdfSC/vendor/autoload.php");

        	$mpdf = new \Mpdf\Mpdf($opcionesMPDF);

        	// $mpdf->keep_table_proportions = true;

        	$mpdf->SetHTMLHeader($headerPdf, 'O', true);
        	$mpdf->SetHTMLFooter($footerPdf, 'O', true);

        	// $mpdf->SetHTMLHeader('<img src="../public/build/images/fm-logo-negro.png" height="40" alt="" style="float: left; display: inline-block;"> <div style="text-align: right">Página {PAGENO} / {nb}<div>', 'O', true);
        	// $mpdf->SetHTMLHeader('<img src="../production/informes/img/fm-logo-negro.png" height="40" alt="" style="float: left; display: inline-block;"> <div style="text-align: right">Página {PAGENO} / {nb}<div>', 'O', true);

        	// $mpdf->SetDisplayMode('fullwidth');
        	$mpdf->SetDisplayMode(100);
        	// $mpdf->SetDisplayMode('fullpage');

        	$mpdf->SetDisplayPreferences('/HideToolbar/CenterWindow/FitWindow');
        	// $mpdf->SetDisplayPreferences('/HideMenubar/HideToolbar/CenterWindow/FitWindow');

        	$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list

        	// Load a stylesheet
        	$stylesheet02 = file_get_contents($style02);

        	$html = $t->getOutputContent();
            $mpdf->SetCompression(false);
        	$mpdf->WriteHTML($stylesheet02, 1); // The parameter 1 tells that this is css/style only and no body/html/text
        	$mpdf->WriteHTML($html,2);
            $mpdf->AddPage();
            $mpdf->img_dpi = 25;

            $html2 = '<table>
                    <tr>
                        <td class="col-50 titulo" style="text-align:center;font-size:20px">Foso</td>
                        <td class="col-50 titulo" style="text-align:center;font-size:20px">Techo Cabina</td>
                    </tr>
                    <tr>
                        <td class="col-50">'.((isset($params['imgfoso'])) ? '<img src="' . $params['imgfoso'] . '" alt="imgfoso">' : "" ).'</td>
                        <td class="col-50">'.((isset($params['imgtecho'])) ? '<img src="' . $params['imgtecho'] . '" alt="imgtecho">' : "" ).'</td>
                    </tr>
                    <tr>
                        <td class="col-50 titulo" style="text-align:center;font-size:20px">Maquina</td>
                        <td class="col-50 titulo" style="text-align:center;font-size:20px">Operador de Puerta de Cabina</td>
                    </tr>
                    <tr>
                        <td class="col-50">'.((isset($params['imgmaquina'])) ? '<img src="' . $params['imgmaquina'] . '" alt="imgmaquina">' : "" ).'</td>
                        <td class="col-50">'.((isset($params['imgoperador'])) ? '<img src="' . $params['imgoperador'] . '" alt="imgoperador">' : "" ).'</td>
                    </tr>
                </table>';
            //$html2 = '<img src="' . $params['imgfoso'] . '" alt="imgfoso">' . '';
            $mpdf->WriteHTML($html2,2);
        	// $PROYECTO_REPORTE_PDF = 'REPORTE_DEL_PROYECTO_' . $datos['codigoimp'] . '.pdf';
        	$PROYECTO_REPORTE_PDF = 'FM_IM' .$idvisita . '_EQ' . $idascensor . '_' . $periodo . '.pdf';

        	if ($output == 'browser') {
        		$mpdf->Output($PROYECTO_REPORTE_PDF, \Mpdf\Output\Destination::INLINE);
        	}
        	elseif ($output == 'variable') {
        		// capture the output into buffer
        		ob_start();
        		$mpdf->Output($PROYECTO_REPORTE_PDF, \Mpdf\Output\Destination::INLINE);

        		// holds the buffer into a variable
        		$html = ob_get_contents(); 
        		ob_get_clean();

        		return $html;
        	}


        	break;
        case 'informemantencionescalera': //mantencionescalera
            $idservicio = $params['idservicio'];
            $idascensor = $params['idascensor'];
            $idencuesta = $params['idencuesta'];

            $rsptaservicio = $encuesta->ultimoInforme($idencuesta, $idascensor, $idservicio);
            $periodo = $rsptaservicio['infv_periodo'] . '';
            $idvisita = $rsptaservicio['infv_id'];
            // exit();

            //Se ocupara sistema de plantillas TemplatePower
            require_once("../public/build/lib/TemplatePower/class.TemplatePower.php7.inc.php");

            switch ($idencuesta) {
                case 4:
                    $t = new TemplatePower("../production/pla_print_informe_mantenimiento_escalera.html");
                    $headerPdf = '
                        <table>
                            <tr>
                                <td class="col-30 sinborde"><img src="../public/build/images/fm-logo-negro.png" height="25" alt=""></td>
                                <td class="verticalcentertext sinborde"><center>Nº VISITA: {idvisita}</center></td>
                                <td class="col-25 right sinborde"><img src="../public/build/images/kone-logo.png" height="25" alt="" style="border:1px solid grey"></td>
                            </tr>
                        </table>
                    ';
                    $footerPdf = '';
                    $opcionesMPDF = [
                        'mode' => 'c',
                        'margin_left' => 15,
                        'margin_right' => 15,
                        'margin_top' => 25,
                        'margin_bottom' => 20,
                        'margin_header' => 16,
                        'margin_footer' => 13
                    ];
                break;
            }

            $t->prepare();

            $rspequipo = $encuesta->infoVisita($idvisita);
            $rows = $rspequipo->fetch_all(MYSQLI_ASSOC);
            $modelo = '';
            $tipoascensor = '';
            $idascensor = '';
            $fechavisita = '';

            setlocale(LC_ALL, 'spanish');
            $t->assign('periodotxt', '<strong>Período: ' . strtoupper(strftime('%B-%Y', strtotime($periodo . '01'))) . '</strong>');

            foreach($rows as $i => $item){
                $rspta = $encuesta->infoEquipo($idservicio);
                $rsptaJson = json_decode($rspta, true); //true para que sea array, no objeto
                $data = Array();

                if ($rspta != 'NODATASAP') {
                    foreach($rsptaJson as $row){
                        $t->assign('_ROOT.obra', $row['equEdificio'] . '');
                        $t->assign('_ROOT.ascensor', $row['equSnInterno'] . '');
                        $t->assign('_ROOT.modelo', $row['artModelo'] . '');
                        $t->assign('tipoascensor', $row['artTipoEquipo'] . '');
                        $t->assign('supervisor', $row['tecNombre'] . ' ' . $row['tecApellido'] . '');
                        $t->assign('empresa', trim($row['cliNombre']) . '');
                        $t->assign('direccion', trim($row['equCalle'] . ' ' . $row['equCalleNro']) . '');
                        $t->assign('idcliente', trim($row['cliCodigo']) . '');
                    }
                }
                //$t->assign('obra', $item['nomedificio'] . '');
                //$t->assign('ascensor', $item['codigo'] . '');
                $modelo = $item['modelo'] . '';
                //$tipoascensor = $item['tipoascensor'] . '';
                $idascensor = $item['infv_ascensor'] . '';
                $fechavisita = $item['infv_fecha'] . '';
                //$t->assign('modelo', $modelo . '');
                $t->assign('tipoascensor', $tipoascensor . '');
                $t->assign('supervisor', $item['supervisor'] . '');
                $t->assign('nomuser', $item['nomusuario'] . '');
                $t->assign('rutuser', $item['num_documento'] . '');
                $t->assign('nomcli', mb_strtoupper($item['infv_nomcli']) . '');
                $t->assign('rutcli', mb_strtoupper($item['infv_rutcli']) . '');
                $t->assign('celularcli', mb_strtoupper($item['infv_celularcli']) . '');
                $t->assign('emailcli', mb_strtoupper($item['infv_emailcli']) . '');
                $t->assign('fechavisita', $fechavisita . '');
                $t->assign('empresa', (!$item['razon_social'] ? $item['infv_empresa'] . '' : $item['razon_social'] . '') . '');
                $t->assign('direccion', (!$item['calle'] ? $item['infv_direccion'] . '' : $item['calle'] . ' ' . $item['numero']) . '');

                if(!empty($item['infv_observaciones'])){
                    $t->assign('observaciones', '<table class="conborde nobordetop"><tr><td class="subtitulo">OBSERVACIONES</td></tr></table><table class="conborde nobordetop"><tr><td class="textochico">'.$item['infv_observaciones'].'</td></tr></table>');
                }
                if($item['infv_estado'] == 'porfirmar'){
                    $t->assign('textoporfirmar', '<table style="margin-top: 30px;"><tr><td style="padding: 20px">PENDIENTE FIRMA DEL CLIENTE (Técnico: ' . $item['nomusuario'] . ')</td></tr></table>');
                }else{
                    $t->newBlock('firmacliente');
                    $t->assign('nomuser', $item['nomusuario'] . '');
                    $t->assign('nomcli', mb_strtoupper($item['infv_nomcli']) . '');
                    $t->assign('rutuser', $item['num_documento'] . '');
                    if(@$firmabase64)
                        $t->assign('firmacliente', '<img src="' . @$firmabase64 . '" alt="firmacliente" height="70">' . '');
                    else{
                        $path_firmacliente = '../files/servicioequipo/firmas/';
                        $firmacliente = $path_firmacliente . $item['infv_firmacliente'];
                        if(file_exists($firmacliente)){
                            if(is_file($firmacliente))
                                $t->assign('firmacliente', '<img src="' . $firmacliente . '" alt="firmacliente" height="70">' . '');
                        }
                    }

                    $path_firmausuario = '../files/usuarios/firmas/';
                    $firmausuario = $path_firmausuario . $item['filefir'];
                    if(file_exists($firmausuario)){
                        if(is_file($firmausuario))
                            $t->assign('firmausuario', '<img src="' . $firmausuario . '" alt="firmausuario" height="70">' . '');
                    }
                    $t->assign('fechafirmacliente', @$fechamod . '');
                }
            }

            $result = $encuesta->numeroDeVisita($idencuesta, $idascensor, $idvisita);
            $t->assign('nvisita', $result['nvisita'] . '');
            $t->assign('idvisita', sprintf("%05d", $idvisita) . '');

            $headerPdf = str_replace('{nvisita}', $result['nvisita'] . '', $headerPdf);
            $headerPdf = str_replace('{idvisita}', sprintf("%05d", $idvisita) . '', $headerPdf);

            $arrMeses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

            $mesVisita = substr($periodo, -2); //captura el mes actual o el anterior, segun seleccione
            $mesVisita = $mesVisita * 1;

            $mesVisita = $arrMeses[$mesVisita - 1];

            $t->assign('_ROOT.sel' . $mesVisita, 'selcolumn');

            //BLOQUES DE LA ENCUESTA
            $rspbloques = $encuesta->bloques($idencuesta);

            //RESPUESTAS DE LA VISITA
            $rspvisita = $encuesta->respPreguntas($idvisita);
            $respuestas = $rspvisita->fetch_all(MYSQLI_ASSOC);

            foreach($respuestas as $key => $respuesta){
                $resp[$respuesta['resp_tipo']][$respuesta['resp_idtipo']] = $respuesta['resp_data'] . '';
                /*if(!in_array($respuesta['blq_id'],$resp['bloques'])){
                    $resp['bloques'][] = $respuesta['blq_id'] . '';
                }*/
            }

            $rows = $rspbloques->fetch_all(MYSQLI_ASSOC);
            foreach($rows as $i => $item){
                //verificacion de que modelo del equipo contenga el tipo de equipo del bloque
                if (strtolower($tipoascensor) == 'escalera')
                    $modelo = $tipoascensor;
                else{
                    if(substr(strtolower($item['blq_tipoequipo']), 0, strlen($modelo)) !== strtolower($modelo))
                        $modelo = 'OTROS';
                }

                $arrTipoMantencion = explode("/", $item['blq_tipoequipo']);
                // if(in_array($item['blq_id'], $resp['bloques'])){
                    $t->newBlock('bloques');
                    // $item['blq_nombre'];
                    $t->assign('nombloque', $item['blq_nombre'] . '');
                // }
                $idbloque = $item['blq_id'] . '';
                //PREGUNTAS DEL BLOQUE
                $rsppreguntas = $encuesta->preguntas($idbloque);
                $rows2 = $rsppreguntas->fetch_all(MYSQLI_ASSOC);
                foreach($rows2 as $j => $item2){
                    $idpregunta = $item2['preg_id'];
                    
                    if (isset($resp['pregunta'][$idpregunta])){
                        $t->newBlock('preguntas');
                        $t->assign('pregunta', $item2['preg_nombre'] . '');
                        $t->assign('respuesta', $resp['pregunta'][$idpregunta]);
                    }
                    if($item2['preg_comentario']){
                        if(isset($resp['comentario'][$idpregunta]))
                            $t->assign('comentario', $resp['comentario'][$idpregunta] . '');
                    }
                }
            }

            //print the result
            //$t->printToScreen();
            //exit();

            $style02 = '../public/build/css/form_informe_mantenimiento.css';

            require_once("../public/build/lib/mPdfSC/vendor/autoload.php");

            $mpdf = new \Mpdf\Mpdf($opcionesMPDF);

            $mpdf->SetHTMLHeader($headerPdf, 'O', true);
            $mpdf->SetHTMLFooter($footerPdf, 'O', true);
            $mpdf->SetDisplayMode(100);

            $mpdf->SetDisplayPreferences('/HideToolbar/CenterWindow/FitWindow');

            $mpdf->list_indent_first_level = 0;

            $stylesheet02 = file_get_contents($style02);

            $html = $t->getOutputContent();

            $mpdf->WriteHTML($stylesheet02, 1); 
            $mpdf->WriteHTML($html,2);

            $PROYECTO_REPORTE_PDF = 'FM_IM' .$idvisita . '_EQ' . $idascensor . '_' . $periodo . '.pdf';

            if($output == 'browser'){
                $mpdf->Output($PROYECTO_REPORTE_PDF, \Mpdf\Output\Destination::INLINE);
            }elseif($output == 'variable'){
                ob_start();
                $mpdf->Output($PROYECTO_REPORTE_PDF, \Mpdf\Output\Destination::INLINE);

                $html = ob_get_contents(); 
                ob_get_clean();

                return $html;
            }
        break;

        case 'informemantencionnuevo': //gservicio
            //echo '<pre>';print_r($params);echo '</pre>';break;
            $idservicio = $params['idservicio'];
            $idascensor = $params['idascensor'];
            $idencuesta = $params['idencuesta'];
            $firmabase64 = $params['firmabase64'];

            $rsptaservicio = $encuesta->ultimoInforme($idencuesta, $idascensor, $idservicio);
            $periodo = $rsptaservicio['infv_periodo'] . '';
            $idvisita = $rsptaservicio['infv_id'];


            $rspequipo = $encuesta->infoVisita($idvisita);
            $rows = $rspequipo->fetch_all(MYSQLI_ASSOC);
            foreach($rows as $i => $item)
            {
                $tipoascensor = $item['tipoascensor'] . '';
                $observaciones = $item['infv_observaciones'];
            }

            //Se ocupara sistema de plantillas TemplatePower
            require_once("../public/build/lib/TemplatePower/class.TemplatePower.php7.inc.php");

            switch ($idencuesta) {
                case 5:
                    $t = new TemplatePower("../production/pla_print_informe_mantenimiento_nuevo.html");
                    $headerPdf = '
                    <table style="border:none;">
                        <tr>
                            <td class="col-30 sinborde"><img src="../public/build/images/fm-logo-negro.png" height="25" alt=""></td>
                            <td class="verticalcentertext sinborde" style="font-size:13px;"><center><b>INFORME DE MANTENIMIENTO Nº '.$params['idservicio'].'</b></center></td>
                            <td class="col-25 right sinborde">&nbsp;</td>
                        </tr>
                    </table>
                    ';
                    $footerPdf = '';
                    $opcionesMPDF = [
                        'mode' => 'c',
                        'margin_left' => 15,
                        'margin_right' => 15,
                        'margin_top' => 25,
                        'margin_bottom' => 10,
                        'margin_header' => 16,
                        'margin_footer' => 5,
                        'format' => 'LEGAL'
                    ];
                    break;
            }

            $t->prepare();

            $t->newBlock('informe');
            $t->assign('fechahoy',date("d/m/Y"));
            $t->assign('equipoFM',$params['actividadsap']['equSnInterno']);

            $t->newBlock('cliente');
            $t->assign('edificio',$params['actividadsap']['equCcostoNombre']);
            $t->assign('direccion',$params['actividadsap']['equCalle'].' '.$params['actividadsap']['equCalleNro']);
            $t->assign('ciudad',$params['actividadsap']['equCiudad']);
            $t->assign('comuna',$params['actividadsap']['equComuna']);
            $t->assign('nombrecontacto','');
            $t->assign('telefonocontacto','');
            $t->assign('emailcontacto',$params['actividadsap']['cliEmail']);

            $t->newBlock('equipo');
            $t->assign('marca',$params['actividadsap']['artFabricante']);
            $t->assign('modelo',$params['actividadsap']['artModelo']);
            $t->assign('paradas',$params['actividadsap']['artParadas']);
            $t->assign('velocidad',$params['actividadsap']['artVelocidad']);
            $t->assign('carga',$params['actividadsap']['artCarga']);
            $t->assign('maniobra','');
            $t->assign('comando',$params['actividadsap']['artComando']);
            $t->assign('sm','');
            $t->assign('accionamiento','');

            $t->newBlock('supervisor');
            $t->assign('nombresupervisor',$params['actividadsap']['equSupNombre'].' '.$params['actividadsap']['equSupApellido']);
            $t->assign('correosupervisor',$params['actividadsap']['equSupEmail']);
            $t->assign('telefonosupervisor',$params['actividadsap']['equSupTelefono']);

            $t->newBlock('matenimiento');
            $t->assign('equipoatendido',$params['actividadsap']['equSnInterno']);
            switch(strlen($params['actividadsap']['actHoraIni'])){case 1: $horaInicio = '000'.$horaInicio; break;case 2: $horaInicio = '00'.$horaInicio; break;case 3: $horaInicio = '0'.$horaInicio; break;case 4: $horaInicio = $params['actividadsap']['actHoraIni']; break;}
            $horaInicio = date('H:i',strtotime($horaInicio));
            $t->assign('fechainicio',date('d/m/Y', strtotime($params['actividadsap']['actFechaIni'])).' '.$horaInicio);
            $t->assign('fechafin',date('d/m/Y H:i'));
            $t->assign('tecnico',$params['actividadsap']['tecNombre'].' '.$params['actividadsap']['tecApellido']);

            $t->newBlock('checklist');
            $arrMeses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
            $arrMesesCompleto = ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
            $mesVisita = substr($periodo, -2); 
            $mesVisitaTexto = $mesVisita * 1;
            $mesVisita = $mesVisita * 1;
            $valmesVisita = $mesVisitaTexto * 1;

            $mesVisita = $arrMeses[$mesVisita - 1];
            $mestexto = $arrMesesCompleto[$valmesVisita - 1];

            $t->assign('sel' . $mesVisita, 'selcolumn');

            $arrMantenimiento['Ene'] = ['B-ST', 'C', 'M', 'SM', 'L'];
            $arrMantenimiento['Feb'] = ['B-ST', 'S', 'D', 'L', 'Z'];
            $arrMantenimiento['Mar'] = ['B-ST', 'C', 'SM', 'L'];
            $arrMantenimiento['Abr'] = ['B-ST', 'S', 'D', 'L', 'Z'];
            $arrMantenimiento['May'] = ['B-ST', 'C', 'M', 'SM', 'L'];
            $arrMantenimiento['Jun'] = ['B-ST', 'MX', 'S', 'D', 'L', 'Z'];
            $arrMantenimiento['Jul'] = ['B-ST', 'C', 'SM', 'L'];
            $arrMantenimiento['Ago'] = ['B-ST', 'S', 'D', 'L', 'Z'];
            $arrMantenimiento['Sep'] = ['B-ST', 'C', 'M', 'SM', 'L'];
            $arrMantenimiento['Oct'] = ['B-ST', 'S', 'D', 'L', 'Z'];
            $arrMantenimiento['Nov'] = ['B-ST', 'C', 'SM', 'L'];
            $arrMantenimiento['Dic'] = ['B-ST', 'MX', 'S', 'D', 'L', 'Z'];

            //BLOQUES DE LA ENCUESTA
            $rspbloques = $encuesta->bloques($idencuesta);

            //RESPUESTAS DE LA VISITA
            $rspvisita = $encuesta->respPreguntas($idvisita);
            $respuestas = $rspvisita->fetch_all(MYSQLI_ASSOC);
            foreach ( $respuestas as $key => $respuesta )
            {
                $resp[$respuesta['resp_tipo']][$respuesta['resp_idtipo']] = $respuesta['resp_data'] . '';
            }
            $rows = $rspbloques->fetch_all(MYSQLI_ASSOC);
            $jk = 0;
            foreach($rows as $i => $item)
            {
                //verificacion de que modelo del equipo contenga el tipo de equipo del bloque
                if (strtolower($tipoascensor) == 'escalera')
                    $modelo = $tipoascensor;
                else
                {
                    // if(strpos(strtolower($item['blq_tipoequipo']), strtolower($modelo) ) !== 0)
                    if(substr(strtolower($item['blq_tipoequipo']), 0, strlen($modelo)) !== strtolower($modelo))
                        $modelo = 'OTROS';
                }
                $arrTipoMantencion = explode("/", $item['blq_tipoequipo']);
                if (!empty(array_intersect($arrTipoMantencion, $arrMantenimiento[$mesVisita])))
                {
                    $t->newBlock('bloques');
                    $t->assign('nombloque', $item['blq_nombre'] . '');

                    $idbloque = $item['blq_id'] . '';

                    //PREGUNTAS DEL BLOQUE
                    $rsppreguntas = $encuesta->preguntas($idbloque);
                    $rows2 = $rsppreguntas->fetch_all(MYSQLI_ASSOC);
                    $jk = 0;
                    foreach($rows2 as $j => $item2)
                    {
                        $idpregunta = $item2['preg_id'];

                        if (($jk%2) == 0) {

                            if (isset($resp['pregunta'][$idpregunta]))
                            {
                                $t->newBlock('preguntas');
                                $jk++;
                                $t->assign('pregunta1', $item2['preg_nombre'] . '');
                                if ($resp['pregunta'][$idpregunta] == 'SI' && $item2['tipp_id'] == 1){
                                    $t->assign('si1', '<img src="../public/build/images/check.png" height="8" style="display: block; margin-left: auto; margin-right: auto">');
                                }elseif($item2['tipp_id'] == 2){
                                    $t->assign('si1', $resp['pregunta'][$idpregunta]);
                                }
                            }
                        }
                        else {

                            if (isset($resp['pregunta'][$idpregunta]))
                            {
                                $jk++;
                                $t->assign('pregunta2', $item2['preg_nombre'] . '');
                                if ($resp['pregunta'][$idpregunta] == 'SI' && $item2['tipp_id'] == 1){
                                    $t->assign('si2', '<img src="../public/build/images/check.png" height="8" style="display: block; margin-left: auto; margin-right: auto">');
                                }elseif($item2['tipp_id'] == 2){
                                    $t->assign('si2', $resp['pregunta'][$idpregunta]);
                                }
                            }
                        }
                        // $t->assign('num', $jk);

                        if ($item2['preg_comentario'])
                        {
                            if (isset($resp['comentario'][$idpregunta]))
                                $t->assign('comentario', $resp['comentario'][$idpregunta] . '');
                        }
                    }
                }
            }
            $t->assign('_ROOT.observaciones', $observaciones);

            $t->newBlock('imagenes');
            $t->assign('imgfoso',$params['imgfoso']);
            $t->assign('imgtecho',$params['imgtecho']);
            $t->assign('imgmaquina',$params['imgmaquina']);
            $t->assign('imgoperador',$params['imgoperador']);

            $t->newBlock('servicio');
            $t->assign('estadofin',$params['estadofintext']);
            $t->assign('observacionfin',$params['obsfin']);
            //print the result
            // $t->printToScreen();
            // exit();

            $style02 = '../public/build/css/form_informe_mantenimiento.css';

            require_once("../public/build/lib/mPdfSC/vendor/autoload.php");

            $mpdf = new \Mpdf\Mpdf($opcionesMPDF);

            // $mpdf->keep_table_proportions = true;
            $footerPdf = '<table style="border:none"><tr><td class="col-3 left sinborde">{DATE d/m/Y H:i}</td><td class="col-3 verticalcentertext sinborde"><center>{PAGENO} / {nbpg}</center></td><td class="col-3 right sinborde"><img src="../public/build/images/kone-logo.png" height="25" alt="" style="border:1px solid grey"></td></tr></table>';
            $mpdf->SetHTMLHeader($headerPdf, 'O', true);
            $mpdf->SetHTMLFooter($footerPdf, 'O', true);
            // $mpdf->SetHTMLHeader('<img src="../public/build/images/fm-logo-negro.png" height="40" alt="" style="float: left; display: inline-block;"> <div style="text-align: right">Página {PAGENO} / {nb}<div>', 'O', true);
            // $mpdf->SetHTMLHeader('<img src="../production/informes/img/fm-logo-negro.png" height="40" alt="" style="float: left; display: inline-block;"> <div style="text-align: right">Página {PAGENO} / {nb}<div>', 'O', true);

            // $mpdf->SetDisplayMode('fullwidth');
            $mpdf->SetDisplayMode(100);
            // $mpdf->SetDisplayMode('fullpage');

            $mpdf->SetDisplayPreferences('/HideToolbar/CenterWindow/FitWindow');
            // $mpdf->SetDisplayPreferences('/HideMenubar/HideToolbar/CenterWindow/FitWindow');

            $mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list

            // Load a stylesheet
            $stylesheet02 = file_get_contents($style02);

            $html = $t->getOutputContent();
            $mpdf->WriteHTML($stylesheet02, 1); // The parameter 1 tells that this is css/style only and no body/html/text
            $mpdf->WriteHTML($html,2);
            
            if($params['presupuesto'] == 1){
                $mpdf->AddPage();
                $html2 = '<table class="p-5" style="border-collapse: collapse;"><tr><td width=623 colspan=32 class="titulo-gris">SOLICITUD DE REPARACIÓN </td></tr><tr><td colspan=10 >DESCRIPCIÓN</td><td colspan=22 >'.nl2br($params['presupuestoobservacion']).'</td></tr></table><table style="border:none !important;">';
                $mpdf->WriteHTML($html2,2);
                if(isset($params['imgpresupuesto1'])){
                    $imagen1 = '<tr><td width:50%;><img style="padding:5px; width: 100%" src="../files/images/'.$params['imgpresupuesto1'].'" ></td>';
                    $mpdf->WriteHTML($imagen1,2);
                }
                if(isset($params['imgpresupuesto2'])){
                    $imagen1 = '<td width:50%;><img style="padding:5px; width: 100%" src="../files/images/'.$params['imgpresupuesto2'].'" ></td></tr>';
                    $mpdf->WriteHTML($imagen1,2);
                }
                if(isset($params['imgpresupuesto3'])){
                    $imagen1 = '<tr><td width:50%;><img style="padding:5px; width: 100%" src="../files/images/'.$params['imgpresupuesto3'].'" ></td></tr>';
                    $mpdf->WriteHTML($imagen1,2);
                }
                $html2 = '</table>';
                $mpdf->WriteHTML($html2);
            }

            $firma = '
            <br>
            <br>
            <table>
                <tr>
                    <td width=623 colspan=32 class="titulo-gris">RECEPCIÓN CONFORME MANTENIMIENTO '.$mestexto.' '.date('Y').' POR PARTE DEL CLIENTE </td>
                </tr>
                <tr>
                    <td width=102 colspan=3 >Nombre </td>
                    <td width=134 colspan=7 >'.$params['nombrecliente'].'</td>
                    <td width=60 colspan=4 >RUT </td>
                    <td width=72 colspan=5 >'.$params['rutcliente'].'</td>
                    <td width=45 colspan=4 >CARGO</td>
                    <td width=210 colspan=9 >'.$params['cargocliente'].'</td>
                </tr>
                <tr>
                    <td width=623 colspan=32 align="center"><img style="margin: 0 auto !important;" src="'.$params['firmacliente'].'"></td>
                </tr>
            </table>';
            $mpdf->WriteHTML($firma,2);
            // $PROYECTO_REPORTE_PDF = 'REPORTE_DEL_PROYECTO_' . $datos['codigoimp'] . '.pdf';
            $PROYECTO_REPORTE_PDF = 'FM_IM' .$idvisita . '_EQ' . $idascensor . '_' . $periodo . '.pdf';

            if ($output == 'browser') {
                $mpdf->Output($PROYECTO_REPORTE_PDF, \Mpdf\Output\Destination::INLINE);
                //$mpdf->Output($PROYECTO_REPORTE_PDF, 'D');
            }
            elseif ($output == 'variable') {
                // capture the output into buffer
                ob_start();
                $mpdf->Output($PROYECTO_REPORTE_PDF, \Mpdf\Output\Destination::INLINE);
                //$mpdf->Output($PROYECTO_REPORTE_PDF, 'D');

                // holds the buffer into a variable
                $html = ob_get_contents(); 
                ob_get_clean();

                return $html;
            }
        break;
    }
}
?>