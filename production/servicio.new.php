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
                                        <input type="hidden" class="form-control" disabled="disabled" name="servicecallID" id="servicecallID">
                                        <input type="hidden" class="form-control" disabled="disabled" name="customerCode" id="customerCode">
                                        <input type="hidden" class="form-control" disabled="disabled" name="itemcode" id="itemcode">
                                        <input type="hidden" disabled class="form-control" name="equipmentcardnum" id="equipmentcardnum">
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
                                        <input type="hidden" disabled class="form-control" name="codclisap" id="codclisap">
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
                                        <input type="hidden" id="latitudso" name="latitudso" class="form-control">
                                        <input type="hidden" id="longitudso" name="longitudso" class="form-control">
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
    <!-- <script type="text/javascript" src="scripts/servicio.js"></script> -->
    <script id="myScript"></script>
    <script>
        var url = 'scripts/servicio.js';
        var extra = '?t=';
        var randomNum = String((Math.floor(Math.random() * 20000000000)));
        document.getElementById('myScript').src = url + extra + randomNum;
    </script>
<?php
}
ob_end_flush();
?>