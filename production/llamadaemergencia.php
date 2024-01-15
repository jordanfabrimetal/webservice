<?php
ob_start();
session_name('SESS_GSAP');
session_start();

if (!isset($_SESSION["nombre"])) {
    header("Location:login.php");
} else {

    require 'header.php';

    if ($_SESSION['FC3'] == 1 || $_SESSION['administrador'] == 1) {
        ?>

        <!-- page content -->
        <div class="right_col" role="main">
            <div class="">

                <div class="clearfix"></div>

                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Llamada de Emergencia</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div id="listadoemergencia" class="x_content">
                            	<div id="filtroguias" class="x_content" style="margin-bottom: 15px;">
									<div class="form-group">
										<div class="col-md-5">
											<label>Cliente</label>
											<select id="cliente" name="cliente" data-live-search="true" class="selectpicker form-control"></select>
										</div>
										<div class="col-md-4">
											<label>Edificio</label>
											<select id="edificio" name="edificio" data-live-search="true" class="selectpicker form-control"></select>
										</div>
										<div class="col-md-3">
											<label>Centro de Costo</label>
											<select id="cencosto" name="cencosto" data-live-search="true" class="selectpicker form-control"></select>
										</div>
									</div>
								<br><br>
								</div>
                                <table id="tblemergencia" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>OPCIONES</th>
                                            <th>FM</th>
                                            <th>Cliente</th>
                                            <th>Edificio</th>
                                            <th>Dirección</th>
                                            <th>Estado Asensor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>


                            <div id="formularioemergencia" class="x_content">
                                <br />
                                <div class="clearfix"></div>
                                <form id="formulario" name="formulario" method="post" class="form-horizontal form-label-left" enctype="multipart/form-data">
                                    <h4><b>INFORMACION DEL EQUIPO</b></h4>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Codigo</label>
                                        <input type="hidden" class="form-control" name="fmform" id="fmform">
                                        <input type="hidden" class="form-control" name="customercodeform" id="customercodeform">
                                        <input type="hidden" class="form-control" name="itemcodeform" id="itemcodeform">
                                        <input type="text" class="form-control" disabled="disabled" name="codigoform" id="codigoform">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Tipo de Ascensor</label>
                                        <input type="text" class="form-control" disabled="disabled" name="tascensorform" id="tascensorform">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Marca</label>
                                        <input type="text" class="form-control" disabled="disabled" name="marcaform" id="marcaform">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Modelo</label>
                                        <input type="text" class="form-control" disabled="disabled" name="modeloform" id="modeloform">
                                    </div>
                                    <h4><b>INFORMACION DEL EDIFICIO</b></h4>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Nombre</label>
                                        <input type="text" class="form-control" disabled="disabled" name="edificioform" id="edificioform">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Direccion</label>
                                        <input type="text" class="form-control" disabled="disabled" name="direccionform" id="direccionform">
                                    </div>
                                    <h4><b>INFORMACION DE INICIO DEL SERVICIO</b></h4>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Tipo de servicio </label>
                                        <input type="text" class="form-control" disabled="disabled" name="tservicioform" id="tservicioform">
                                    </div>                                   
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label>Ultimo estado del equipo</label>
                                        <input type="text" class="form-control" disabled="disabled" name="estadoiniform" id="estadoiniform">
                                    </div>    
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label for="observacionini">Observaciones de la Llamada</label>
                                        <textarea type="text" id="observacioniniform" name="observacioniniform" required="required" class="resizable_textarea form-control"></textarea>
                                    </div>       
                                    
                                    <div class="clearfix"></div>
                                    <div class="ln_solid"></div>
                                    <div class="form-group">
                                        <div class="col-md-6 col-sm-6 col-xs-12 left-margin">
                                            <button class="btn btn-primary" type="button" id="btnCancelar"  onclick="cancelarform()">Cancelar</button>
                                            <button class="btn btn-success" type="submit" id="btnGuardar">Crear Llamada de Emergencia</button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /page content -->

            <!-- /page content -->
            <?php
        } else {
            require 'nopermiso.php';
        }
        require 'footer.php';
        ?>
            <script type="text/javascript" src="scripts/llamadaemergencia.js"></script>
        <?php
    }
    ob_end_flush();
    ?>