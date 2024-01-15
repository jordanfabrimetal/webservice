<?php

session_name('SESS_GSAP');
session_start();


require_once "../modelos/Servicio.php";
require_once "../modelos/Email.php";
require_once "../modelos/Tecnico.php";
require_once '../public/build/lib/PHPMailer/class.phpmailer.php';
require_once '../public/build/lib/PHPMailer/class.smtp.php';
include '../public/build/lib/mpdf/mpdf.php';



$servicio = new Servicio();
$email = new Email();
$tecnico = new Tecnico();


switch ($_GET["op"]) {

    case 'GS':
        $idservicio = isset($_POST["idservicio"]) ? limpiarCadena($_POST["idservicio"]) : "";

        $resp = $servicio->pdf($idservicio);

        $bodypdf = '
	<html>
        <head>
            <meta charset="utf-8">
            <title>Guia de servicio N° ' . $resp["idservicio"] . ' </title>

            <style>
            .invoice-box {
                max-width: 800px;
                margin: auto;
                padding: 30px;
                border: 1px solid #eee;
                box-shadow: 0 0 10px rgba(0, 0, 0, .15);
                font-size: 12px;
                line-height: 12px;
                font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
                color: #555;
            }

            .invoice-box table {
                width: 100%;
                line-height: inherit;
                text-align: left;
            }

            .invoice-box table td {
                padding: 5px;
                vertical-align: top;
            }

            .invoice-box table td.logo {
                font-size: 10px;
                line-height: 11px;
            }

            .invoice-box table td.border {
                border: 1px solid black;
                text-align: center;
                vertical-align: middle;
                line-height: 24px;
                font-size: 18px;
            }

            .invoice-box table tr td:nth-child(2) {
                text-align: center;
            }

            .invoice-box table tr.top table td {
                padding-bottom: 20px;
            }

            .invoice-box table tr.top table td.title {
                font-size: 45px;
                line-height: 45px;
                color: #333;
            }

            .invoice-box table tr.information table td {
                padding-bottom: 40px;
                border: 1px solid black;

            }

            .invoice-box table tr.heading td {
                background: #eee;
                border-bottom: 1px solid #ddd;
                font-weight: bold;
                max-width: 800px;
            }

            .invoice-box table tr.details td {
                padding-bottom: 20px;
                max-width: 800px;
            }

            .invoice-box table tr.item td{
                border-bottom: 1px solid #eee;
            }

            .invoice-box table tr.item.last td {
                border-bottom: none;
            }

            .invoice-box table tr.total td:nth-child(2) {
                border-top: 2px solid #eee;
                font-weight: bold;
            }

            @media only screen and (max-width: 600px) {
                .invoice-box table tr.top table td {
                    width: 100%;
                    display: block;
                    text-align: center;
                }

                .invoice-box table tr.information table td {
                    width: 100%;
                    display: block;
                    text-align: center;
                }
            }

            /** RTL **/
            .rtl {
                direction: rtl;
                font-family: Tahoma, "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
            }

            .rtl table {
                text-align: right;
            }

            .rtl table tr td:nth-child(2) {
                text-align: left;
            }
            </style>
        </head>

        <body>
            <div class="invoice-box">
                <table cellpadding="0" cellspacing="0">
                    <tr class="top">
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td class="logo">
                                        <img src="data:image/jpeg;base64,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" style="width:100%; max-width:300px;"><br>
                                        <p>RUT: 85.233.500-9</p>
                                        <p>GIRO: FABRICACIONES METALICAS ESPECIALES</p>
                                        <p>DIRECCION: VOLCAN LASCAR 818, PARQUE IND. LO BOZA - PUDAHUEL - SANTIAGO</p>
                                        <p>TELEFONO: 29493900</p>
                                        <p>PAGINA WEB: WWW.FABRIMETAL.CL</p>
                                    </td>

                                    <td class="border">
                                        ' . $resp["ini"] . '<br>
                                        <b>SERVICIO N°</b><br>
                                        <b>' . $resp["idservicio"] . '</b>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <br>
                    <tr class="heading">
                        <td colspan="2">
                            INFORMACION DEL EDIFICIO
                        </td>            
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>NOMBRE: </b> ' . $resp["edi"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>DIRECCION: </b> ' . $resp["calle"] . ' ' . $resp["numero"] . ', ' . $resp["comuna"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>REGION: </b>' . $resp["region"] . '
                        </td>
                    </tr>
                    
                    <br>

                    <tr class="heading">
                        <td colspan="2">
                            INFORMACION DEL SERVICIO
                        </td>            
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>EQUIPO: </b> ' . $resp["tascen"] . ' - ' . $resp["marca"] . ' ' . $resp["modelo"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>CODIGO FM: </b> ' . $resp["codigo"] . '
                        </td>
                    </tr>
                    <tr class="item">
                        <td colspan="2">
                            <b>CODIGO CLIENTE: </b>  ' . $resp["ubicacion"] . ' ' . $resp["codigocli"] . '
                        </td>
                    </tr>
                    
                    <tr class="item">
                        <td colspan="2">
                            <b>TECNICO: </b> ' . $resp["nomtec"] . ' ' . $resp["apetec"] . ' |  <b> RUT: </b> ' . $resp["ruttec"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>TIPO DE SERVICIO: </b> ' . $resp["tser"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>FECHA Y HORA DEL SERVICIO: </b> ' . $resp["ini"] . '
                        </td>
                    </tr>
 
                    <tr class="item">
                        <td colspan="2">
                            <b>OBSERVACION AL INICIAR SERVICIO: </b> ' . $resp["observacionini"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>ESTADO DEL EQUIPO AL FINALIZAR: </b> ' . $resp["esfin"] . '
                        </td>
                    </tr>
                    
                    <tr class="item">
                        <td colspan="2">
                            <b>OBSERVACION AL FINALIZAR SERVICIO: </b> ' . $resp["observacionfin"] . '
                        </td>
                    </tr>
                    <br>
                    <tr class="heading">
                        <td colspan="2">
                            APROBACION DEL SERVICIO
                        </td>            
                    </tr>';

        if ($resp["reqfirma"] == '0') {

            $bodypdf .= '<tr class="item">
                                        <td colspan="2">
                                            <b>NO REQUIERE FIRMA</b>
                                        </td>
                                    </tr>';
        } else {
            if (is_null($resp["filefir"])) {
                $bodypdf .= '<tr class="item">
                                        <td colspan="2">
                                            <b>NOMBRES Y APELLIDOS: </b> ' . $resp["nomvali"] . ' ' . $resp["apevali"] . '
                                        </td>
                                    </tr>
                                    <tr class="item">
                                        <td colspan="2">
                                            <b>RUT: </b>  ' . $resp["rutvali"] . '
                                        </td>
                                    </tr>
                                    <tr class="item">
                                        <td colspan="2">
                                            <b>FIRMA: </b> ERROR AL CARGAR LA FIRMA
                                        </td>
                                    </tr>';
            } else {
                $bodypdf .= '<tr class="item">
                                        <td colspan="2">
                                            <b>NOMBRES Y APELLIDOS: </b> ' . $resp["nomvali"] . ' ' . $resp["apevali"] . '
                                        </td>
                                    </tr>
                                    <tr class="item">
                                        <td colspan="2">
                                            <b>RUT: </b>  ' . $resp["rutvali"] . '
                                        </td>
                                    </tr>
                                    <tr class="item">
                                        <td colspan="2">
                                            <b>FIRMA: </b><br>
                                            <img src="../files/firma/' . $resp["filefir"] . '" style="width:100%; max-width:150px;"><br>
                                        </td>
                                    </tr>';
            }
        }

        $bodypdf .= '
                    <br>
                    <tr class="top">
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td class="logofooter" align="right">
                                        <img src="data:image/jpeg;base64,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" style="width:100%; max-width:150px;"><br>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </body>
        </html>
        ';

        $guiaPDF = new mPDF('c');

        $guiaPDF->WriteHTML($bodypdf);
        $archivo = round(microtime(true)) . ".pdf";
        $rpdf = $guiaPDF->Output('../files/pdf/' . $archivo, 'F');
        $servicio->UpFile($archivo, $idservicio);
        $resp = $servicio->email($idservicio);

        $body = '
	<html>
        <head>
            <meta charset="utf-8">
            <title>Guia de servicio N° ' . $resp["idservicio"] . ' </title>

            <style>
            .invoice-box {
                max-width: 800px;
                margin: auto;
                padding: 30px;
                border: 1px solid #eee;
                box-shadow: 0 0 10px rgba(0, 0, 0, .15);
                font-size: 12px;
                line-height: 12px;
                font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
                color: #555;
            }

            .invoice-box table {
                width: 100%;
                line-height: inherit;
                text-align: left;
            }

            .invoice-box table td {
                padding: 5px;
                vertical-align: top;
            }

            .invoice-box table td.logo {
                font-size: 10px;
                line-height: 4px;
            }

            .invoice-box table td.border {
                border: 1px solid black;
                text-align: center;
                vertical-align: middle;
                line-height: 24px;
                font-size: 18px;
            }

            .invoice-box table tr td:nth-child(2) {
                text-align: center;
            }

            .invoice-box table tr.top table td {
                padding-bottom: 20px;
            }

            .invoice-box table tr.top table td.title {
                font-size: 45px;
                line-height: 45px;
                color: #333;
            }

            .invoice-box table tr.information table td {
                padding-bottom: 40px;
                border: 1px solid black;

            }

            .invoice-box table tr.heading td {
                background: #eee;
                border-bottom: 1px solid #ddd;
                font-weight: bold;
            }

            .invoice-box table tr.details td {
                padding-bottom: 20px;
            }

            .invoice-box table tr.item td{
                border-bottom: 1px solid #eee;
            }

            .invoice-box table tr.item.last td {
                border-bottom: none;
            }

            .invoice-box table tr.total td:nth-child(2) {
                border-top: 2px solid #eee;
                font-weight: bold;
            }

            @media only screen and (max-width: 600px) {
                .invoice-box table tr.top table td {
                    width: 100%;
                    display: block;
                    text-align: center;
                }

                .invoice-box table tr.information table td {
                    width: 100%;
                    display: block;
                    text-align: center;
                }
            }

            /** RTL **/
            .rtl {
                direction: rtl;
                font-family: Tahoma, "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
            }

            .rtl table {
                text-align: right;
            }

            .rtl table tr td:nth-child(2) {
                text-align: left;
            }
            </style>
        </head>

        <body>
            <div class="invoice-box">
                <table cellpadding="0" cellspacing="0">
                    <tr class="top">
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td class="title">
                                        <img src="data:image/jpeg;base64,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" style="width:100%; max-width:300px;"><br><br>
                                        SERVICIO N°<b> ' . $resp["idservicio"] . '</b><br>
                                    </td>              
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <br>
                    <br>
                    <tr class="heading">
                        <td>
                            INFORMACION DEL EDIFICIO
                        </td>            
                    </tr>

                    <tr class="item">
                        <td>
                            <b>SEGMENTO: </b>' . $resp["segmen"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>NOMBRE: </b> ' . $resp["edi"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>DIRECCION: </b> ' . $resp["calle"] . ' ' . $resp["numero"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>REGION: </b>' . $resp["region"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>COMUNA: </b>' . $resp["comuna"] . '
                        </td>
                    </tr>

                    <br>    
                    <br>

                    <tr class="heading">
                        <td>
                            INFORMACION DEL SERVICIO
                        </td>            
                    </tr>

                    <tr class="item">
                        <td>
                            <b>EQUIPO: </b> ' . $resp["marca"] . ' ' . $resp["modelo"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>TIPO DE EQUIPO: </b> ' . $resp["tascen"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>CODIGO FM: </b> ' . $resp["codigo"] . '
                        </td>
                    </tr>
                    
                    <tr class="item">
                        <td>
                            <b>IDENTIFICACION CLIENTE: </b> ' . $resp["ubicacion"] . ' ' . $resp["codigocli"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>TECNICO: </b> ' . $resp["nomtec"] . ' ' . $resp["apetec"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>RUT: </b> ' . $resp["ruttec"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>CARGO: </b>' . $resp["cartec"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>TIPO DE SERVICIO: </b> ' . $resp["tser"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>FECHA Y HORA DEL SERVICIO: </b> ' . $resp["ini"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>ESTADO DEL EQUIPO AL INICIAR: </b> ' . $resp["esini"] . '
                        </td>
                    </tr>
                    
                    <tr class="item">
                        <td>
                            <b>OBSERVACION AL INICIAR: </b> ' . $resp["observacionini"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>ESTADO DEL EQUIPO AL FINALIZAR: </b> ' . $resp["esfin"] . '
                        </td>
                    </tr>
                    <tr class="item">
                        <td>
                            <b>OBSERVACION AL FINALIZAR: </b> ' . $resp["observacionfin"] . '
                        </td>
                    </tr>
                    <tr class="heading">
                        <td>
                           APROBACION DEL SERVICIO
                        </td>            
                    </tr>';

        if ($resp["reqfirma"] == '3') {

            $body .= '  <tr class="item">
                                <td>
                                    <b>NO REQUIERE FIRMA</b>
                                </td>
                            </tr>';
        } else {
            $body .= '<tr class="item">
                                <td>
                                    <b>NOMBRES: </b> ' . $resp["nomvali"] . '
                                </td>
                            </tr>
                            <tr class="item">
                                <td>
                                    <b>APELLIDOS: </b> ' . $resp["apevali"] . '
                                </td>
                            </tr>
                            <tr class="item">
                                <td>
                                    <b>RUT: </b> ' . $resp["rutvali"] . '
                                </td>
                            </tr>
                            <tr class="item">
                                <td colspan="2">
                                    <b>FIRMA: </b><br>
                                    <img src="' . $resp["firma"] . '" style="width:100%; max-width:150px;"><br>
                                </td>
                            </tr>';
        }

        $body .= '
                    <br>
                    <br>
                    <tr class="top">
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td class="logofooter" align="right">
                                        <img src="data:image/jpeg;base64,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" style="width:100%; max-width:150px;"><br>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </body>
        </html>
        ';

        $Mailer = new PHPMailer();
        $Mailer->isSMTP();
        $Mailer->CharSet = 'UTF-8';
        $Mailer->Port = 587;
        $Mailer->SMTPAuth = true;
        $Mailer->SMTPSecure = "tls";
        $Mailer->SMTPDebug = 0;
        $Mailer->Debugoutput = 'html';
        $Mailer->Host = "mail.fabrimetalsa.cl";
        $Mailer->Username = "notificaciones@fabrimetalsa.cl";
        $Mailer->Password = "*RUJQtbV!wK*";
        $Mailer->From = "notificaciones@fabrimetalsa.cl";
        $Mailer->FromName = "Sistema guía de servicio -> Fabrimetal";
        $Mailer->Subject = "Copia - Guía de servicio N° " . $resp["idservicio"];
        //$Mailer->AddAttachment('../files/pdf/'.$resp["file"], $name = $resp["file"],  $encoding = 'base64', $type = 'application/pdf');
        $Mailer->addAttachment('../files/pdf/' . $resp["file"]);
        $Mailer->msgHTML($body);



        $rspta = $email->email($idservicio);

        while ($reg = $rspta->fetch_object()) {
            $Mailer->addAddress($reg->email, '');
        }
//        
//        $respemail = $tecnico->Email($idserviciofi);
//        $Mailer->addAddress($respemail["email"], '');
//            
//        $respsup = $tecnico->EmailSup($idserviciofi);
//        $Mailer->addAddress($respsup["email"], '');
//        
//        $Mailer->addAddress("ecarrero.fabrimetal@gmail.com",'');
        $Mailer->addAddress("omaldonado@fabrimetal.cl", '');
//        $Mailer->addAddress("yubillo@fabrimetal.cl",'');



        if (!$Mailer->send()) {
            echo "Mailer: " . $Mailer->ErrorInfo;
        } else {
            echo "Correo enviado exitosamente";
        }

        break;


    //Servicio para enviar correo sin firma todavia formato viejo
    case 'GSS':
        $idservicio = isset($_POST["idservicio"]) ? limpiarCadena($_POST["idservicio"]) : "";

        $respfir = $servicio->camfirma($idservicio);
        $encoded_image = explode(",", $respfir["firma"])[1];
        $decoded_image = base64_decode($encoded_image);
        $imgfirma = round(microtime(true)) . ".png";
        //file_put_contents('../files/firmas/'.$imgfirma, $decoded_image);
        $patchfir = "../files/firma/" . $imgfirma;
        file_put_contents($patchfir, $decoded_image);
        $image = imagecreatefrompng('../files/firma/' . $imgfirma);
        $imgjpg = round(microtime(true)) . ".jpg";
        imagejpeg($image, '../files/firma/' . $imgjpg, 100);
        imagedestroy($image);
        $servicio->UpFirma($imgjpg, $idservicio);

        $resp = $servicio->pdf($idservicio);

        $bodypdf = '
	<html>
        <head>
            <meta charset="utf-8">
            <title>Guia de servicio N° ' . $resp["idservicio"] . ' </title>

            <style>
            .invoice-box {
                max-width: 800px;
                margin: auto;
                padding: 30px;
                border: 1px solid #eee;
                box-shadow: 0 0 10px rgba(0, 0, 0, .15);
                font-size: 12px;
                line-height: 12px;
                font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
                color: #555;
            }

            .invoice-box table {
                width: 100%;
                line-height: inherit;
                text-align: left;
            }

            .invoice-box table td {
                padding: 5px;
                vertical-align: top;
            }

            .invoice-box table td.logo {
                font-size: 10px;
                line-height: 11px;
            }

            .invoice-box table td.border {
                border: 1px solid black;
                text-align: center;
                vertical-align: middle;
                line-height: 24px;
                font-size: 18px;
            }

            .invoice-box table tr td:nth-child(2) {
                text-align: center;
            }

            .invoice-box table tr.top table td {
                padding-bottom: 20px;
            }

            .invoice-box table tr.top table td.title {
                font-size: 45px;
                line-height: 45px;
                color: #333;
            }

            .invoice-box table tr.information table td {
                padding-bottom: 40px;
                border: 1px solid black;

            }

            .invoice-box table tr.heading td {
                background: #eee;
                border-bottom: 1px solid #ddd;
                font-weight: bold;
                max-width: 800px;
            }

            .invoice-box table tr.details td {
                padding-bottom: 20px;
                max-width: 800px;
            }

            .invoice-box table tr.item td{
                border-bottom: 1px solid #eee;
            }

            .invoice-box table tr.item.last td {
                border-bottom: none;
            }

            .invoice-box table tr.total td:nth-child(2) {
                border-top: 2px solid #eee;
                font-weight: bold;
            }

            @media only screen and (max-width: 600px) {
                .invoice-box table tr.top table td {
                    width: 100%;
                    display: block;
                    text-align: center;
                }

                .invoice-box table tr.information table td {
                    width: 100%;
                    display: block;
                    text-align: center;
                }
            }

            /** RTL **/
            .rtl {
                direction: rtl;
                font-family: Tahoma, "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
            }

            .rtl table {
                text-align: right;
            }

            .rtl table tr td:nth-child(2) {
                text-align: left;
            }
            </style>
        </head>

        <body>
            <div class="invoice-box">
                <table cellpadding="0" cellspacing="0">
                    <tr class="top">
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td class="logo">
                                        <img src="data:image/jpeg;base64,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" style="width:100%; max-width:300px;"><br>
                                        <p>RUT: 85.233.500-9</p>
                                        <p>GIRO: FABRICACIONES METALICAS ESPECIALES</p>
                                        <p>DIRECCION: VOLCAN LASCAR 818, PARQUE IND. LO BOZA - PUDAHUEL - SANTIAGO</p>
                                        <p>TELEFONO: 29493900</p>
                                        <p>PAGINA WEB: WWW.FABRIMETAL.CL</p>
                                    </td>

                                    <td class="border">
                                        ' . $resp["ini"] . '<br>
                                        <b>SERVICIO N°</b><br>
                                        <b>' . $resp["idservicio"] . '</b>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <br>
                    <tr class="heading">
                        <td colspan="2">
                            INFORMACION DEL EDIFICIO
                        </td>            
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>NOMBRE: </b> ' . $resp["edi"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>DIRECCION: </b> ' . $resp["calle"] . ' ' . $resp["numero"] . ', ' . $resp["comuna"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>REGION: </b>' . $resp["region"] . '
                        </td>
                    </tr>
                    
                    <br>

                    <tr class="heading">
                        <td colspan="2">
                            INFORMACION DEL SERVICIO
                        </td>            
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>EQUIPO: </b> ' . $resp["tascen"] . ' - ' . $resp["marca"] . ' ' . $resp["modelo"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>CODIGO FM: </b> ' . $resp["codigo"] . '
                        </td>
                    </tr>
                    <tr class="item">
                        <td colspan="2">
                            <b>CODIGO CLIENTE: </b>  ' . $resp["ubicacion"] . ' ' . $resp["codigocli"] . '
                        </td>
                    </tr>
                    
                    <tr class="item">
                        <td colspan="2">
                            <b>TECNICO: </b> ' . $resp["nomtec"] . ' ' . $resp["apetec"] . ' |  <b> RUT: </b> ' . $resp["ruttec"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>TIPO DE SERVICIO: </b> ' . $resp["tser"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>FECHA Y HORA DEL SERVICIO: </b> ' . $resp["ini"] . '
                        </td>
                    </tr>
 
                    <tr class="item">
                        <td colspan="2">
                            <b>OBSERVACION AL INICIAR SERVICIO: </b> ' . $resp["observacionini"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>ESTADO DEL EQUIPO AL FINALIZAR: </b> ' . $resp["esfin"] . '
                        </td>
                    </tr>
                    
                    <tr class="item">
                        <td colspan="2">
                            <b>OBSERVACION AL FINALIZAR SERVICIO: </b> ' . $resp["observacionfin"] . '
                        </td>
                    </tr>
                    <br>
                    <tr class="heading">
                        <td colspan="2">
                            APROBACION DEL SERVICIO
                        </td>            
                    </tr>';

        if ($resp["reqfirma"] == '0') {

            $bodypdf .= '<tr class="item">
                                        <td colspan="2">
                                            <b>NO REQUIERE FIRMA</b>
                                        </td>
                                    </tr>';
        } else {
            if (is_null($resp["filefir"])) {
                $bodypdf .= '<tr class="item">
                                        <td colspan="2">
                                            <b>NOMBRES Y APELLIDOS: </b> ' . $resp["nomvali"] . ' ' . $resp["apevali"] . '
                                        </td>
                                    </tr>
                                    <tr class="item">
                                        <td colspan="2">
                                            <b>RUT: </b>  ' . $resp["rutvali"] . '
                                        </td>
                                    </tr>
                                    <tr class="item">
                                        <td colspan="2">
                                            <b>FIRMA: </b> ERROR AL CARGAR LA FIRMA
                                        </td>
                                    </tr>';
            } else {
                $bodypdf .= '<tr class="item">
                                        <td colspan="2">
                                            <b>NOMBRES Y APELLIDOS: </b> ' . $resp["nomvali"] . ' ' . $resp["apevali"] . '
                                        </td>
                                    </tr>
                                    <tr class="item">
                                        <td colspan="2">
                                            <b>RUT: </b>  ' . $resp["rutvali"] . '
                                        </td>
                                    </tr>
                                    <tr class="item">
                                        <td colspan="2">
                                            <b>FIRMA: </b><br>
                                            <img src="../files/firma/' . $resp["filefir"] . '" style="width:100%; max-width:150px;"><br>
                                        </td>
                                    </tr>';
            }
        }

        $bodypdf .= '
                    <br>
                    <tr class="top">
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td class="logofooter" align="right">
                                        <img src="data:image/jpeg;base64,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" style="width:100%; max-width:150px;"><br>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </body>
        </html>
        ';

        $guiaPDF = new mPDF('c');

        $guiaPDF->WriteHTML($bodypdf);
        $archivo = round(microtime(true)) . ".pdf";
        $rpdf = $guiaPDF->Output('../files/pdf/' . $archivo, 'F');
        $servicio->UpFile($archivo, $idservicio);
        $resp = $servicio->email($idservicio);

        $body = '
	<html>
        <head>
            <meta charset="utf-8">
            <title>Guia de servicio N° ' . $resp["idservicio"] . ' </title>

            <style>
            .invoice-box {
                max-width: 800px;
                margin: auto;
                padding: 30px;
                border: 1px solid #eee;
                box-shadow: 0 0 10px rgba(0, 0, 0, .15);
                font-size: 12px;
                line-height: 12px;
                font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
                color: #555;
            }

            .invoice-box table {
                width: 100%;
                line-height: inherit;
                text-align: left;
            }

            .invoice-box table td {
                padding: 5px;
                vertical-align: top;
            }

            .invoice-box table td.logo {
                font-size: 10px;
                line-height: 4px;
            }

            .invoice-box table td.border {
                border: 1px solid black;
                text-align: center;
                vertical-align: middle;
                line-height: 24px;
                font-size: 18px;
            }

            .invoice-box table tr td:nth-child(2) {
                text-align: center;
            }

            .invoice-box table tr.top table td {
                padding-bottom: 20px;
            }

            .invoice-box table tr.top table td.title {
                font-size: 45px;
                line-height: 45px;
                color: #333;
            }

            .invoice-box table tr.information table td {
                padding-bottom: 40px;
                border: 1px solid black;

            }

            .invoice-box table tr.heading td {
                background: #eee;
                border-bottom: 1px solid #ddd;
                font-weight: bold;
            }

            .invoice-box table tr.details td {
                padding-bottom: 20px;
            }

            .invoice-box table tr.item td{
                border-bottom: 1px solid #eee;
            }

            .invoice-box table tr.item.last td {
                border-bottom: none;
            }

            .invoice-box table tr.total td:nth-child(2) {
                border-top: 2px solid #eee;
                font-weight: bold;
            }

            @media only screen and (max-width: 600px) {
                .invoice-box table tr.top table td {
                    width: 100%;
                    display: block;
                    text-align: center;
                }

                .invoice-box table tr.information table td {
                    width: 100%;
                    display: block;
                    text-align: center;
                }
            }

            /** RTL **/
            .rtl {
                direction: rtl;
                font-family: Tahoma, "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
            }

            .rtl table {
                text-align: right;
            }

            .rtl table tr td:nth-child(2) {
                text-align: left;
            }
            </style>
        </head>

        <body>
            <div class="invoice-box">
                <table cellpadding="0" cellspacing="0">
                    <tr class="top">
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td class="title">
                                        <img src="data:image/jpeg;base64,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" style="width:100%; max-width:300px;"><br><br>
                                        SERVICIO N°<b> ' . $resp["idservicio"] . '</b><br>
                                    </td>              
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <br>
                    <br>
                    <tr class="heading">
                        <td>
                            INFORMACION DEL EDIFICIO
                        </td>            
                    </tr>

                    <tr class="item">
                        <td>
                            <b>SEGMENTO: </b>' . $resp["segmen"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>NOMBRE: </b> ' . $resp["edi"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>DIRECCION: </b> ' . $resp["calle"] . ' ' . $resp["numero"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>REGION: </b>' . $resp["region"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>COMUNA: </b>' . $resp["comuna"] . '
                        </td>
                    </tr>

                    <br>    
                    <br>

                    <tr class="heading">
                        <td>
                            INFORMACION DEL SERVICIO
                        </td>            
                    </tr>

                    <tr class="item">
                        <td>
                            <b>EQUIPO: </b> ' . $resp["marca"] . ' ' . $resp["modelo"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>TIPO DE EQUIPO: </b> ' . $resp["tascen"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>CODIGO FM: </b> ' . $resp["codigo"] . '
                        </td>
                    </tr>
                    
                    <tr class="item">
                        <td>
                            <b>IDENTIFICACION CLIENTE: </b> ' . $resp["ubicacion"] . ' ' . $resp["codigocli"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>TECNICO: </b> ' . $resp["nomtec"] . ' ' . $resp["apetec"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>RUT: </b> ' . $resp["ruttec"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>CARGO: </b>' . $resp["cartec"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>TIPO DE SERVICIO: </b> ' . $resp["tser"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>FECHA Y HORA DEL SERVICIO: </b> ' . $resp["ini"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>ESTADO DEL EQUIPO AL INICIAR: </b> ' . $resp["esini"] . '
                        </td>
                    </tr>
                    
                    <tr class="item">
                        <td>
                            <b>OBSERVACION AL INICIAR: </b> ' . $resp["observacionini"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>ESTADO DEL EQUIPO AL FINALIZAR: </b> ' . $resp["esfin"] . '
                        </td>
                    </tr>
                    <tr class="item">
                        <td>
                            <b>OBSERVACION AL FINALIZAR: </b> ' . $resp["observacionfin"] . '
                        </td>
                    </tr>
                    <tr class="heading">
                        <td>
                           APROBACION DEL SERVICIO
                        </td>            
                    </tr>';

        if ($resp["reqfirma"] == '3') {

            $body .= '  <tr class="item">
                                <td>
                                    <b>NO REQUIERE FIRMA</b>
                                </td>
                            </tr>';
        } else {
            $body .= '<tr class="item">
                                <td>
                                    <b>NOMBRES: </b> ' . $resp["nomvali"] . '
                                </td>
                            </tr>
                            <tr class="item">
                                <td>
                                    <b>APELLIDOS: </b> ' . $resp["apevali"] . '
                                </td>
                            </tr>
                            <tr class="item">
                                <td>
                                    <b>RUT: </b> ' . $resp["rutvali"] . '
                                </td>
                            </tr>
                            <tr class="item">
                                <td colspan="2">
                                    <b>FIRMA: </b><br>
                                    <img src="' . $resp["firma"] . '" style="width:100%; max-width:150px;"><br>
                                </td>
                            </tr>';
        }

        $body .= '
                    <br>
                    <br>
                    <tr class="top">
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td class="logofooter" align="right">
                                        <img src="data:image/jpeg;base64,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" style="width:100%; max-width:150px;"><br>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </body>
        </html>
        ';

        $Mailer = new PHPMailer();
        $Mailer->isSMTP();
        $Mailer->CharSet = 'UTF-8';
        $Mailer->Port = 587;
        $Mailer->SMTPAuth = true;
        $Mailer->SMTPSecure = "tls";
        $Mailer->SMTPDebug = 0;
        $Mailer->Debugoutput = 'html';
        $Mailer->Host = "mail.fabrimetalsa.cl";
        $Mailer->Username = "notificaciones@fabrimetalsa.cl";
        $Mailer->Password = "*RUJQtbV!wK*";
        $Mailer->From = "notificaciones@fabrimetalsa.cl";
        $Mailer->FromName = "Sistema guía de servicio -> Fabrimetal";
        $Mailer->Subject = "Copia - Guía de servicio N° " . $resp["idservicio"];
        //$Mailer->AddAttachment('../files/pdf/'.$resp["file"], $name = $resp["file"],  $encoding = 'base64', $type = 'application/pdf');
        $Mailer->addAttachment('../files/pdf/' . $resp["file"]);
        $Mailer->msgHTML($body);



        $rspta = $email->email($idservicio);

        while ($reg = $rspta->fetch_object()) {
            $Mailer->addAddress($reg->email, '');
        }

//        $respemail = $tecnico->Email($idservicio);
//        $Mailer->addAddress($respemail["email"], '');
//        $respsup = $tecnico->EmailSup($idservicio);
//        $Mailer->addAddress($respsup["email"], '');
//        $Mailer->addAddress("ecarrero.fabrimetal@gmail.com",'');
        $Mailer->addAddress("omaldonado@fabrimetal.cl", '');
//        $Mailer->addAddress("yubillo@fabrimetal.cl",'');


        if (!$Mailer->send()) {
            echo "Mailer: " . $Mailer->ErrorInfo;
        } else {
            echo "Correo enviado exitosamente con generacion de firma   ";
        }

        break;

    //Generar solo pdf formato viejo
    case 'GPDF':
        $idservicio = isset($_POST["idservicio"]) ? limpiarCadena($_POST["idservicio"]) : "";

        $respfir = $servicio->camfirma($idservicio);
        $encoded_image = explode(",", $respfir["firma"])[1];
        $decoded_image = base64_decode($encoded_image);
        $imgfirma = round(microtime(true)) . ".png";
        //file_put_contents('../files/firmas/'.$imgfirma, $decoded_image);
        $patchfir = "../files/firma/" . $imgfirma;
        file_put_contents($patchfir, $decoded_image);
        $image = imagecreatefrompng('../files/firma/' . $imgfirma);
        $imgjpg = round(microtime(true)) . ".jpg";
        imagejpeg($image, '../files/firma/' . $imgjpg, 100);
        imagedestroy($image);
        $servicio->UpFirma($imgjpg, $idservicio);

        $resp = $servicio->pdf($idservicio);

        $bodypdf = '
	<html>
        <head>
            <meta charset="utf-8">
            <title>Guia de servicio N° ' . $resp["idservicio"] . ' </title>

            <style>
            .invoice-box {
                max-width: 800px;
                margin: auto;
                padding: 30px;
                border: 1px solid #eee;
                box-shadow: 0 0 10px rgba(0, 0, 0, .15);
                font-size: 12px;
                line-height: 12px;
                font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
                color: #555;
            }

            .invoice-box table {
                width: 100%;
                line-height: inherit;
                text-align: left;
            }

            .invoice-box table td {
                padding: 5px;
                vertical-align: top;
            }

            .invoice-box table td.logo {
                font-size: 10px;
                line-height: 11px;
            }

            .invoice-box table td.border {
                border: 1px solid black;
                text-align: center;
                vertical-align: middle;
                line-height: 24px;
                font-size: 18px;
            }

            .invoice-box table tr td:nth-child(2) {
                text-align: center;
            }

            .invoice-box table tr.top table td {
                padding-bottom: 20px;
            }

            .invoice-box table tr.top table td.title {
                font-size: 45px;
                line-height: 45px;
                color: #333;
            }

            .invoice-box table tr.information table td {
                padding-bottom: 40px;
                border: 1px solid black;

            }

            .invoice-box table tr.heading td {
                background: #eee;
                border-bottom: 1px solid #ddd;
                font-weight: bold;
                max-width: 800px;
            }

            .invoice-box table tr.details td {
                padding-bottom: 20px;
                max-width: 800px;
            }

            .invoice-box table tr.item td{
                border-bottom: 1px solid #eee;
            }

            .invoice-box table tr.item.last td {
                border-bottom: none;
            }

            .invoice-box table tr.total td:nth-child(2) {
                border-top: 2px solid #eee;
                font-weight: bold;
            }

            @media only screen and (max-width: 600px) {
                .invoice-box table tr.top table td {
                    width: 100%;
                    display: block;
                    text-align: center;
                }

                .invoice-box table tr.information table td {
                    width: 100%;
                    display: block;
                    text-align: center;
                }
            }

            /** RTL **/
            .rtl {
                direction: rtl;
                font-family: Tahoma, "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
            }

            .rtl table {
                text-align: right;
            }

            .rtl table tr td:nth-child(2) {
                text-align: left;
            }
            </style>
        </head>

        <body>
            <div class="invoice-box">
                <table cellpadding="0" cellspacing="0">
                    <tr class="top">
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td class="logo">
                                        <img src="data:image/jpeg;base64,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" style="width:100%; max-width:300px;"><br>
                                        <p>RUT: 85.233.500-9</p>
                                        <p>GIRO: FABRICACIONES METALICAS ESPECIALES</p>
                                        <p>DIRECCION: VOLCAN LASCAR 818, PARQUE IND. LO BOZA - PUDAHUEL - SANTIAGO</p>
                                        <p>TELEFONO: 29493900</p>
                                        <p>PAGINA WEB: WWW.FABRIMETAL.CL</p>
                                    </td>

                                    <td class="border">
                                        ' . $resp["ini"] . '<br>
                                        <b>SERVICIO N°</b><br>
                                        <b>' . $resp["idservicio"] . '</b>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <br>
                    <tr class="heading">
                        <td colspan="2">
                            INFORMACION DEL EDIFICIO
                        </td>            
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>NOMBRE: </b> ' . $resp["edi"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>DIRECCION: </b> ' . $resp["calle"] . ' ' . $resp["numero"] . ', ' . $resp["comuna"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>REGION: </b>' . $resp["region"] . '
                        </td>
                    </tr>
                    
                    <br>

                    <tr class="heading">
                        <td colspan="2">
                            INFORMACION DEL SERVICIO
                        </td>            
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>EQUIPO: </b> ' . $resp["tascen"] . ' - ' . $resp["marca"] . ' ' . $resp["modelo"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>CODIGO FM: </b> ' . $resp["codigo"] . '
                        </td>
                    </tr>
                    <tr class="item">
                        <td colspan="2">
                            <b>CODIGO CLIENTE: </b>  ' . $resp["ubicacion"] . ' ' . $resp["codigocli"] . '
                        </td>
                    </tr>
                    
                    <tr class="item">
                        <td colspan="2">
                            <b>TECNICO: </b> ' . $resp["nomtec"] . ' ' . $resp["apetec"] . ' |  <b> RUT: </b> ' . $resp["ruttec"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>TIPO DE SERVICIO: </b> ' . $resp["tser"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>FECHA Y HORA DEL SERVICIO: </b> ' . $resp["ini"] . '
                        </td>
                    </tr>
 
                    <tr class="item">
                        <td colspan="2">
                            <b>OBSERVACION AL INICIAR SERVICIO: </b> ' . $resp["observacionini"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>ESTADO DEL EQUIPO AL FINALIZAR: </b> ' . $resp["esfin"] . '
                        </td>
                    </tr>
                    
                    <tr class="item">
                        <td colspan="2">
                            <b>OBSERVACION AL FINALIZAR SERVICIO: </b> ' . $resp["observacionfin"] . '
                        </td>
                    </tr>
                    <br>
                    <tr class="heading">
                        <td colspan="2">
                            APROBACION DEL SERVICIO
                        </td>            
                    </tr>';

        if ($resp["reqfirma"] == '0') {

            $bodypdf .= '<tr class="item">
                                        <td colspan="2">
                                            <b>NO REQUIERE FIRMA</b>
                                        </td>
                                    </tr>';
        } else {
            if (is_null($resp["filefir"])) {
                $bodypdf .= '<tr class="item">
                                        <td colspan="2">
                                            <b>NOMBRES Y APELLIDOS: </b> ' . $resp["nomvali"] . ' ' . $resp["apevali"] . '
                                        </td>
                                    </tr>
                                    <tr class="item">
                                        <td colspan="2">
                                            <b>RUT: </b>  ' . $resp["rutvali"] . '
                                        </td>
                                    </tr>
                                    <tr class="item">
                                        <td colspan="2">
                                            <b>FIRMA: </b> ERROR AL CARGAR LA FIRMA
                                        </td>
                                    </tr>';
            } else {
                $bodypdf .= '<tr class="item">
                                        <td colspan="2">
                                            <b>NOMBRES Y APELLIDOS: </b> ' . $resp["nomvali"] . ' ' . $resp["apevali"] . '
                                        </td>
                                    </tr>
                                    <tr class="item">
                                        <td colspan="2">
                                            <b>RUT: </b>  ' . $resp["rutvali"] . '
                                        </td>
                                    </tr>
                                    <tr class="item">
                                        <td colspan="2">
                                            <b>FIRMA: </b><br>
                                            <img src="../files/firma/' . $resp["filefir"] . '" style="width:100%; max-width:150px;"><br>
                                        </td>
                                    </tr>';
            }
        }

        $bodypdf .= '
                    <br>
                    <tr class="top">
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td class="logofooter" align="right">
                                        <img src="data:image/jpeg;base64,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" style="width:100%; max-width:150px;"><br>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </body>
        </html>
        ';

        $guiaPDF = new mPDF('c');

        $guiaPDF->WriteHTML($bodypdf);
        $archivo = round(microtime(true)) . ".pdf";
        $rpdf = $guiaPDF->Output('../files/pdf/' . $archivo, 'F');
        $respdf = $servicio->UpFile($archivo, $idservicio);

        if ($respdf) {
            echo "Se genero pdf";
        } else {
            echo "No se pudo generar pdf";
        }

        break;

    //Servicio para enviar correo sin firma todavia formato viejo
    case 'GMES':
        $idedificio = isset($_POST["idedificio"]) ? limpiarCadena($_POST["idedificio"]) : "";
        $mes = isset($_POST["mes"]) ? limpiarCadena($_POST["mes"]) : "";
        $meses = array("ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE");
        $resp = $email->edificio($idedificio);
        $body = '
	<html>
        <head>
            <meta charset="utf-8">
            <title>Guias de servicio / MES de ' . $meses[(intval($mes) - 1)] . ' </title>

            <style>
            .invoice-box {
                max-width: 800px;
                margin: auto;
                padding: 30px;
                border: 1px solid #eee;
                box-shadow: 0 0 10px rgba(0, 0, 0, .15);
                font-size: 12px;
                line-height: 12px;
                font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
                color: #555;
            }

            .invoice-box table {
                width: 100%;
                line-height: inherit;
                text-align: left;
            }

            .invoice-box table td {
                padding: 5px;
                vertical-align: top;
            }

            .invoice-box table td.logo {
                font-size: 10px;
                line-height: 4px;
            }

            .invoice-box table td.border {
                border: 1px solid black;
                text-align: center;
                vertical-align: middle;
                line-height: 24px;
                font-size: 18px;
            }

            .invoice-box table tr td:nth-child(2) {
                text-align: center;
            }

            .invoice-box table tr.top table td {
                padding-bottom: 20px;
            }

            .invoice-box table tr.top table td.title {
                font-size: 45px;
                line-height: 45px;
                color: #333;
            }

            .invoice-box table tr.information table td {
                padding-bottom: 40px;
                border: 1px solid black;

            }

            .invoice-box table tr.heading td {
                background: #eee;
                border-bottom: 1px solid #ddd;
                font-weight: bold;
            }

            .invoice-box table tr.details td {
                padding-bottom: 20px;
            }

            .invoice-box table tr.item td{
                border-bottom: 1px solid #eee;
            }

            .invoice-box table tr.item.last td {
                border-bottom: none;
            }

            .invoice-box table tr.total td:nth-child(2) {
                border-top: 2px solid #eee;
                font-weight: bold;
            }

            @media only screen and (max-width: 600px) {
                .invoice-box table tr.top table td {
                    width: 100%;
                    display: block;
                    text-align: center;
                }

                .invoice-box table tr.information table td {
                    width: 100%;
                    display: block;
                    text-align: center;
                }
            }

            /** RTL **/
            .rtl {
                direction: rtl;
                font-family: Tahoma, "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
            }

            .rtl table {
                text-align: right;
            }

            .rtl table tr td:nth-child(2) {
                text-align: left;
            }
            </style>
        </head>

        <body>
            <div class="invoice-box">
                <table cellpadding="0" cellspacing="0">
                    <tr class="top">
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td class="title">
                                        <img src="data:image/jpeg;base64,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" style="width:100%; max-width:300px;"><br><br>
                                        SERVICIOS DEL MES DE <b> ' . $meses[(intval($mes) - 1)] . '</b><br>
                                    </td>              
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <br>
                    <br>
                    <tr class="heading">
                        <td>
                            INFORMACION DEL EDIFICIO
                        </td>            
                    </tr>

                    <tr class="item">
                        <td>
                            <b>SEGMENTO: </b>' . $resp["segmen"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>NOMBRE: </b> ' . $resp["edi"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>DIRECCION: </b> ' . $resp["calle"] . ' ' . $resp["numero"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>REGION: </b>' . $resp["region"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>COMUNA: </b>' . $resp["comuna_nombre"] . '
                        </td>
                    </tr>

                    <br>    
                    <br>

                    <tr class="heading">
                        <td>
                            INFORMACION DEL SERVICIOS
                        </td>            
                    </tr>

                    <tr class="item">
                        <td>
                            <b>MES: </b> ' . $meses[(intval($mes) - 1)] . '
                        </td>
                    </tr>';

        $body .= '
                    <br>
                    <br>
                    <tr class="top">
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td class="logofooter" align="right">
                                        <img src="data:image/jpeg;base64,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" style="width:100%; max-width:150px;"><br>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </body>
        </html>
        ';

        $Mailer = new PHPMailer();
        $Mailer->isSMTP();
        $Mailer->CharSet = 'UTF-8';
        $Mailer->Port = 587;
        $Mailer->SMTPAuth = true;
        $Mailer->SMTPSecure = "tls";
        $Mailer->SMTPDebug = 0;
        $Mailer->Debugoutput = 'html';
        $Mailer->Host = "mail.fabrimetalsa.cl";
        $Mailer->Username = "notificaciones@fabrimetalsa.cl";
        $Mailer->Password = "*RUJQtbV!wK*";
        $Mailer->From = "notificaciones@fabrimetalsa.cl";
        $Mailer->FromName = "Sistema guía de servicio -> Fabrimetal";
        $Mailer->Subject = "Copia - Guías de servicio del Mes de " . $meses[(intval($mes) - 1)];
        //$Mailer->AddAttachment('../files/pdf/'.$resp["file"], $name = $resp["file"],  $encoding = 'base64', $type = 'application/pdf');

        $pdfs = $email->pdfs($idedificio, $mes);
        while ($reg = $pdfs->fetch_object()) {
            $Mailer->addAttachment('../files/pdf/' . $reg->file);
        }

        $Mailer->msgHTML($body);

        $rspta = $email->emailedif($idedificio);
        while ($reg = $rspta->fetch_object()) {
            $Mailer->addAddress($reg->email, '');
        }

        $Mailer->addAddress("ecarrero.fabrimetal@gmail.com", '');
        $Mailer->addAddress("omaldonado@fabrimetal.cl", '');





        if (!$Mailer->send()) {
            echo "Mailer: " . $Mailer->ErrorInfo;
        } else {
            echo "Correo enviado con guias del MES de " . $meses[(intval($mes) - 1)];
        }

        break;

    case 'GPDFMESANO':
        $idedificio = isset($_POST["idedificio"]) ? limpiarCadena($_POST["idedificio"]) : "";
        $mes = isset($_POST["mes"]) ? limpiarCadena($_POST["mes"]) : "";
        $ano = isset($_POST["ano"]) ? limpiarCadena($_POST["ano"]) : "";
        $meses = array("ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE");

        if (!empty($idedificio) && !empty($mes) && !empty($ano)) {
            $pdfs = $email->gseanomes($idedificio, $mes, $ano);
            $i = 0;
            while ($reg = $pdfs->fetch_object()) {
                $respfir = $servicio->camfirma($reg->idservicio);
                $encoded_image = explode(",", $respfir["firma"])[1];
                $decoded_image = base64_decode($encoded_image);
                $imgfirma = $i . "" . round(microtime(true)) . ".png";
                //file_put_contents('../files/firmas/'.$imgfirma, $decoded_image);
                $patchfir = "../files/firma/" . $imgfirma;
                file_put_contents($patchfir, $decoded_image);
                $image = imagecreatefrompng('../files/firma/' . $imgfirma);
                $imgjpg = round(microtime(true)) . ".jpg";
                imagejpeg($image, '../files/firma/' . $imgjpg, 100);
                imagedestroy($image);
                $servicio->UpFirma($imgjpg, $reg->idservicio);

                $resp = $servicio->pdf($reg->idservicio);

                $bodypdf = '
	<html>
        <head>
            <meta charset="utf-8">
            <title>Guia de servicio N° ' . $resp["idservicio"] . ' </title>

            <style>
            .invoice-box {
                max-width: 800px;
                margin: auto;
                padding: 30px;
                border: 1px solid #eee;
                box-shadow: 0 0 10px rgba(0, 0, 0, .15);
                font-size: 12px;
                line-height: 12px;
                font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
                color: #555;
            }

            .invoice-box table {
                width: 100%;
                line-height: inherit;
                text-align: left;
            }

            .invoice-box table td {
                padding: 5px;
                vertical-align: top;
            }

            .invoice-box table td.logo {
                font-size: 10px;
                line-height: 11px;
            }

            .invoice-box table td.border {
                border: 1px solid black;
                text-align: center;
                vertical-align: middle;
                line-height: 24px;
                font-size: 18px;
            }

            .invoice-box table tr td:nth-child(2) {
                text-align: center;
            }

            .invoice-box table tr.top table td {
                padding-bottom: 20px;
            }

            .invoice-box table tr.top table td.title {
                font-size: 45px;
                line-height: 45px;
                color: #333;
            }

            .invoice-box table tr.information table td {
                padding-bottom: 40px;
                border: 1px solid black;

            }

            .invoice-box table tr.heading td {
                background: #eee;
                border-bottom: 1px solid #ddd;
                font-weight: bold;
                max-width: 800px;
            }

            .invoice-box table tr.details td {
                padding-bottom: 20px;
                max-width: 800px;
            }

            .invoice-box table tr.item td{
                border-bottom: 1px solid #eee;
            }

            .invoice-box table tr.item.last td {
                border-bottom: none;
            }

            .invoice-box table tr.total td:nth-child(2) {
                border-top: 2px solid #eee;
                font-weight: bold;
            }

            @media only screen and (max-width: 600px) {
                .invoice-box table tr.top table td {
                    width: 100%;
                    display: block;
                    text-align: center;
                }

                .invoice-box table tr.information table td {
                    width: 100%;
                    display: block;
                    text-align: center;
                }
            }

            /** RTL **/
            .rtl {
                direction: rtl;
                font-family: Tahoma, "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
            }

            .rtl table {
                text-align: right;
            }

            .rtl table tr td:nth-child(2) {
                text-align: left;
            }
            </style>
        </head>

        <body>
            <div class="invoice-box">
                <table cellpadding="0" cellspacing="0">
                    <tr class="top">
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td class="logo">
                                        <img src="data:image/jpeg;base64,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" style="width:100%; max-width:300px;"><br>
                                        <p>RUT: 85.233.500-9</p>
                                        <p>GIRO: FABRICACIONES METALICAS ESPECIALES</p>
                                        <p>DIRECCION: VOLCAN LASCAR 818, PARQUE IND. LO BOZA - PUDAHUEL - SANTIAGO</p>
                                        <p>TELEFONO: 29493900</p>
                                        <p>PAGINA WEB: WWW.FABRIMETAL.CL</p>
                                    </td>

                                    <td class="border">
                                        ' . $resp["ini"] . '<br>
                                        <b>SERVICIO N°</b><br>
                                        <b>' . $resp["idservicio"] . '</b>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <br>
                    <tr class="heading">
                        <td colspan="2">
                            INFORMACION DEL EDIFICIO
                        </td>            
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>NOMBRE: </b> ' . $resp["edi"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>DIRECCION: </b> ' . $resp["calle"] . ' ' . $resp["numero"] . ', ' . $resp["comuna"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>REGION: </b>' . $resp["region"] . '
                        </td>
                    </tr>
                    
                    <br>

                    <tr class="heading">
                        <td colspan="2">
                            INFORMACION DEL SERVICIO
                        </td>            
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>EQUIPO: </b> ' . $resp["tascen"] . ' - ' . $resp["marca"] . ' ' . $resp["modelo"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>CODIGO FM: </b> ' . $resp["codigo"] . '
                        </td>
                    </tr>
                    <tr class="item">
                        <td colspan="2">
                            <b>CODIGO CLIENTE: </b>  ' . $resp["ubicacion"] . ' ' . $resp["codigocli"] . '
                        </td>
                    </tr>
                    
                    <tr class="item">
                        <td colspan="2">
                            <b>TECNICO: </b> ' . $resp["nomtec"] . ' ' . $resp["apetec"] . ' |  <b> RUT: </b> ' . $resp["ruttec"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>TIPO DE SERVICIO: </b> ' . $resp["tser"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>FECHA Y HORA DEL SERVICIO: </b> ' . $resp["ini"] . '
                        </td>
                    </tr>
 
                    <tr class="item">
                        <td colspan="2">
                            <b>OBSERVACION AL INICIAR SERVICIO: </b> ' . $resp["observacionini"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>ESTADO DEL EQUIPO AL FINALIZAR: </b> ' . $resp["esfin"] . '
                        </td>
                    </tr>
                    
                    <tr class="item">
                        <td colspan="2">
                            <b>OBSERVACION AL FINALIZAR SERVICIO: </b> ' . $resp["observacionfin"] . '
                        </td>
                    </tr>
                    <br>
                    <tr class="heading">
                        <td colspan="2">
                            APROBACION DEL SERVICIO
                        </td>            
                    </tr>';

                if ($resp["reqfirma"] == '0') {

                    $bodypdf .= '<tr class="item">
                                        <td colspan="2">
                                            <b>NO REQUIERE FIRMA</b>
                                        </td>
                                    </tr>';
                } else {
                    if (is_null($resp["filefir"])) {
                        $bodypdf .= '<tr class="item">
                                        <td colspan="2">
                                            <b>NOMBRES Y APELLIDOS: </b> ' . $resp["nomvali"] . ' ' . $resp["apevali"] . '
                                        </td>
                                    </tr>
                                    <tr class="item">
                                        <td colspan="2">
                                            <b>RUT: </b>  ' . $resp["rutvali"] . '
                                        </td>
                                    </tr>
                                    <tr class="item">
                                        <td colspan="2">
                                            <b>FIRMA: </b> ERROR AL CARGAR LA FIRMA
                                        </td>
                                    </tr>';
                    } else {
                        $bodypdf .= '<tr class="item">
                                        <td colspan="2">
                                            <b>NOMBRES Y APELLIDOS: </b> ' . $resp["nomvali"] . ' ' . $resp["apevali"] . '
                                        </td>
                                    </tr>
                                    <tr class="item">
                                        <td colspan="2">
                                            <b>RUT: </b>  ' . $resp["rutvali"] . '
                                        </td>
                                    </tr>
                                    <tr class="item">
                                        <td colspan="2">
                                            <b>FIRMA: </b><br>
                                            <img src="../files/firma/' . $resp["filefir"] . '" style="width:100%; max-width:150px;"><br>
                                        </td>
                                    </tr>';
                    }
                }

                $bodypdf .= '
                    <br>
                    <tr class="top">
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td class="logofooter" align="right">
                                        <img src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAAMCAgMCAgMDAwMEAwMEBQgFBQQEBQoHBwYIDAoMDAsKCwsNDhIQDQ4RDgsLEBYQERMUFRUVDA8XGBYUGBIUFRT/2wBDAQMEBAUEBQkFBQkUDQsNFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBT/wgARCAAoAH8DAREAAhEBAxEB/8QAHAAAAgIDAQEAAAAAAAAAAAAAAAUEBwIDBgEI/8QAGgEAAgMBAQAAAAAAAAAAAAAAAAMCBAYFAf/aAAwDAQACEAMQAAABtDTZ/iuvzWKHLnpYocAuenrOZfuLK6MAAAAAACm9XnKy0PEbVnxWQYIdHZCG1XRULlz5PSc7foxGezEz99Vj4/rubbaVrNd93j173eRLVOMyDqpZ3rnHZD09uHK6OC2EVid0WYSVol6+pv3wZ8473G1TpeC9p2kVyq9p2gEVyrY/B7P0hgdmltV5ipa/ZaGJy8m5qulqaAAAAAAAAAAAAAAAB//EACUQAAICAgEDAwUAAAAAAAAAAAQFAwYBAgATFzUHFRYiMDQ2QP/aAAgBAQABBQK2WwtCx7jsuH35gLP3HZcZX5gGxW35gYx7jsuVi4mOmv2LGi+QWr470UEdf3clsaxHosMrE7TZUi6ESyuQFp6kOKNaHsmIl2Sth9tHE5BMDuaPT3mbeUBvP01c8hS7hJcAd0ZHjWpAGaISLtuFV0GjvChcW8CcDU5lELX1v13omfAo8LKPaHZ4NHjLaDqYeB5xE/Gl3gn0Ji56j+c45/L4984i85ygfsJYcZ0cleGzrIq0kyQminh3RaSz4Rx68FG1Eg/h/8QAOBEAAQIDBAUJBgcAAAAAAAAAAQIDAAQREiExUQUTQWFxEBQiNIGhwdHwFTKCkbHhIzBAQlKy8f/aAAgBAwEBPwGfn3ZV0IQBhHtmYyHf5w7pZ9CqADAd4rnHtmYyHf5w9pZ9t1SABcfW2GdLPuOpQQLz62x7ZmMh3+cSOkXZl7VrA/JnJXnc4EVp0fGOZ2ZfnDhpkKYwJQzK7RNEgJv+EQ9JANa9hdpMOSK39a8jYo3QxK2QzMVxULu37QzJocY17i7I4VjR6G0TYDarQplSJo2Wqk0vH1EKcKFfgmqdmJvsr7TgLr918NvuOKSon+XA4YUUd/yMGYWlZCb7/l0gL+ypGGG2BNrKjeAMzgBVYrjTYMseAjXqTYFcaZ1NcacNt1RiaCJZanWUuKxIryLcQ3PVWadDxh51uflgpSqLT3w262tCpV02ahN/wjyglmRl1oSu0peUCZEu08UnpWzd2iHJpqYSzYuNsXRo55KJazbANdsM36QtWgqo2QpQQCo7IQu0L8fXobo5yitPA7vOC8gf4Y5010b/AHsLjthMy2sFQwuz2whYcFpPJpnrA4eJ5Jn3xwT/AFHJNdYc4n6xK9Yb4j68mies9kONpdTZWLo5sj9nRG671XwFL41AraqfVPIb98OMJcQUYQZRKlBZJqKZbDUbM8oTLhIs1O7dTL71rgaiG2w2myP0X//EADkRAAECAwUCCgkFAQAAAAAAAAECAwAEEQUSITFBUWEQFCI0coGhsdHwExUyUnGCkcHhMDNAQvGy/9oACAECAQE/AbMsxmdZLjhOdMOrdHqCW95XZ4QxYku4mpUcyNNCRsj1BLe8rs8Il7El3WUOFRxAOnhExYku0ytwKOAJ08I9QS3vK7PCLRspmUY9Kgmvnd+jITnEZEuXa8unZHH781xVpNaZmuUKnkyjd0C8oqVQfMYYtFRe4tMouK74atFuWDLDmRSnHz3xMzl9T8rd9lBNer8xMT7jUwJZtu8aVzpFprdckSXUXTXbWJNN56gFcFb/AOpphAbCxR8UVrQAYXkU3A4nZpXCFSrbbbgAxw6sFZ1SPJTBlEKxoRsyx5JOHXQa50wMcUQEgEEnYKVJog7DtO3L4mHpVurigMr2OgpkKb9Mdd0TKEtPLbRkDTgbaW7ZtG015enRiXZcs2bKUoq2vZp5/O6HWXW1pnWU3rpUCPmV4wA/aM2hxbZQlGOMGUM09LhSTduDHqMNSb8qt/0mIuHHz3RasutybCvRqUmmn+GJjCy7lwpodc9uwQ2guLCBrC2FBVE45duUCTdV7NNNRr1xxZdAdu8duOEcTe5Qp7OeI8YVJOIArnjqMKUzNaDOFoU2q6rgsDmyul9hwSn7Z6Sv+jwSXNmuiO6J3mzvRPdwW3zQ/EQ06plV5GcCedwK+URt+o+n3hMyUilB/mR6q/DaDCJpSFX6V/Ap53wmdUhJQlIoa7dRQ6wZxRzSNa760rX6aUppDjhdVeP8L//EADsQAAEDAgMDCAgDCQAAAAAAAAECAxEEEgATIQUxQRAjMkJRYXGRFCIzdIGSstIVMMFAYnOCobGz0fD/2gAIAQEABj8CbYYbZWhTQXzgM7z392PYUvyq+7CUJZpiC02vVKusgKPHvx7Cl+VX3YqmEM0xQ06pAlKp0PjilYWzTBDrqUGEqnU+OPYUvyq+7Apn22EoKSZbSZ/v+Sinzsi2jzLrbuuf94/E6p5VPeYZZy5LnZx0wpxTqaWkZpmC4+vhzKcHaOzqwV1Kkwv1bVIxteup1XrarHU5EakTOnnuxsXamdOdWIbyrd3rHj/Lhe0amv8ARGkrsPMlf69+G00lX6a3kqJXllEHsg4kuFlGa0FLC7ITmJnXhpgmidL9Mo82464txN+W4TrMkaJ7ePHFEsupCFXIiLUuG9vo2rIO/t4KwkFxt5CQkuGwgsi9I9Yz2FRnTozuwohbVOibS68lRSkXvASJEdBPn4YoWytClKQzzSkkuOhQErCp4a8Or34pnnrcxxAWbBA15L33m2EHZ8XOKtHtMNuO1SKfaNGDKHVAZvbHjH/b8VOxqx4UiX2GHEPndOUjf8o/risYZrWq+rrRZzRlKRHj3nG3FNvNJqxtFaktKIlQuTOnnjYxpyltz8RbUtjrJMmT5nf34W36bS01RnEgVCuGnCRjO9KYq1OsXFVN0RpEbz2YcdUCQgTCd57hi9zm1ALvTvi0wrCszNTCljRlaujvOg3ajXCki9Vsza0s7iBppr8MUxvWE1EZSlNLAVO7WMORmZYShSTlLuXdduTEno8P0wHGzKT8ORj3cfUrkb93Y/xJ5Noe8OfUcbP94b+ociP4asBt4XtXBRQdyvHC0sldIhcyliEjUQeHHTyweddRJM2nqmLk/GPHsIwW8xxsG7oR1lBRG7ujww285UPuOJtkm0XWquEwMGH37haG1SJbCZiNP3iNZwGkSQJMq3kkyT5/sX//xAAnEAEBAAICAgEDAwUAAAAAAAABEQAhMUFRYRBxgZEwQNGxweHw8f/aAAgBAQABPyF60FfSfQ1p8bHVA96/Rmr42O8B7goXz1jvAeYCM89/GyW1uNH1f6PVe9lCkp/xn3zGQbWIdvHBd0weoQU6IavvevxUANC+AbPvvRpHjeWxajVUV2+j+M+h3zru239neaeP3oR1X4ZV6f4jQRvvGF4XJA1oindziShnJNB7wi66av6WPYFRg+3GnMZBG1hNtqTonIrhP6QM9EyYXW43oDBF+lB4pu3KpGJE69jFgK8Wfx8AogRo9K96cks6RsK47ik4dSJiYE1AF4ln4LpRmXmsy1Zs0hs7U1BzRnM+v5USKe/Gb0XSCEl2NPL2phUWEU022vPeIKkuWj6ohee8eI2a+g7XgM5e82dMs2DxoojMVBpnQMP7aG+cFNzvFUJHqu61/SoOhVqioFig9b4TIJ5MQUGqSqEl8s2SdyKEYiOxERHYn6M1fYsf6nxmquDDPYDssZ6OqZ0SAex5wgudlI2vRrUZfCNCqmjehmhot6YAoToSUjc8vMYqMOqyEOHkHLYQdTJ92kcw7uJowjVQh8qX7/sv/9oADAMBAAIAAwAAABDqKReSSSSarQuxL/TR0/wbR5xZZLQcbSySSSSSSST/xAAmEQEBAQACAgECBgMAAAAAAAABESEAMUFRYXGhEIGRsfDxIDBA/9oACAEDAQE/EHTBDo+08J65/R8CW2vT4F+pzn9HwkvoFHwpxJfAYPlDj+j4DgCLg3Pqv+nxTXZfI9nvnZds0Po9kHfHRS0r0NsvH6F+dw/IVsE7kT8v3wyMTeMtqHGoNo+Xep+dzndubHWvN/Z544hGdvT0/PrmjMbWvUf3+eHgGrZBJ0SZa3g2zdlNCMReliL03BQBA5oDKAp5VYMNRZgCuNIyrhdAJRA8Q4DGkBljUradKAdaUZRURKnSooCCDyMsWCGlmr19fwg+MVQ+/hgTuKHTZ8sydOSI8lFOPCmLc8PV0ozgksnkBJscxY+VMg8FSBBJU0zuJevn1xOmi4o1rPIrb5u7Tj4V3bxnij9+TvlV6mSdvgvfnnQSFfOGuc65AonyX6MQqQqGFnJpXuERYJYFhBenG6cqSslidlJBuazo1mXNqiBYKoFk3xfG9bxwVAqFNIISozEo76eBUzfCdMcYiJEfw+x/4Cj+S9ufyXp+HT9XEliin0RPuFPJjRTiWJoMkLEfFwxidkCq0ifhDIEMpbKQJg5QKH1OpJokmScYW7GKQmCB8C5VhF4lhoz0IDrzoNMOEkXtV7VVV6NVYAHQBD/i/8QAJhEBAQEAAwABAwMFAQAAAAAAAREhADFBURBhgaHw8SAwQHGRsf/aAAgBAgEBPxAxJFoBAXq+efynBPdMT0E/4N+/P5ThTFYjFQc4pikVigu8fynBevQak3/Q/s+6qizstsfjnRUrkI7JGph32xkUANjTt93ufbGv5QnSlNp+X/motKJEh4tvBSRPDPWekrzpt2DvGSZ+XnKk/wAPy+SefPMx9yMfNPyT7cbQqAhSJoI7IR3mLOYTsKEEYrU0vVCoruo8SwUMQtB6p1Ib2Q4pDwKVX4DjPBUSg4GiFoYKJohQCyQdNY7gFBgBob0VaRcZcDuWTOt7+iZj3ArPWcXc0iFdsveFjezDR4taesR2A31e5jEHn2VRitpKFqFOgHahxwxkgYNDeqMY/aiPBQsM3EgBfECfAMyPGZAmXvfYfpysimHdXXwVTrziKgoN6L6vgevhzpUcOFm+sfnUES8k6UWn7ho1jnedcHZDEo9i9hoMoXzeJsi2gdAisKQupY52Jw4ikJh2OwWIojDtOKBifnsoiYiaJiaZ9P1T+gD/ALT8OftPy+uKTkIPpcU+8pfLSMSwJ1G1x+QtWXxDSBSG67HtPpqERqOxvC8ETu9oGiN20RAJOAf9DU3irZ61NDFFMwNCMejWaEwWg4/mOEOgAAPsABa5qv8Ahf/EACIQAQEAAgIBBQADAAAAAAAAAAERACExQVEQMGFxkUDR8P/aAAgBAQABPxA58zUxBPp0Wrv0HPWvERIQ6GO4FV24OWNs4mSCMCwC9GDG2cTJLCFKJen0HLJqwNEKSb8ez/UF3Hd3t+m9FHyUDF+pWVx9Buj1e6FQWh0AatRiDYPWGitYhpQIUg3qHrNHXhBsYxWL8v8AMXP+ifnrdAi6FddFsghp5zmMMvaNisHQ49OKvjZl5UAUCC7xTzaxNCVrgGAZ9CKnzm1ookdETlgYhqObE9ANQmEnFmlAjHSW3VmHkdf5OAHJX7BB9LmC5Y9AldosqGCfAoi0Kgwk5g+MV4Le5dhABpWMBU5ep+NAqg6Nkt6cgsE92xiRBtQ0sZJRAmFYKAhGhSzdrhBnGUi2iCfejcqqyIAQFO5MQHHPuElwJX6QmP8At9DCnYU7Ch3lCBuzWoCwFUIbQW9V3O7Br+CpGGFbJ0UWMKTgoiuH2nNmGHsQCwBQk1akKqYM9wT0JjtpQqQHgYEICCJ7IaDWrf7Xhg/SooNcMTxqk1IsYYahmoDhhElGBnem+YW0XcOgOA2MRqtJSoTEEq5KKFUBJ0iKwCCTvG7W6VOoeUkdK+fURnQ5XgFUAh/C/9k=" style="width:100%; max-width:150px;"><br>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </body>
        </html>
        ';

                $guiaPDF = new mPDF('c');

                $guiaPDF->WriteHTML($bodypdf);
                $archivo = $i . "" . round(microtime(true)) . ".pdf";
                $rpdf = $guiaPDF->Output('../files/pdf/' . $archivo, 'F');
                $respdf = $servicio->UpFile($archivo, $reg->idservicio);

                if ($respdf) {
                    echo "Se generaro pdf " . $i . " de forma correcta <br>";
                } else {
                    echo "No se pudo generar pdf <br>";
                }
                $i++;
            }
        } else {
            echo 'Faltan parametros para procesar la solicitud de pdfs';
        }
        break;

    case 'GMESANO':
        $idedificio = isset($_POST["idedificio"]) ? limpiarCadena($_POST["idedificio"]) : "";
        $mes = isset($_POST["mes"]) ? limpiarCadena($_POST["mes"]) : "";
        $ano = isset($_POST["ano"]) ? limpiarCadena($_POST["ano"]) : "";
        $meses = array("ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE");
        $resp = $email->edificio($idedificio);
        $body = '
	<html>
        <head>
            <meta charset="utf-8">
            <title>Guias de servicio / MES de ' . $meses[(intval($mes) - 1)] . ' Año ' . $ano . ' </title>

            <style>
            .invoice-box {
                max-width: 800px;
                margin: auto;
                padding: 30px;
                border: 1px solid #eee;
                box-shadow: 0 0 10px rgba(0, 0, 0, .15);
                font-size: 12px;
                line-height: 12px;
                font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
                color: #555;
            }

            .invoice-box table {
                width: 100%;
                line-height: inherit;
                text-align: left;
            }

            .invoice-box table td {
                padding: 5px;
                vertical-align: top;
            }

            .invoice-box table td.logo {
                font-size: 10px;
                line-height: 4px;
            }

            .invoice-box table td.border {
                border: 1px solid black;
                text-align: center;
                vertical-align: middle;
                line-height: 24px;
                font-size: 18px;
            }

            .invoice-box table tr td:nth-child(2) {
                text-align: center;
            }

            .invoice-box table tr.top table td {
                padding-bottom: 20px;
            }

            .invoice-box table tr.top table td.title {
                font-size: 45px;
                line-height: 45px;
                color: #333;
            }

            .invoice-box table tr.information table td {
                padding-bottom: 40px;
                border: 1px solid black;

            }

            .invoice-box table tr.heading td {
                background: #eee;
                border-bottom: 1px solid #ddd;
                font-weight: bold;
            }

            .invoice-box table tr.details td {
                padding-bottom: 20px;
            }

            .invoice-box table tr.item td{
                border-bottom: 1px solid #eee;
            }

            .invoice-box table tr.item.last td {
                border-bottom: none;
            }

            .invoice-box table tr.total td:nth-child(2) {
                border-top: 2px solid #eee;
                font-weight: bold;
            }

            @media only screen and (max-width: 600px) {
                .invoice-box table tr.top table td {
                    width: 100%;
                    display: block;
                    text-align: center;
                }

                .invoice-box table tr.information table td {
                    width: 100%;
                    display: block;
                    text-align: center;
                }
            }

            /** RTL **/
            .rtl {
                direction: rtl;
                font-family: Tahoma, "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
            }

            .rtl table {
                text-align: right;
            }

            .rtl table tr td:nth-child(2) {
                text-align: left;
            }
            </style>
        </head>

        <body>
            <div class="invoice-box">
                <table cellpadding="0" cellspacing="0">
                    <tr class="top">
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td class="title">
                                        <img src="data:image/jpeg;base64,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" style="width:100%; max-width:300px;"><br><br>
                                        SERVICIOS DEL MES DE <b> ' . $meses[(intval($mes) - 1)] . '</b><br>
                                    </td>              
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <br>
                    <br>
                    <tr class="heading">
                        <td>
                            INFORMACION DEL EDIFICIO
                        </td>            
                    </tr>

                    <tr class="item">
                        <td>
                            <b>SEGMENTO: </b>' . $resp["segmen"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>NOMBRE: </b> ' . $resp["edi"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>DIRECCION: </b> ' . $resp["calle"] . ' ' . $resp["numero"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>REGION: </b>' . $resp["region"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>COMUNA: </b>' . $resp["comuna_nombre"] . '
                        </td>
                    </tr>

                    <br>    
                    <br>

                    <tr class="heading">
                        <td>
                            INFORMACION DEL SERVICIOS
                        </td>            
                    </tr>

                    <tr class="item">
                        <td>
                            <b>AÑO: </b> ' . $ano . '
                        </td>
                    </tr>
                    <tr class="item">
                     <td>
                            <b>MES: </b> ' . $meses[(intval($mes) - 1)] . '
                        </td>
                    </tr>';

        $body .= '
                    <br>
                    <br>
                    <tr class="top">
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td class="logofooter" align="right">
                                        <img src="data:image/jpeg;base64,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" style="width:100%; max-width:150px;"><br>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </body>
        </html>
        ';

        $Mailer = new PHPMailer();
        $Mailer->isSMTP();
        $Mailer->CharSet = 'UTF-8';
        $Mailer->Port = 587;
        $Mailer->SMTPAuth = true;
        $Mailer->SMTPSecure = "tls";
        $Mailer->SMTPDebug = 0;
        $Mailer->Debugoutput = 'html';
        $Mailer->Host = "mail.fabrimetalsa.cl";
        $Mailer->Username = "notificaciones@fabrimetalsa.cl";
        $Mailer->Password = "*RUJQtbV!wK*";
        $Mailer->From = "notificaciones@fabrimetalsa.cl";
        $Mailer->FromName = "Sistema guía de servicio -> Fabrimetal";
        $Mailer->Subject = "Copia - Guías de servicio del Mes de " . $meses[(intval($mes) - 1)] . " Año " . $ano;
        //$Mailer->AddAttachment('../files/pdf/'.$resp["file"], $name = $resp["file"],  $encoding = 'base64', $type = 'application/pdf');

        $pdfs = $email->pdfano($idedificio, $mes, $ano);
        while ($reg = $pdfs->fetch_object()) {
            $Mailer->addAttachment('../files/pdf/' . $reg->file);
        }

        $Mailer->msgHTML($body);

        /*
        $rspta = $email->emailedif($idedificio);
        while ($reg = $rspta->fetch_object()) {
            $Mailer->addAddress($reg->email, '');
        }
        

        $Mailer->addAddress("ecarrero.fabrimetal@gmail.com", '');
        */
        $Mailer->addAddress("omaldonado@fabrimetal.cl", '');




        if (!$Mailer->send()) {
            echo "Mailer: " . $Mailer->ErrorInfo;
        } else {
            echo "Correo enviado con guias del MES de " . $meses[(intval($mes) - 1)] . " Año " . $ano;
        }

        break;


    case 'GMESANOI':
        $idedificio = isset($_POST["idedificio"]) ? limpiarCadena($_POST["idedificio"]) : "";
        $mes = isset($_POST["mes"]) ? limpiarCadena($_POST["mes"]) : "";
        $ano = isset($_POST["ano"]) ? limpiarCadena($_POST["ano"]) : "";
        $meses = array("ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE");
        $resp = $email->edificio($idedificio);
        $body = '
    <html>
        <head>
            <meta charset="utf-8">
            <title>Guias de servicio / MES de ' . $meses[(intval($mes) - 1)] . ' Año ' . $ano . ' </title>

            <style>
            .invoice-box {
                max-width: 800px;
                margin: auto;
                padding: 30px;
                border: 1px solid #eee;
                box-shadow: 0 0 10px rgba(0, 0, 0, .15);
                font-size: 12px;
                line-height: 12px;
                font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
                color: #555;
            }

            .invoice-box table {
                width: 100%;
                line-height: inherit;
                text-align: left;
            }

            .invoice-box table td {
                padding: 5px;
                vertical-align: top;
            }

            .invoice-box table td.logo {
                font-size: 10px;
                line-height: 4px;
            }

            .invoice-box table td.border {
                border: 1px solid black;
                text-align: center;
                vertical-align: middle;
                line-height: 24px;
                font-size: 18px;
            }

            .invoice-box table tr td:nth-child(2) {
                text-align: center;
            }

            .invoice-box table tr.top table td {
                padding-bottom: 20px;
            }

            .invoice-box table tr.top table td.title {
                font-size: 45px;
                line-height: 45px;
                color: #333;
            }

            .invoice-box table tr.information table td {
                padding-bottom: 40px;
                border: 1px solid black;

            }

            .invoice-box table tr.heading td {
                background: #eee;
                border-bottom: 1px solid #ddd;
                font-weight: bold;
            }

            .invoice-box table tr.details td {
                padding-bottom: 20px;
            }

            .invoice-box table tr.item td{
                border-bottom: 1px solid #eee;
            }

            .invoice-box table tr.item.last td {
                border-bottom: none;
            }

            .invoice-box table tr.total td:nth-child(2) {
                border-top: 2px solid #eee;
                font-weight: bold;
            }

            @media only screen and (max-width: 600px) {
                .invoice-box table tr.top table td {
                    width: 100%;
                    display: block;
                    text-align: center;
                }

                .invoice-box table tr.information table td {
                    width: 100%;
                    display: block;
                    text-align: center;
                }
            }

            /** RTL **/
            .rtl {
                direction: rtl;
                font-family: Tahoma, "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
            }

            .rtl table {
                text-align: right;
            }

            .rtl table tr td:nth-child(2) {
                text-align: left;
            }
            </style>
        </head>

        <body>
            <div class="invoice-box">
                <table cellpadding="0" cellspacing="0">
                    <tr class="top">
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td class="title">
                                        <img src="data:image/jpeg;base64,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" style="width:100%; max-width:300px;"><br><br>
                                        SERVICIOS DEL MES DE <b> ' . $meses[(intval($mes) - 1)] . '</b><br>
                                    </td>              
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <br>
                    <br>
                    <tr class="heading">
                        <td>
                            INFORMACION DEL EDIFICIO
                        </td>            
                    </tr>

                    <tr class="item">
                        <td>
                            <b>SEGMENTO: </b>' . $resp["segmen"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>NOMBRE: </b> ' . $resp["edi"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>DIRECCION: </b> ' . $resp["calle"] . ' ' . $resp["numero"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>REGION: </b>' . $resp["region"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>COMUNA: </b>' . $resp["comuna_nombre"] . '
                        </td>
                    </tr>

                    <br>    
                    <br>

                    <tr class="heading">
                        <td>
                            INFORMACION DEL SERVICIOS
                        </td>            
                    </tr>

                    <tr class="item">
                        <td>
                            <b>AÑO: </b> ' . $ano . '
                        </td>
                        <td>
                            <b>MES: </b> ' . $meses[(intval($mes) - 1)] . '
                        </td>
                    </tr>';

        $body .= '
                    <br>
                    <br>
                    <tr class="top">
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td class="logofooter" align="right">
                                        <img src="data:image/jpeg;base64,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" style="width:100%; max-width:150px;"><br>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </body>
        </html>
        ';

        $Mailer = new PHPMailer();
        $Mailer->isSMTP();
        $Mailer->CharSet = 'UTF-8';
        $Mailer->Port = 587;
        $Mailer->SMTPAuth = true;
        $Mailer->SMTPSecure = "tls";
        $Mailer->SMTPDebug = 0;
        $Mailer->Debugoutput = 'html';
        $Mailer->Host = "mail.fabrimetalsa.cl";
        $Mailer->Username = "notificaciones@fabrimetalsa.cl";
        $Mailer->Password = "*RUJQtbV!wK*";

        $Mailer->From = "notificaciones@fabrimetalsa.cl";
        $Mailer->FromName = "Sistema guía de servicio -> Fabrimetal";
        $Mailer->Subject = "Copia - Guías de servicio del Mes de " . $meses[(intval($mes) - 1)] . " Año " . $ano;
        //$Mailer->AddAttachment('../files/pdf/'.$resp["file"], $name = $resp["file"],  $encoding = 'base64', $type = 'application/pdf');

        $pdfs = $email->pdfano($idedificio, $mes, $ano);
        while ($reg = $pdfs->fetch_object()) {
            $Mailer->addAttachment('../files/pdf/' . $reg->file);
        }

        $Mailer->msgHTML($body);


        $Mailer->addAddress("omaldonado@fabrimetal.cl", '');



        if (!$Mailer->send()) {
            echo "Mailer: " . $Mailer->ErrorInfo;
        } else {
            echo "Correo enviado con guias del MES de " . $meses[(intval($mes) - 1)] . " Año " . $ano;
        }

        break;


    case 'GSINT':
        $idservicio = isset($_POST["idservicio"]) ? limpiarCadena($_POST["idservicio"]) : "";

        $resp = $servicio->pdf($idservicio);

        $bodypdf = '
	<html>
        <head>
            <meta charset="utf-8">
            <title>Guia de servicio N° ' . $resp["idservicio"] . ' </title>

            <style>
            .invoice-box {
                max-width: 800px;
                margin: auto;
                padding: 30px;
                border: 1px solid #eee;
                box-shadow: 0 0 10px rgba(0, 0, 0, .15);
                font-size: 12px;
                line-height: 12px;
                font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
                color: #555;
            }

            .invoice-box table {
                width: 100%;
                line-height: inherit;
                text-align: left;
            }

            .invoice-box table td {
                padding: 5px;
                vertical-align: top;
            }

            .invoice-box table td.logo {
                font-size: 10px;
                line-height: 11px;
            }

            .invoice-box table td.border {
                border: 1px solid black;
                text-align: center;
                vertical-align: middle;
                line-height: 24px;
                font-size: 18px;
            }

            .invoice-box table tr td:nth-child(2) {
                text-align: center;
            }

            .invoice-box table tr.top table td {
                padding-bottom: 20px;
            }

            .invoice-box table tr.top table td.title {
                font-size: 45px;
                line-height: 45px;
                color: #333;
            }

            .invoice-box table tr.information table td {
                padding-bottom: 40px;
                border: 1px solid black;

            }

            .invoice-box table tr.heading td {
                background: #eee;
                border-bottom: 1px solid #ddd;
                font-weight: bold;
                max-width: 800px;
            }

            .invoice-box table tr.details td {
                padding-bottom: 20px;
                max-width: 800px;
            }

            .invoice-box table tr.item td{
                border-bottom: 1px solid #eee;
            }

            .invoice-box table tr.item.last td {
                border-bottom: none;
            }

            .invoice-box table tr.total td:nth-child(2) {
                border-top: 2px solid #eee;
                font-weight: bold;
            }

            @media only screen and (max-width: 600px) {
                .invoice-box table tr.top table td {
                    width: 100%;
                    display: block;
                    text-align: center;
                }

                .invoice-box table tr.information table td {
                    width: 100%;
                    display: block;
                    text-align: center;
                }
            }

            /** RTL **/
            .rtl {
                direction: rtl;
                font-family: Tahoma, "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
            }

            .rtl table {
                text-align: right;
            }

            .rtl table tr td:nth-child(2) {
                text-align: left;
            }
            </style>
        </head>

        <body>
            <div class="invoice-box">
                <table cellpadding="0" cellspacing="0">
                    <tr class="top">
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td class="logo">
                                        <img src="data:image/jpeg;base64,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" style="width:100%; max-width:300px;"><br>
                                        <p>RUT: 85.233.500-9</p>
                                        <p>GIRO: FABRICACIONES METALICAS ESPECIALES</p>
                                        <p>DIRECCION: VOLCAN LASCAR 818, PARQUE IND. LO BOZA - PUDAHUEL - SANTIAGO</p>
                                        <p>TELEFONO: 29493900</p>
                                        <p>PAGINA WEB: WWW.FABRIMETAL.CL</p>
                                    </td>

                                    <td class="border">
                                        ' . $resp["ini"] . '<br>
                                        <b>SERVICIO N°</b><br>
                                        <b>' . $resp["idservicio"] . '</b>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <br>
                    <tr class="heading">
                        <td colspan="2">
                            INFORMACION DEL EDIFICIO
                        </td>            
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>NOMBRE: </b> ' . $resp["edi"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>DIRECCION: </b> ' . $resp["calle"] . ' ' . $resp["numero"] . ', ' . $resp["comuna"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>REGION: </b>' . $resp["region"] . '
                        </td>
                    </tr>
                    
                    <br>

                    <tr class="heading">
                        <td colspan="2">
                            INFORMACION DEL SERVICIO
                        </td>            
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>EQUIPO: </b> ' . $resp["tascen"] . ' - ' . $resp["marca"] . ' ' . $resp["modelo"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>CODIGO FM: </b> ' . $resp["codigo"] . '
                        </td>
                    </tr>
                    <tr class="item">
                        <td colspan="2">
                            <b>CODIGO CLIENTE: </b>  ' . $resp["ubicacion"] . ' ' . $resp["codigocli"] . '
                        </td>
                    </tr>
                    
                    <tr class="item">
                        <td colspan="2">
                            <b>TECNICO: </b> ' . $resp["nomtec"] . ' ' . $resp["apetec"] . ' |  <b> RUT: </b> ' . $resp["ruttec"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>TIPO DE SERVICIO: </b> ' . $resp["tser"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>FECHA Y HORA DEL SERVICIO: </b> ' . $resp["ini"] . '
                        </td>
                    </tr>
 
                    <tr class="item">
                        <td colspan="2">
                            <b>OBSERVACION AL INICIAR SERVICIO: </b> ' . $resp["observacionini"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td colspan="2">
                            <b>ESTADO DEL EQUIPO AL FINALIZAR: </b> ' . $resp["esfin"] . '
                        </td>
                    </tr>
                    
                    <tr class="item">
                        <td colspan="2">
                            <b>OBSERVACION AL FINALIZAR SERVICIO: </b> ' . $resp["observacionfin"] . '
                        </td>
                    </tr>
                    <br>
                    <tr class="heading">
                        <td colspan="2">
                            APROBACION DEL SERVICIO
                        </td>            
                    </tr>';

        if ($resp["reqfirma"] == '0') {

            $bodypdf .= '<tr class="item">
                                        <td colspan="2">
                                            <b>NO REQUIERE FIRMA</b>
                                        </td>
                                    </tr>';
        } else {
            if (is_null($resp["filefir"])) {
                $bodypdf .= '<tr class="item">
                                        <td colspan="2">
                                            <b>NOMBRES Y APELLIDOS: </b> ' . $resp["nomvali"] . ' ' . $resp["apevali"] . '
                                        </td>
                                    </tr>
                                    <tr class="item">
                                        <td colspan="2">
                                            <b>RUT: </b>  ' . $resp["rutvali"] . '
                                        </td>
                                    </tr>
                                    <tr class="item">
                                        <td colspan="2">
                                            <b>FIRMA: </b> ERROR AL CARGAR LA FIRMA
                                        </td>
                                    </tr>';
            } else {
                $bodypdf .= '<tr class="item">
                                        <td colspan="2">
                                            <b>NOMBRES Y APELLIDOS: </b> ' . $resp["nomvali"] . ' ' . $resp["apevali"] . '
                                        </td>
                                    </tr>
                                    <tr class="item">
                                        <td colspan="2">
                                            <b>RUT: </b>  ' . $resp["rutvali"] . '
                                        </td>
                                    </tr>
                                    <tr class="item">
                                        <td colspan="2">
                                            <b>FIRMA: </b><br>
                                            <img src="../files/firma/' . $resp["filefir"] . '" style="width:100%; max-width:150px;"><br>
                                        </td>
                                    </tr>';
            }
        }

        $bodypdf .= '
                    <br>
                    <tr class="top">
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td class="logofooter" align="right">
                                        <img src="data:image/jpeg;base64,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" style="width:100%; max-width:150px;"><br>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </body>
        </html>
        ';

        $guiaPDF = new mPDF('c');

        $guiaPDF->WriteHTML($bodypdf);
        $archivo = round(microtime(true)) . ".pdf";
        $rpdf = $guiaPDF->Output('../files/pdf/' . $archivo, 'F');
        $servicio->UpFile($archivo, $idservicio);
        $resp = $servicio->email($idservicio);

        $body = '
	<html>
        <head>
            <meta charset="utf-8">
            <title>Guia de servicio N° ' . $resp["idservicio"] . ' </title>

            <style>
            .invoice-box {
                max-width: 800px;
                margin: auto;
                padding: 30px;
                border: 1px solid #eee;
                box-shadow: 0 0 10px rgba(0, 0, 0, .15);
                font-size: 12px;
                line-height: 12px;
                font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
                color: #555;
            }

            .invoice-box table {
                width: 100%;
                line-height: inherit;
                text-align: left;
            }

            .invoice-box table td {
                padding: 5px;
                vertical-align: top;
            }

            .invoice-box table td.logo {
                font-size: 10px;
                line-height: 4px;
            }

            .invoice-box table td.border {
                border: 1px solid black;
                text-align: center;
                vertical-align: middle;
                line-height: 24px;
                font-size: 18px;
            }

            .invoice-box table tr td:nth-child(2) {
                text-align: center;
            }

            .invoice-box table tr.top table td {
                padding-bottom: 20px;
            }

            .invoice-box table tr.top table td.title {
                font-size: 45px;
                line-height: 45px;
                color: #333;
            }

            .invoice-box table tr.information table td {
                padding-bottom: 40px;
                border: 1px solid black;

            }

            .invoice-box table tr.heading td {
                background: #eee;
                border-bottom: 1px solid #ddd;
                font-weight: bold;
            }

            .invoice-box table tr.details td {
                padding-bottom: 20px;
            }

            .invoice-box table tr.item td{
                border-bottom: 1px solid #eee;
            }

            .invoice-box table tr.item.last td {
                border-bottom: none;
            }

            .invoice-box table tr.total td:nth-child(2) {
                border-top: 2px solid #eee;
                font-weight: bold;
            }

            @media only screen and (max-width: 600px) {
                .invoice-box table tr.top table td {
                    width: 100%;
                    display: block;
                    text-align: center;
                }

                .invoice-box table tr.information table td {
                    width: 100%;
                    display: block;
                    text-align: center;
                }
            }

            /** RTL **/
            .rtl {
                direction: rtl;
                font-family: Tahoma, "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
            }

            .rtl table {
                text-align: right;
            }

            .rtl table tr td:nth-child(2) {
                text-align: left;
            }
            </style>
        </head>

        <body>
            <div class="invoice-box">
                <table cellpadding="0" cellspacing="0">
                    <tr class="top">
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td class="title">
                                        <img src="data:image/jpeg;base64,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" style="width:100%; max-width:300px;"><br><br>
                                        SERVICIO N°<b> ' . $resp["idservicio"] . '</b><br>
                                    </td>              
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <br>
                    <br>
                    <tr class="heading">
                        <td>
                            INFORMACION DEL EDIFICIO
                        </td>            
                    </tr>

                    <tr class="item">
                        <td>
                            <b>SEGMENTO: </b>' . $resp["segmen"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>NOMBRE: </b> ' . $resp["edi"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>DIRECCION: </b> ' . $resp["calle"] . ' ' . $resp["numero"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>REGION: </b>' . $resp["region"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>COMUNA: </b>' . $resp["comuna"] . '
                        </td>
                    </tr>

                    <br>    
                    <br>

                    <tr class="heading">
                        <td>
                            INFORMACION DEL SERVICIO
                        </td>            
                    </tr>

                    <tr class="item">
                        <td>
                            <b>EQUIPO: </b> ' . $resp["marca"] . ' ' . $resp["modelo"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>TIPO DE EQUIPO: </b> ' . $resp["tascen"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>CODIGO FM: </b> ' . $resp["codigo"] . '
                        </td>
                    </tr>
                    
                    <tr class="item">
                        <td>
                            <b>IDENTIFICACION CLIENTE: </b> ' . $resp["ubicacion"] . ' ' . $resp["codigocli"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>TECNICO: </b> ' . $resp["nomtec"] . ' ' . $resp["apetec"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>RUT: </b> ' . $resp["ruttec"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>CARGO: </b>' . $resp["cartec"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>TIPO DE SERVICIO: </b> ' . $resp["tser"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>FECHA Y HORA DEL SERVICIO: </b> ' . $resp["ini"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>ESTADO DEL EQUIPO AL INICIAR: </b> ' . $resp["esini"] . '
                        </td>
                    </tr>
                    
                    <tr class="item">
                        <td>
                            <b>OBSERVACION AL INICIAR: </b> ' . $resp["observacionini"] . '
                        </td>
                    </tr>

                    <tr class="item">
                        <td>
                            <b>ESTADO DEL EQUIPO AL FINALIZAR: </b> ' . $resp["esfin"] . '
                        </td>
                    </tr>
                    <tr class="item">
                        <td>
                            <b>OBSERVACION AL FINALIZAR: </b> ' . $resp["observacionfin"] . '
                        </td>
                    </tr>
                    <tr class="heading">
                        <td>
                           APROBACION DEL SERVICIO
                        </td>            
                    </tr>';

        if ($resp["reqfirma"] == '3') {

            $body .= '  <tr class="item">
                                <td>
                                    <b>NO REQUIERE FIRMA</b>
                                </td>
                            </tr>';
        } else {
            $body .= '<tr class="item">
                                <td>
                                    <b>NOMBRES: </b> ' . $resp["nomvali"] . '
                                </td>
                            </tr>
                            <tr class="item">
                                <td>
                                    <b>APELLIDOS: </b> ' . $resp["apevali"] . '
                                </td>
                            </tr>
                            <tr class="item">
                                <td>
                                    <b>RUT: </b> ' . $resp["rutvali"] . '
                                </td>
                            </tr>
                            <tr class="item">
                                <td colspan="2">
                                    <b>FIRMA: </b><br>
                                    <img src="' . $resp["firma"] . '" style="width:100%; max-width:150px;"><br>
                                </td>
                            </tr>';
        }

        $body .= '
                    <br>
                    <br>
                    <tr class="top">
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td class="logofooter" align="right">
                                        <img src="data:image/jpeg;base64,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" style="width:100%; max-width:150px;"><br>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </body>
        </html>
        ';

        $Mailer = new PHPMailer();
        $Mailer->isSMTP();
        $Mailer->CharSet = 'UTF-8';

        $Mailer->Port = 587;
        $Mailer->SMTPAuth = true;
        $Mailer->SMTPSecure = "tls";
        $Mailer->SMTPDebug = 0;
        $Mailer->Debugoutput = 'html';
        $Mailer->Host = "mail.fabrimetalsa.cl";
        $Mailer->Username = "notificaciones@fabrimetalsa.cl";
        $Mailer->Password = "*RUJQtbV!wK*";

        $Mailer->From = "notificaciones@fabrimetalsa.cl";
        $Mailer->FromName = "Sistema guía de servicio -> Fabrimetal";
        $Mailer->Subject = "Copia - Guía de servicio N° " . $resp["idservicio"];
        //$Mailer->AddAttachment('../files/pdf/'.$resp["file"], $name = $resp["file"],  $encoding = 'base64', $type = 'application/pdf');
        $Mailer->addAttachment('../files/pdf/' . $resp["file"]);
        $Mailer->msgHTML($body);

        $respemail = $tecnico->Email($idservicio);
        $Mailer->addAddress($respemail["email"], '');
        echo 'Correo Tecnico: ' . $respemail["email"];

        $Mailer->addAddress("ecarrero.fabrimetal@gmail.com", '');
        $Mailer->addAddress("omaldonado@fabrimetal.cl", '');




        if (!$Mailer->send()) {
            echo "Mailer: " . $Mailer->ErrorInfo;
        } else {
            echo "Correo enviado exitosamente";
        }

        break;
}
?>