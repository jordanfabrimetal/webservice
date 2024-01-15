<?php
ob_start();
session_name('SESS_GSAP');
session_start();

if (!isset($_SESSION["nombre"])) {
    header("Location:login.php");
} else {

    require 'header.php';

    if ($_SESSION['Guia'] == 1) {
?>

        <!-- page content -->
        <div class="right_col" role="main">
            <div class="">

                <div class="clearfix"></div>

                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Guia de servicio</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div id="inicio" class="x_content">

                            </div>

                            <div id="inicioservicio" class="x_content">
                                <br />
                                <div class="clearfix"></div>
                                <form id="formularioinicio" name="formularioinicio" method="post" class="form-horizontal form-label-left">
                                    <!-- <div id="pptoPendiente">
                                        <h4><b>PRESUPUESTOS PENDIENTES</b></h4>
                                        <table id="tblpresupuestos" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>SOLICITUD</th>
                                                    <th>FECHA</th>
                                                    <th>DESCRIPCION</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div> -->
                                    <h4><b>INFORMACION DEL EQUIPO</b></h4>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Codigo</label>
                                        <input type="text" class="form-control" disabled="disabled" name="servicecallID" id="servicecallID">
                                        <input type="text" class="form-control" disabled="disabled" name="customerCode" id="customerCode">
                                        <input type="text" class="form-control" disabled="disabled" name="itemcode" id="itemcode">
                                        <input type="text" disabled class="form-control" name="equipmentcardnum" id="equipmentcardnum">
                                        <input type="text" class="form-control" disabled="disabled" name="fm" id="fm">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Tipo de Ascensor</label>
                                        <input type="text" class="form-control" disabled="disabled" name="tascensorso" id="tascensorso">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Marca</label>
                                        <input type="text" class="form-control" disabled="disabled" name="manufacturer" id="manufacturer">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Modelo</label>
                                        <input type="text" class="form-control" disabled="disabled" name="itemname" id="itemname">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Código Cliente</label>
                                        <input type="text" disabled class="form-control" name="codclisap" id="codclisap">
                                        <input type="text" class="form-control" name="codcli" id="codcli">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Garantía</label>
                                        <input type="text" class="form-control" disabled="disabled" name="garantia" id="garantia">
                                    </div>
                                    <h4><b>INFORMACION DEL EDIFICIO</b></h4>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Nombre</label>
                                        <input type="text" class="form-control" disabled="disabled" name="edificio" id="edificio">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Direccion</label>
                                        <input type="text" class="form-control" disabled="disabled" name="direccion" id="direccion">
                                    </div>

                                    <h4><b>INFORMACION DEL SERVICIO</b></h4>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Tipo de servicio <span class="required">*</span></label>
                                        <input type="text" class="form-control" disabled="disabled" name="calltype" id="calltype">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Estado del Ascensor</label>
                                        <input type="text" id="latitudso" name="latitudso" class="form-control">
                                        <input type="text" id="longitudso" name="longitudso" class="form-control">
                                        <input type="text" class="form-control" disabled="disabled" name="status" id="status">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label for="observacioniniso">Observaciones de inicio de servicio</label>
                                        <input type="text" class="form-control" name="subject" id="subject" required>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="ln_solid"></div>
                                    <div class="form-group">
                                        <div class="col-md-6 col-sm-6 col-xs-12 left-margin">
                                            <button class="btn btn-primary" type="button" onclick="cancelarform()" id="btnVolver">Volver</button>
                                            <button class="btn btn-success" type="submit" id="btnIniciar">Iniciar Servicio</button>
                                        </div>
                                    </div>

                                </form>
                            </div>

                            <div id="finservicio" class="x_content">
                                <br />
                                <div class="clearfix"></div>
                                <form id="formulariofin" name="formulariofin" method="post" class="form-horizontal form-label-left" action="../ajax/uploadpres.php?op=interno" enctype="multipart/form-data">
                                    <input type="hidden" id="file01" name="file01" class="form-control" value="">
                                    <input type="hidden" id="file02" name="file02" class="form-control" value="">
                                    <input type="hidden" id="file03" name="file03" class="form-control" value="">
                                    <input type="hidden" id="attachments" name="attachments" class="form-control" value="">
                                    <input type="hidden" id="dTime" name="dTime" class="form-control" value="<?php echo date('YmdHis'); ?>">
                                    <h4><b>INFORMACION DEL EQUIPO</b></h4>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Codigo</label>
                                        <input type="hidden" readonly="true" class="form-control" name="servicecallIDfi" id="servicecallIDfi">
                                        <input type="hidden" readonly="true" class="form-control" name="actividadIDfi" id="actividadIDfi">
                                        <input type="hidden" readonly="true" class="form-control" name="customercodefi" id="customercodefi">
                                        <input type="hidden" readonly="true" class="form-control" name="codigofmfi" id="codigofmfi">
                                        <input type="hidden" readonly="true" class="form-control" name="ascensorIDfi" id="ascensorIDfi">
                                        <input type="hidden" readonly="true" class="form-control" name="txtObsFin" id="txtObsFin">
                                        <input type="text" class="form-control" disabled="disabled" name="codigofi" id="codigofi">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Tipo de Ascensor</label>
                                        <input type="text" class="form-control" disabled="disabled" name="tascensorfi" id="tascensorfi">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Marca</label>
                                        <input type="text" class="form-control" disabled="disabled" name="marcafi" id="marcafi">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Modelo</label>
                                        <input type="text" class="form-control" disabled="disabled" name="modelofi" id="modelofi">
                                    </div>
                                    <h4><b>INFORMACION DEL EDIFICIO</b></h4>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Nombre</label>
                                        <input type="text" class="form-control" readonly="true" name="edificiofi" id="edificiofi">
                                        <input type="hidden" class="form-control" name="codclifi" id="codclifi">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Direccion</label>
                                        <input type="text" class="form-control" disabled="disabled" name="direccionfi" id="direccionfi">
                                    </div>

                                    <h4><b>INFORMACION DE INICIO DEL SERVICIO</b></h4>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Fecha <span class="required">*</span></label>
                                        <input type="text" class="form-control" disabled="disabled" name="fechainifi" id="fechainifi">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Hora <span class="required">*</span></label>
                                        <input type="text" class="form-control" disabled="disabled" name="horainifi" id="horainifi">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Tipo de servicio <span class="required">*</span></label>
                                        <input type="text" class="form-control" disabled="disabled" name="tserviciofi" id="tserviciofi">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Estado del equipo al inicio del servicio</label>
                                        <input type="text" class="form-control" disabled="disabled" name="estadoinifi" id="estadoinifi">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label for="observacioniniso">Observaciones de inicio de servicio</label>
                                        <textarea type="text" id="observacioniniso" name="observacioniniso" disabled="disabled" class="resizable_textarea form-control"></textarea>
                                    </div>
                                    <h4><b>INFORMACION DE FINALIZACION DEL SERVICIO</b></h4>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Estado del equipo despues del servicio <span class="required">*</span></label>
                                        <input type="hidden" id="idserviciofi" name="idserviciofi" class="form-control">
                                        <input type="hidden" id="idascensorfi" name="idascensorfi" class="form-control">
                                        <input type="hidden" id="supervisorID" name="supervisorID" class="form-control">
                                        <input type="hidden" id="latitudfi" name="latitudfi" class="form-control">
                                        <input type="hidden" id="longitudfi" name="longitudfi" class="form-control">
                                        <select class="form-control selectpicker" data-live-search="true" id="idestadofi" name="idestadofi" required="Campo requerido">
                                        </select>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label for="observacionfi">Observaciones de finalizacion del servicio</label>
                                        <textarea type="text" id="observacionfi" name="observacionfi" class="resizable_textarea form-control" placeholder="Ingrese observación"></textarea>
                                    </div>
                                    <div id="obsInt">
                                        <div id="terminoreparacion" class="col-md-12 col-sm-12 col-xs-12 form-group">
                                            <label for="terminado">¿Se termino el trabajo a realizar?</label>
                                            <select class="form-control selectpicker" data-live-search="true" id="terminado" name="terminado" required="required">
                                                <option disabled>Seleccione una Opción</option>
                                                <option value="y">SI</option>
                                                <option value="n">NO</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="divAyudantes">
                                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                            <label for="opayu">¿Realizó el trabajo con ayudante?</label>
                                            <select class="form-control selectpicker" data-live-search="true" onchange="addayudante1()" id="opayu" name="opayu" required="Campo requerido">
                                                <option value="" selected disabled>SELECCIONE OPCIÓN</option>
                                                <option value="S">SI</option>
                                                <option value="N">NO</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12 form-group" id="cantAyu" style="display: none">
                                            <label for="cantAyud">Ingrese cantidad de ayudantes</label>
                                            <select class="form-control selectpicker" data-live-search="true" onchange="addayudante2()" id="cantAyud" name="cantAyud">
                                                <option value="" selected disabled>SELECCIONE OPCIÓN</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                            </select>
                                        </div>
                                        <div id="ayudantes"></div>
                                    </div>
                                    <div id="formdoc" name="formdoc">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Generar solicitud de presupueto</label>
                                        <select class="form-control selectpicker" data-live-search="true" onchange="addpreform2()" id="oppre" name="oppre" required="Campo requerido">
                                            <option value="" selected disabled>SELECCIONE OPCION</option>
                                            <option value="1">SI</option>
                                            <option value="2">NO</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group" id="zonaUpload" style="display: none">
                                        <label for="descripcion2">Imagenes Presupuesto (máx. 3)</label>
                                        <div class="dropzone" id="myDropzone"></div>
                                    </div>
                                    <div id="formpre" name="formpre">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Opciones de firma del servicio</label>
                                        <select class="form-control selectpicker" data-live-search="true" onchange="addvalidform()" id="opfirma" name="opfirma" required="Campo requerido">
                                            <option value="" selected disabled>SELECCIONE OPCION</option>
                                            <option value="1">FIRMADA</option>
                                            <option value="2">POSPONER</option>
                                            <option value="3">NO REQUIERE FIRMA</option>
                                        </select>
                                    </div>
                                    <div id="formfirma" name="formfirma">
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="ln_solid"></div>
                                    <div class="form-group">
                                        <div class="col-md-6 col-sm-6 col-xs-12 left-margin">
                                            <button class="btn btn-primary" type="button" onclick="cancelarform()" id="btnVolver">Volver</button>
                                            <button class="btn btn-success" type="submit" id="btnFinalizar">Finalizar Servicio</button>
                                        </div>
                                    </div>

                                </form>
                            </div>

                            <div id="otradata" style="display: none;" class="x_content">

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /page content -->

        <div class="modal fade" id="modalPreview" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" id="contenido">

                </div>
            </div>
        </div>
        
        <div class="modal fade" id="uploadPreview" role="dialog" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" id="contenido">
                    <div id="uploadDiv" style="text-align: center;padding: 25px;">
                        <div class="progress">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" id="contador" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                            </div>
                        </div>    

                        <p class="text-center">
                            <h1>Subiendo Imágenes<br><small>por favor no recargar la pagina</small></h1>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    <?php
    } else {
        require 'nopermiso.php';
    }
    require 'footer.php';
    ?>
    <link rel="stylesheet" type="text/css" href="../public/build/css/dropzone.css">
    <link rel="stylesheet" type="text/css" href="../public/build/css/component-dropzone.css">
    <script type="text/javascript" src="../public/build/js/instascan.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
    <script src="../public/build/js/dropzone.js" type="text/javascript"></script>
    <!-- <script type="text/javascript" src="scripts/servicio.js"></script> -->
    <script id="myScript"></script>
    <script>
        var url = 'scripts/servicio.js';
        var extra = '?t=';
        var randomNum = String((Math.floor(Math.random() * 20000000000)));
        document.getElementById('myScript').src = url + extra + randomNum;
    </script>
    <script>
        var uploadOK = false;
        // Dropzone.autoDiscover = false;
        Dropzone.prototype.defaultOptions.dictDefaultMessage = "<b style=\"color:black\">ARRASTRA O SELECCIONA TUS ARCHIVOS AQUÍ</b>";
        Dropzone.prototype.defaultOptions.dictFallbackMessage = "Su navegador no admite la carga de archivos de arrastrar y soltar.";
        Dropzone.prototype.defaultOptions.dictFallbackText = "Utilice el formulario de reserva a continuación para cargar sus archivos como en los viejos tiempos.";
        Dropzone.prototype.defaultOptions.dictFileTooBig = "El archivo es demasiado grande ({{filesize}} MiB). Tamaño máximo de archivo: {{maxFilesize}} MiB.";
        Dropzone.prototype.defaultOptions.dictInvalidFileType = "No puedes subir archivos de este tipo.";
        Dropzone.prototype.defaultOptions.dictResponseError = "El servidor respondió con el código {{statusCode}}.";
        Dropzone.prototype.defaultOptions.dictCancelUpload = "Cancelar Subida";
        Dropzone.prototype.defaultOptions.dictCancelUploadConfirmation = "¿Estás seguro de que deseas cancelar esta carga?";
        Dropzone.prototype.defaultOptions.dictRemoveFile = "Quitar Archivo";
        Dropzone.prototype.defaultOptions.dictMaxFilesExceeded = "No puedes subir mas archivos.";
        // Dropzone.autoDiscover = false;
        Dropzone.options.myDropzone = {
            url: $('#formulariofin').attr('action'),
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 3,
            maxFiles: 3,
            timeout:0,
            // autoQueue: true,
            // autoProcessQueue: true,
            //maxFilesize: 10,
            acceptedFiles: 'image/*',
            addRemoveLinks: true,
            init: function() {
                dzClosure = this;

                document.getElementById("btnFinalizar").addEventListener("click", function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if ($('#oppre').val() == '1') {
                        if (dzClosure.files.length) {
                            dzClosure.processQueue();
                            if (uploadOK)
                                $('form#formulariofin').submit();

                        } else {
                            if (!$("#formulariofin")[0].checkValidity())
                                $("#formulariofin")[0].reportValidity();
                            else {
                                if (!$("#file01").val() && !$("#file02").val() && !$("#file03").val())
                                    alert('Debe subir al menos una imagen de presupuesto.');
                                else{
                                    FinalizarServ();
                                }
                            }
                        }
                    } else {
                        if (!$("#formulariofin")[0].checkValidity())
                            $("#formulariofin")[0].reportValidity();
                        else{
                            FinalizarServ();
                        }
                    }
                });
                
                this.on("totaluploadprogress", function (progress) {
                    var progr = document.querySelector(".progress .determinate");
                        $("#contador").css('width', progress + "%");
                        console.log('entra aca');
                });

                //// Envia todo el fomulario con las imagenes incluidas:
                this.on("sendingmultiple", function(data, xhr, formData) {
                    var formData2 = new FormData($('#formulariofin')[0]);
                    var poData = jQuery($('#formulariofin')[0]).serializeArray();
                    for (var i = 0; i < poData.length; i++) {
                        formData.append(poData[i].name, poData[i].value);
                    }
                });

                //// Envia todo el fomulario con las imagenes incluidas:
                // this.on("success", function(data, xhr, formData) {
                this.on("success", function(data, xhr, formData) {
                    //console.log('CADA UNO');
                    //console.log(xhr);
                    obj = JSON.parse(xhr);
                    console.log(obj.count);
//                    $("#attachments").val(obj.id);

                    //console.log(data.upload.filename);
                    if (!$('#file01').val()) {
                        $('#file01').val(data.upload.filename);
                        return;
                    }
                    if (!$('#file02').val()) {
                        $('#file02').val(data.upload.filename);
                        return;
                    }
                    if (!$('#file03').val()) {
                        $('#file03').val(data.upload.filename);
                        return;
                    }
                });

                //// cuando se termino de procesar todas las imagenes
                // this.on("queuecomplete", function(data, xhr, formData) {
                this.on("queuecomplete", function(xhr) {
                    // alert('TODO SUBIDO');
                    uploadOK = true;
                    console.log(xhr);
                    $('#uploadPreview').modal('hide');
                    $('form#formulariofin').submit();
                });

                //Si se elimina el archivo por dropzone se deja en blanco
                //la variable oculta que contenia el nombre del archivo
                this.on("removedfile", function(file) {
                    var name = file.name;
                    if ($('#file01').val() == name)
                        $('#file01').val('');
                    if ($('#file02').val() == name)
                        $('#file02').val('');
                    if ($('#file03').val() == name)
                        $('#file03').val('');
                });
            }
        }
    </script>
<?php
}
ob_end_flush();
?>